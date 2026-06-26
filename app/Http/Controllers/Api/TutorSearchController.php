<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tutor;
use App\Models\TutorSchedule;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TutorSearchController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $query = Tutor::where('status', 'active')
            ->with(['user', 'tutorSubjects.subjectCategory'])
            ->withCount('orders as session_count');

        if ($request->filled('mata_pelajaran_id')) {
            $mapelId = (int) $request->mata_pelajaran_id;
            $query->whereHas('tutorSubjects', fn ($q) => $q->where('subject_category_id', $mapelId));
        }

        switch ($request->input('sort', 'popular')) {
            case 'rating':
                $query->orderByDesc('session_count');
                break;
            case 'price_asc':
                $query->orderBy('hourly_rate');
                break;
            case 'price_desc':
                $query->orderByDesc('hourly_rate');
                break;
            default:
                $query->orderByDesc('session_count');
        }

        $perPage = 6;
        $page = max(1, (int) $request->input('page', 1));
        $total = $query->count();
        $tutors = $query->skip(($page - 1) * $perPage)->take($perPage)->get();

        $data = $tutors->map(fn ($t) => [
            'id' => $t->id,
            'name' => $t->name,
            'bio' => $t->desc ?? 'Tutor berpengalaman siap membantu belajarmu.',
            'hourly_rate' => (float) $t->hourly_rate,
            'rating' => 0,
            'rating_count' => 0,
            'session_count' => $t->session_count,
            'subjects' => $t->tutorSubjects->map(fn ($ts) => $ts->subjectCategory->name ?? '-')->toArray(),
        ])->toArray();

        return response()->json([
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'has_more' => ($page * $perPage) < $total,
        ]);
    }

    public function jadwal(Request $request, int $tutorId): JsonResponse
    {
        $request->validate([
            'tanggal' => ['required', 'date'],
        ]);

        $tanggal = Carbon::parse($request->tanggal);
        $dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $dayName = $dayNames[$tanggal->dayOfWeek];

        $schedules = TutorSchedule::where('tutor_id', $tutorId)
            ->where('day', $dayName)
            ->with(['scheduleLocks' => function ($q) use ($tanggal) {
                $q->where('tanggal', $tanggal->toDateString())
                    ->whereIn('status', ['locked', 'confirmed']);
            }])
            ->orderBy('jam_start')
            ->get()
            ->map(function ($s) {
                $lock = $s->scheduleLocks->first();
                $isBooked = false;

                if ($lock) {
                    if ($lock->status === 'confirmed') {
                        $isBooked = true;
                    } elseif ($lock->status === 'locked' && $lock->expired_at && $lock->expired_at->isFuture()) {
                        $isBooked = true;
                    }
                }

                return [
                    'id' => $s->id,
                    'jam_mulai' => $s->jam_start instanceof Carbon ? $s->jam_start->format('H:i') : $s->jam_start,
                    'jam_selesai' => $s->jam_end instanceof Carbon ? $s->jam_end->format('H:i') : $s->jam_end,
                    'is_booked' => $isBooked,
                ];
            });

        return response()->json([
            'tanggal' => $tanggal->toDateString(),
            'hari' => $dayName,
            'data' => $schedules,
        ]);
    }

    public function availableDays(Request $request, int $tutorId): JsonResponse
    {
        $days = TutorSchedule::where('tutor_id', $tutorId)
            ->distinct()
            ->pluck('day');

        return response()->json(['days' => $days->values()->all()]);
    }
}
