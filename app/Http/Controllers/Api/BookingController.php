<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TutorProfile;
use App\Models\TutorSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * POST /api/booking
     * Siswa membuat booking baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'tutor_profile_id' => ['required', 'exists:tutor_profiles,id'],
            'schedule_id'      => ['required', 'exists:tutor_schedules,id'],
            'durasi'           => ['required', 'numeric', 'in:1,1.5,2,3'],
            'catatan'          => ['nullable', 'string', 'max:300'],
        ]);

        $tutor    = TutorProfile::findOrFail($request->tutor_profile_id);
        $schedule = TutorSchedule::where('id', $request->schedule_id)
            ->where('tutor_profile_id', $tutor->id)
            ->where('is_available', true)
            ->firstOrFail();

        // Cek slot tidak di-booking orang lain saat bersamaan (race condition guard)
        DB::beginTransaction();
        try {
            $alreadyBooked = Booking::where('schedule_id', $schedule->id)
                ->whereIn('status', ['pending','confirmed'])
                ->lockForUpdate()
                ->exists();

            if ($alreadyBooked) {
                DB::rollBack();
                return response()->json(['message' => 'Slot jadwal sudah dipesan orang lain.'], 409);
            }

            $totalPrice = $tutor->hourly_rate * $request->durasi;

            $booking = Booking::create([
                'user_id'          => Auth::id(),
                'tutor_profile_id' => $tutor->id,
                'schedule_id'      => $schedule->id,
                'mata_pelajaran_id'=> $tutor->mataPelajaran->first()?->id,
                'scheduled_day'    => $schedule->hari,
                'scheduled_time'   => $schedule->jam_mulai,
                'duration'         => $request->durasi,
                'total_price'      => $totalPrice,
                'catatan'          => $request->catatan,
                'status'           => 'pending',
                'payment_status'   => 'unpaid',
            ]);

            DB::commit();

            // Kirim email notifikasi ke tutor
            // Mail::to($tutor->user->email)->queue(new NewBookingRequestMail($booking));

            return response()->json([
                'message'    => 'Pemesanan berhasil dibuat. Menunggu konfirmasi tutor.',
                'booking_id' => $booking->id,
                'redirect'   => route('siswa.pemesanan'),
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return response()->json(['message' => 'Terjadi kesalahan. Silakan coba lagi.'], 500);
        }
    }

    /**
     * POST /api/booking/{booking}/terima  (tutor)
     */
    public function terima(Booking $booking)
    {
        $tutor = Auth::user()->tutorProfile;
        abort_if($booking->tutor_profile_id !== $tutor->id, 403);
        abort_if($booking->status !== 'pending', 422, 'Booking sudah tidak pending.');

        $booking->update(['status' => 'confirmed']);

        if ($booking->schedule_id) {
            TutorSchedule::where('id', $booking->schedule_id)->update(['is_available' => false]);
        }

        return response()->json(['message' => 'Booking diterima.']);
    }

    /**
     * POST /api/booking/{booking}/tolak  (tutor)
     */
    public function tolak(Request $request, Booking $booking)
    {
        $tutor = Auth::user()->tutorProfile;
        abort_if($booking->tutor_profile_id !== $tutor->id, 403);
        abort_if($booking->status !== 'pending', 422, 'Booking sudah tidak pending.');

        $booking->update([
            'status'       => 'cancelled',
            'tolak_alasan' => $request->input('alasan'),
        ]);

        if ($booking->schedule_id) {
            TutorSchedule::where('id', $booking->schedule_id)->update(['is_available' => true]);
        }

        return response()->json(['message' => 'Booking ditolak.']);
    }
}
