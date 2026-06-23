<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_siswa'       => 128,
            'total_tutor_aktif' => 45,
            'tutor_pending'     => 3,
            'total_booking'     => 312,
            'booking_bulan_ini' => 47,
            'sesi_selesai'      => 38,
            'total_transaksi'   => 24650000,
            'growth_booking'    => 12,
        ];

        $pendingTutors = collect([
            (object)['id'=>1, 'user'=>(object)['name'=>'Ahmad Rifai','email'=>'ahmad@mail.com'], 'created_at'=>Carbon::now()->subDays(2), 'mataPelajaran'=>collect([(object)['nama'=>'Matematika'],(object)['nama'=>'Fisika']])],
            (object)['id'=>2, 'user'=>(object)['name'=>'Siti Nurhaliza','email'=>'siti@mail.com'], 'created_at'=>Carbon::now()->subDays(1), 'mataPelajaran'=>collect([(object)['nama'=>'Bahasa Inggris']])],
            (object)['id'=>3, 'user'=>(object)['name'=>'Dwi Wahyuni','email'=>'dwi@mail.com'], 'created_at'=>Carbon::now(), 'mataPelajaran'=>collect([(object)['nama'=>'Kimia'],(object)['nama'=>'Biologi']])],
        ]);

        $recentBookings = collect([
            (object)['id'=>1, 'user'=>(object)['name'=>'Budi Santoso'], 'mataPelajaran'=>(object)['nama'=>'Matematika'], 'status'=>'pending', 'created_at'=>Carbon::now()->subHours(2)],
            (object)['id'=>2, 'user'=>(object)['name'=>'Anisa Putri'], 'mataPelajaran'=>(object)['nama'=>'Bahasa Inggris'], 'status'=>'confirmed', 'created_at'=>Carbon::now()->subHours(5)],
            (object)['id'=>3, 'user'=>(object)['name'=>'Rizky Pratama'], 'mataPelajaran'=>(object)['nama'=>'Fisika'], 'status'=>'completed', 'created_at'=>Carbon::now()->subDay()],
            (object)['id'=>4, 'user'=>(object)['name'=>'Dewi Lestari'], 'mataPelajaran'=>(object)['nama'=>'Kimia'], 'status'=>'pending', 'created_at'=>Carbon::now()->subDays(2)],
        ]);

        $popularMapel = collect([
            (object)['nama'=>'Matematika','bookings_count'=>24],
            (object)['nama'=>'Bahasa Inggris','bookings_count'=>18],
            (object)['nama'=>'Fisika','bookings_count'=>11],
            (object)['nama'=>'Kimia','bookings_count'=>8],
            (object)['nama'=>'Biologi','bookings_count'=>5],
        ]);

        return view('pages.admin.dashboard', compact(
            'stats','pendingTutors','recentBookings','popularMapel'
        ));
    }
}
