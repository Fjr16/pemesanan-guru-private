<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function show($id)
    {
        $order = [
            'id'            => (int) $id,
            'tutor_name'    => 'Budi Santoso',
            'tutor_email'   => 'budi@tutor.com',
            'mapel'         => 'Matematika',
            'hari'          => 'Senin',
            'jam'           => '15:00',
            'durasi'        => 2,
            'tarif_per_jam' => 100000,
            'total'         => 200000,
            'status'        => 'confirmed',
            'created_at'    => '20 Jun 2026',
        ];

        return view('pages.siswa.pembayaran', compact('order'));
    }

    public function process(Request $request, $id)
    {
        $request->validate([
            'payment_method' => ['required', 'in:transfer,ewallet,qris'],
            'bukti_bayar'    => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        return response()->json([
            'message'  => 'Bukti pembayaran berhasil diunggah.',
            'redirect' => route('siswa.pembayaran.sukses', $id),
        ]);
    }

    public function success($id)
    {
        $order = [
            'id'            => (int) $id,
            'tutor_name'    => 'Budi Santoso',
            'tutor_email'   => 'budi@tutor.com',
            'mapel'         => 'Matematika',
            'hari'          => 'Senin',
            'jam'           => '15:00',
            'durasi'        => 2,
            'tarif_per_jam' => 100000,
            'total'         => 200000,
            'status'        => 'confirmed',
            'created_at'    => '20 Jun 2026',
        ];

        return view('pages.siswa.pembayaran-sukses', compact('order'));
    }
}
