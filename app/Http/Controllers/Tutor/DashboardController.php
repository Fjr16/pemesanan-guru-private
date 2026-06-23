<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'pending'       => 3,
            'confirmed'     => 5,
            'completed'     => 48,
            'avg_rating'    => 4.8,
            'total_reviews' => 24,
            'pendapatan'    => 7200000,
        ];

        $pendingCount = 3;

        $pendingBookings = collect([
            (object)['id'=>1, 'siswa'=>'Budi Santoso','mapel'=>'Matematika','hari'=>'Senin','jam'=>'09:00','durasi'=>2,'catatan'=>'Mohon bantu saya persiapan ujian minggu depan.','waktu'=>'2 jam lalu'],
            (object)['id'=>2, 'siswa'=>'Anisa Putri','mapel'=>'Fisika','hari'=>'Selasa','jam'=>'14:00','durasi'=>1.5,'catatan'=>'','waktu'=>'5 jam lalu'],
            (object)['id'=>3, 'siswa'=>'Rizky Pratama','mapel'=>'Kimia','hari'=>'Rabu','jam'=>'10:00','durasi'=>2,'catatan'=>'Fokus pada materi stoikiometri.','waktu'=>'1 hari lalu'],
        ]);

        $todaySessions = collect([
            (object)['siswa'=>'Dewi Lestari','jam'=>'09:00','mapel'=>'Matematika'],
            (object)['siswa'=>'Farhan Maulana','jam'=>'14:00','mapel'=>'Fisika'],
        ]);

        return view('pages.tutor.dashboard', compact('stats', 'pendingCount', 'pendingBookings', 'todaySessions'));
    }
}
