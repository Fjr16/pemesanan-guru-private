<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PemesananController extends Controller
{
    protected function allOrders()
    {
        return collect([
            [
                'id'          => 1,
                'tutor_name'  => 'Budi Santoso',
                'tutor_email' => 'budi@tutor.com',
                'mapel'       => 'Matematika',
                'hari'        => 'Senin',
                'jam'         => '15:00',
                'durasi'      => 2,
                'total'       => 200000,
                'status'      => 'confirmed',
                'catatan'     => 'Persiapan ujian semester.',
                'created_at'  => '20 Jun 2026',
            ],
            [
                'id'          => 2,
                'tutor_name'  => 'Siti Aminah',
                'tutor_email' => 'siti@tutor.com',
                'mapel'       => 'Bahasa Inggris',
                'hari'        => 'Rabu',
                'jam'         => '10:00',
                'durasi'      => 1,
                'total'       => 100000,
                'status'      => 'pending',
                'catatan'     => '',
                'created_at'  => '21 Jun 2026',
            ],
            [
                'id'          => 3,
                'tutor_name'  => 'Andi Pratama',
                'tutor_email' => 'andi@tutor.com',
                'mapel'       => 'Fisika',
                'hari'        => 'Jumat',
                'jam'         => '13:30',
                'durasi'      => 2,
                'total'       => 180000,
                'status'      => 'completed',
                'catatan'     => 'Materi mekanika.',
                'created_at'  => '15 Jun 2026',
            ],
            [
                'id'          => 4,
                'tutor_name'  => 'Dewi Lestari',
                'tutor_email' => 'dewi@tutor.com',
                'mapel'       => 'Kimia',
                'hari'        => 'Selasa',
                'jam'         => '16:00',
                'durasi'      => 1,
                'total'       => 95000,
                'status'      => 'cancelled',
                'catatan'     => '',
                'created_at'  => '14 Jun 2026',
            ],
            [
                'id'          => 5,
                'tutor_name'  => 'Budi Santoso',
                'tutor_email' => 'budi@tutor.com',
                'mapel'       => 'Matematika',
                'hari'        => 'Kamis',
                'jam'         => '09:00',
                'durasi'      => 2,
                'total'       => 200000,
                'status'      => 'completed',
                'catatan'     => 'Latihan soal trigonometri.',
                'created_at'  => '10 Jun 2026',
            ],
            [
                'id'          => 6,
                'tutor_name'  => 'Rina Hartati',
                'tutor_email' => 'rina@tutor.com',
                'mapel'       => 'Biologi',
                'hari'        => 'Sabtu',
                'jam'         => '08:00',
                'durasi'      => 1,
                'total'       => 90000,
                'status'      => 'pending',
                'catatan'     => 'Materi genetika.',
                'created_at'  => '22 Jun 2026',
            ],
            [
                'id'          => 7,
                'tutor_name'  => 'Siti Aminah',
                'tutor_email' => 'siti@tutor.com',
                'mapel'       => 'Bahasa Inggris',
                'hari'        => 'Senin',
                'jam'         => '14:00',
                'durasi'      => 2,
                'total'       => 200000,
                'status'      => 'completed',
                'catatan'     => '',
                'created_at'  => '08 Jun 2026',
            ],
            [
                'id'          => 8,
                'tutor_name'  => 'Andi Pratama',
                'tutor_email' => 'andi@tutor.com',
                'mapel'       => 'Fisika',
                'hari'        => 'Selasa',
                'jam'         => '11:00',
                'durasi'      => 1,
                'total'       => 90000,
                'status'      => 'cancelled',
                'catatan'     => 'Bentrok jadwal.',
                'created_at'  => '05 Jun 2026',
            ],
        ]);
    }

    public function index(Request $request)
    {
        $status = $request->input('status', 'all');
        $all    = $this->allOrders();

        $counts = [
            'all'       => $all->count(),
            'pending'   => $all->where('status', 'pending')->count(),
            'confirmed' => $all->where('status', 'confirmed')->count(),
            'completed' => $all->where('status', 'completed')->count(),
            'cancelled' => $all->where('status', 'cancelled')->count(),
        ];

        $orders = $status === 'all'
            ? $all
            : $all->where('status', $status);

        return view('pages.siswa.pemesanan', compact('orders', 'counts'));
    }

    public function show($id)
    {
        $all   = $this->allOrders();
        $order = $all->firstWhere('id', (int) $id);

        abort_unless($order, 404);

        return view('pages.siswa.pemesanan-detail', compact('order'));
    }

    public function cancel(Request $request, $id)
    {
        return redirect()->route('siswa.pemesanan')->with('success', 'Pemesanan berhasil dibatalkan.');
    }
}
