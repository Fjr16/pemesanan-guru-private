<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ScheduleLock;
use App\Models\TutorSchedule;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();

        if ($user->role !== 'siswa') {
            return response()->json(['message' => 'Hanya siswa yang dapat melakukan pemesanan.'], 403);
        }

        $student = $user->student;
        if (! $student) {
            return response()->json(['message' => 'Data siswa tidak ditemukan, mohon lengkapi profil anda'], 404);
        }

        $validated = $request->validate([
            'schedule_ids' => ['required', 'array', 'min:1'],
            'schedule_ids.*' => ['required', 'integer', 'exists:tutor_schedules,id'],
            'tanggal' => ['required', 'date', 'after:today'],
            'catatan' => ['nullable', 'string', 'max:300'],
        ]);

        $schedules = TutorSchedule::with('tutor')
            ->whereIn('id', $validated['schedule_ids'])
            ->get();

        if ($schedules->count() !== count($validated['schedule_ids'])) {
            return response()->json(['message' => 'Beberapa slot tidak ditemukan.'], 422);
        }

        $tutorId = $schedules->first()->tutor_id;
        if ($schedules->contains(fn ($s) => $s->tutor_id !== $tutorId)) {
            return response()->json(['message' => 'Semua slot harus dari tutor yang sama.'], 422);
        }

        $tutor = $schedules->first()->tutor;
        if (! $tutor || $tutor->status !== 'active') {
            return response()->json(['message' => 'Tutor tidak aktif.'], 422);
        }

        $selectedDate = Carbon::parse($validated['tanggal']);
        $minDate = now()->addDays(3)->startOfDay();

        if ($selectedDate->lt($minDate)) {
            return response()->json([
                'message' => 'Minimal booking H-3 (3 hari sebelum kelas). Pilih tanggal mulai '.$minDate->translatedFormat('d F Y').'.',
            ], 422);
        }

        $dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $dayName = $dayNames[$selectedDate->dayOfWeek];

        if ($schedules->contains(fn ($s) => $s->day !== $dayName)) {
            return response()->json([
                'message' => 'Semua slot harus di hari yang sama dengan tanggal yang dipilih ('.$dayName.').',
            ], 422);
        }

        DB::beginTransaction();
        try {
            foreach ($schedules as $schedule) {
                $lockExists = ScheduleLock::where('tutor_schedule_id', $schedule->id)
                    ->where('tanggal', $validated['tanggal'])
                    ->where(function ($q) {
                        $q->where('status', 'confirmed')
                            ->orWhere(function ($q2) {
                                $q2->where('status', 'locked')
                                    ->where('expired_at', '>', now());
                            });
                    })
                    ->exists();

                if ($lockExists) {
                    DB::rollBack();

                    $jamDisplay = $schedule->jam_start->format('H:i');

                    return response()->json([
                        'message' => 'Slot jam '.$jamDisplay.' pada tanggal tersebut sudah dipesan.',
                    ], 409);
                }
            }

            $totalPayment = $tutor->hourly_rate * $schedules->count();

            $order = Order::create([
                'tutor_id' => $tutor->id,
                'student_id' => $student->id,
                'status' => 'pending',
                'catatan' => $validated['catatan'],
                'total_payment' => $totalPayment,
                'expired_at' => now()->addHours(24),
            ]);

            foreach ($schedules as $schedule) {
                $jamStart = $schedule->jam_start->format('H:i');
                $jamEnd = $schedule->jam_end->format('H:i');

                $orderDetail = OrderDetail::create([
                    'order_id' => $order->id,
                    'tutor_schedule_id' => $schedule->id,
                    'tanggal' => $validated['tanggal'],
                    'jam_start' => $jamStart,
                    'jam_end' => $jamEnd,
                    'harga' => $tutor->hourly_rate,
                ]);

                ScheduleLock::create([
                    'tutor_schedule_id' => $schedule->id,
                    'order_detail_id' => $orderDetail->id,
                    'tanggal' => $validated['tanggal'],
                    'status' => 'locked',
                    'locked_at' => now(),
                    'expired_at' => now()->addHours(24),
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Pemesanan berhasil dibuat ('.$schedules->count().' jam). Menunggu konfirmasi tutor.',
                'order_id' => $order->id,
                'redirect' => route('siswa.pemesanan'),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return response()->json(['message' => 'Terjadi kesalahan. Silakan coba lagi.'], 500);
        }
    }
}
