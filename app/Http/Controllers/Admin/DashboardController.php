<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\MataPelajaran;
use App\Models\TutorProfile;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_siswa'       => User::where('role', 'siswa')->count(),
            'total_tutor_aktif' => TutorProfile::where('status', 'active')->count(),
            'tutor_pending'     => TutorProfile::where('status', 'pending')->count(),
            'total_booking'     => Booking::count(),
            'booking_bulan_ini' => Booking::whereMonth('created_at', now()->month)->count(),
            'sesi_selesai'      => Booking::where('status','completed')->whereMonth('updated_at', now()->month)->count(),
            'total_transaksi'   => Booking::where('payment_status','paid')->sum('total_price'),
            'growth_booking'    => $this->growthBooking(),
        ];

        $pendingTutorCount = $stats['tutor_pending'];

        $pendingTutors = TutorProfile::with(['user','mataPelajaran'])
            ->where('status','pending')
            ->orderBy('created_at','desc')
            ->take(10)
            ->get();

        $recentBookings = Booking::with(['user','tutorProfile.user','mataPelajaran'])
            ->orderByDesc('created_at')
            ->take(8)
            ->get();

        $popularMapel = MataPelajaran::withCount('bookings')
            ->orderByDesc('bookings_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats','pendingTutorCount','pendingTutors','recentBookings','popularMapel'
        ));
    }

    private function growthBooking(): int
    {
        $thisMonth = Booking::whereMonth('created_at', now()->month)->count();
        $lastMonth = Booking::whereMonth('created_at', now()->subMonth()->month)->count();
        if ($lastMonth === 0) return 100;
        return (int) round((($thisMonth - $lastMonth) / $lastMonth) * 100);
    }
}
