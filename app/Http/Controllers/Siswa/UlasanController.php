<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UlasanController extends Controller
{
    public function store(Request $request, Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);
        abort_if($booking->status !== 'completed', 422, 'Hanya booking selesai yang dapat diulas.');
        abort_if($booking->review()->exists(), 422, 'Anda sudah memberikan ulasan untuk sesi ini.');

        $request->validate([
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'max:500'],
        ]);

        Review::create([
            'booking_id'       => $booking->id,
            'tutor_profile_id' => $booking->tutor_profile_id,
            'user_id'          => Auth::id(),
            'rating'           => $request->rating,
            'comment'          => $request->comment,
        ]);

        return response()->json(['message' => 'Ulasan berhasil dikirim. Terima kasih!']);
    }
}
