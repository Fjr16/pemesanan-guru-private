<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function show(Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);
        abort_if($booking->status !== 'confirmed', 404, 'Booking belum dikonfirmasi tutor.');

        $booking->load(['tutorProfile.user', 'mataPelajaran']);

        return view('siswa.pembayaran', compact('booking'));
    }

    public function process(Request $request, Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);

        $request->validate([
            'payment_method' => ['required', 'in:transfer,ewallet,qris'],
            'bukti_bayar'    => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        // Simpan file bukti pembayaran
        $path = $request->file('bukti_bayar')
            ->store('bukti-pembayaran/' . $booking->id, 'public');

        $booking->update([
            'payment_method'  => $request->payment_method,
            'bukti_bayar'     => $path,
            'payment_status'  => 'pending_verification',
        ]);

        // Kirim notifikasi email ke admin (via Event/Job — pasang setelah model siap)
        // event(new PaymentUploaded($booking));

        return response()->json([
            'message'  => 'Bukti pembayaran berhasil diunggah.',
            'redirect' => route('siswa.pembayaran.sukses', $booking->id),
        ]);
    }

    public function success(Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);
        $booking->load(['tutorProfile.user', 'mataPelajaran']);
        return view('siswa.pembayaran-sukses', compact('booking'));
    }
}
