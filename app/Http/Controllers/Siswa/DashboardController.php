<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_booking' => 12,
            'pending'       => 2,
            'completed'     => 8,
            'cancelled'     => 2,
        ];

        $pendingCount = $stats['pending'];

        $upcomingSessions = collect([
            [
                'tutor_name' => 'Budi Santoso',
                'mapel'      => 'Matematika',
                'hari'       => 'Senin',
                'jam'        => '15:00',
                'status'     => 'confirmed',
            ],
            [
                'tutor_name' => 'Siti Aminah',
                'mapel'      => 'Bahasa Inggris',
                'hari'       => 'Rabu',
                'jam'        => '10:00',
                'status'     => 'pending',
            ],
            [
                'tutor_name' => 'Andi Pratama',
                'mapel'      => 'Fisika',
                'hari'       => 'Jumat',
                'jam'        => '13:30',
                'status'     => 'confirmed',
            ],
        ]);

        $favoriteTutors = collect([
            ['name' => 'Budi Santoso',  'mapel' => 'Matematika',        'total_sesi' => 5],
            ['name' => 'Siti Aminah',   'mapel' => 'Bahasa Inggris',    'total_sesi' => 4],
            ['name' => 'Andi Pratama',  'mapel' => 'Fisika',            'total_sesi' => 3],
        ]);

        return view('pages.siswa.dashboard', compact(
            'stats', 'pendingCount', 'upcomingSessions', 'favoriteTutors'
        ));
    }
}
