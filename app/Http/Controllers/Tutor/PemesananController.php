<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemesananController extends Controller
{
    public function index(Request $request)
    {
        $tutor  = Auth::user()->tutorProfile;
        $status = $request->input('status', 'all');

        $query = Booking::with(['user', 'mataPelajaran'])
            ->where('tutor_profile_id', $tutor->id)
            ->orderBy('created_at', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $bookings     = $query->paginate(10);
        $pendingCount = Booking::where('tutor_profile_id', $tutor->id)->where('status', 'pending')->count();

        $counts = [
            'all'       => Booking::where('tutor_profile_id', $tutor->id)->count(),
            'pending'   => $pendingCount,
            'confirmed' => Booking::where('tutor_profile_id', $tutor->id)->where('status', 'confirmed')->count(),
            'completed' => Booking::where('tutor_profile_id', $tutor->id)->where('status', 'completed')->count(),
            'cancelled' => Booking::where('tutor_profile_id', $tutor->id)->where('status', 'cancelled')->count(),
        ];

        return view('tutor.pemesanan', compact('bookings', 'counts', 'pendingCount'));
    }

    public function terima(Booking $booking)
    {
        $this->authorizeBooking($booking);
        abort_if($booking->status !== 'pending', 422, 'Booking sudah tidak dalam status pending.');

        $booking->update(['status' => 'confirmed']);

        // Tandai slot sebagai booked
        if ($booking->schedule_id) {
            \App\Models\TutorSchedule::where('id', $booking->schedule_id)
                ->update(['is_available' => false]);
        }

        // Kirim email notifikasi ke siswa
        // Mail::to($booking->user->email)->queue(new BookingConfirmedMail($booking));

        return response()->json([
            'message' => 'Booking berhasil diterima. Email notifikasi dikirim ke siswa.',
        ]);
    }

    public function tolak(Request $request, Booking $booking)
    {
        $this->authorizeBooking($booking);
        abort_if($booking->status !== 'pending', 422, 'Booking sudah tidak dalam status pending.');

        $booking->update([
            'status'        => 'cancelled',
            'tolak_alasan'  => $request->input('alasan'),
        ]);

        // Bebaskan slot kembali
        if ($booking->schedule_id) {
            \App\Models\TutorSchedule::where('id', $booking->schedule_id)
                ->update(['is_available' => true]);
        }

        // Kirim email ke siswa
        // Mail::to($booking->user->email)->queue(new BookingRejectedMail($booking));

        return response()->json(['message' => 'Booking ditolak. Email dikirim ke siswa.']);
    }

    public function selesai(Booking $booking)
    {
        $this->authorizeBooking($booking);
        abort_if($booking->status !== 'confirmed', 422, 'Booking harus berstatus confirmed.');

        $booking->update(['status' => 'completed']);

        return response()->json(['message' => 'Sesi ditandai selesai.']);
    }

    private function authorizeBooking(Booking $booking): void
    {
        $tutor = Auth::user()->tutorProfile;
        abort_if($booking->tutor_profile_id !== $tutor->id, 403, 'Akses ditolak.');
    }
}
