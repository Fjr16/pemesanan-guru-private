<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemesananController extends Controller
{
    public function index(Request $request)
    {
        $user   = Auth::user();
        $status = $request->input('status', 'all');

        $query = Booking::with(['tutorProfile.user', 'mataPelajaran', 'review'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $bookings = $query->paginate(10);

        // Hitungan per status untuk tab
        $counts = [
            'all'       => Booking::where('user_id', $user->id)->count(),
            'pending'   => Booking::where('user_id', $user->id)->where('status', 'pending')->count(),
            'confirmed' => Booking::where('user_id', $user->id)->where('status', 'confirmed')->count(),
            'completed' => Booking::where('user_id', $user->id)->where('status', 'completed')->count(),
            'cancelled' => Booking::where('user_id', $user->id)->where('status', 'cancelled')->count(),
        ];

        $pendingCount = $counts['pending'];

        return view('siswa.pemesanan', compact('bookings', 'counts', 'pendingCount'));
    }

    public function show(Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);
        $booking->load(['tutorProfile.user', 'mataPelajaran', 'review']);
        return view('siswa.pemesanan-detail', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);

        if ($booking->status !== 'pending') {
            return response()->json([
                'message' => 'Hanya booking berstatus Pending yang dapat dibatalkan.'
            ], 422);
        }

        $booking->update(['status' => 'cancelled']);

        // Bebaskan kembali slot jadwal
        if ($booking->schedule_id) {
            \App\Models\TutorSchedule::where('id', $booking->schedule_id)
                ->update(['is_available' => true]);
        }

        return response()->json(['message' => 'Pemesanan berhasil dibatalkan.']);
    }
}
