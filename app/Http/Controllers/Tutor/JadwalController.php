<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\TutorSchedule;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class JadwalController extends Controller
{
    private static array $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

    public function index()
    {
        $tutor = Auth::user()->tutor;
        $days = self::$days;

        if (! $tutor) {
            return view('pages.tutor.jadwal', [
                'schedules' => collect(),
                'days' => self::$days,
            ]);
        }

        $schedules = TutorSchedule::where('tutor_id', $tutor->id)
            ->with(['scheduleLocks' => function ($q) {
                $q->where('tanggal', '>=', now()->toDateString())
                    ->whereIn('status', ['locked', 'confirmed']);
            }])
            ->orderByRaw("FIELD(day, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')")
            ->orderBy('jam_start')
            ->get()
            ->map(function ($slot) {
                $activeLocks = $slot->scheduleLocks->filter(function ($lock) {
                    if ($lock->status === 'confirmed') {
                        return true;
                    }

                    return $lock->status === 'locked' && $lock->expired_at && $lock->expired_at->isFuture();
                });

                return (object) [
                    'id' => $slot->id,
                    'day' => $slot->day,
                    'jam_start' => $slot->jam_start->format('H:i'),
                    'jam_end' => $slot->jam_end->format('H:i'),
                    'has_active_locks' => $activeLocks->isNotEmpty(),
                    'active_lock_count' => $activeLocks->count(),
                    'locked_dates' => $activeLocks->map(fn ($l) => [
                        'tanggal' => $l->tanggal->translatedFormat('d M'),
                        'status' => $l->status,
                    ])->values()->all(),
                ];
            });

        return view('pages.tutor.jadwal', compact('schedules', 'days'));
    }

    public function store(Request $request): JsonResponse
    {
        $tutor = Auth::user()->tutor;

        if (! $tutor) {
            return response()->json(['message' => 'Tutor tidak ditemukan.'], 404);
        }

        $validated = $request->validate([
            'day' => ['required', Rule::in(self::$days)],
            'jam_start' => ['required', 'date_format:H:i'],
        ]);

        $jamStart = Carbon::parse($validated['jam_start']);
        $jamEnd = $jamStart->copy()->addHour();
        $jamStartStr = $jamStart->format('H:i');
        $jamEndStr = $jamEnd->format('H:i');

        $exists = TutorSchedule::where('tutor_id', $tutor->id)
            ->where('day', $validated['day'])
            ->where('jam_start', $jamStartStr)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Slot jam '.$jamStartStr.' sudah ada di hari '.$validated['day'].'.',
            ], 422);
        }

        TutorSchedule::create([
            'tutor_id' => $tutor->id,
            'day' => $validated['day'],
            'jam_start' => $jamStartStr,
            'jam_end' => $jamEndStr,
        ]);

        return response()->json(['message' => 'Slot '.$jamStartStr.'–'.$jamEndStr.' berhasil ditambahkan.']);
    }

    public function destroy(int $id): JsonResponse
    {
        $tutor = Auth::user()->tutor;

        if (! $tutor) {
            return response()->json(['message' => 'Tutor tidak ditemukan.'], 404);
        }

        $slot = TutorSchedule::where('tutor_id', $tutor->id)->find($id);

        if (! $slot) {
            return response()->json(['message' => 'Slot jadwal tidak ditemukan.'], 404);
        }

        $hasActiveLocks = $slot->scheduleLocks()
            ->where('tanggal', '>=', now()->toDateString())
            ->where(function ($q) {
                $q->where('status', 'confirmed')
                    ->orWhere(function ($q2) {
                        $q2->where('status', 'locked')
                            ->where('expired_at', '>', now());
                    });
            })
            ->exists();

        if ($hasActiveLocks) {
            return response()->json([
                'message' => 'Slot ini memiliki booking mendatang dan tidak dapat dihapus.',
            ], 422);
        }

        $slot->delete();

        return response()->json(['message' => 'Slot jadwal berhasil dihapus.']);
    }
}
