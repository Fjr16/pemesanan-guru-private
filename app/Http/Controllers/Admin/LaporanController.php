<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TutorProfile;
use App\Models\User;

class LaporanController extends Controller
{
    public function index()
    {
        $stats = [
            'total_booking'   => Booking::count(),
            'completed'       => Booking::where('status','completed')->count(),
            'confirmed'       => Booking::where('status','confirmed')->count(),
            'pending'         => Booking::where('status','pending')->count(),
            'cancelled'       => Booking::where('status','cancelled')->count(),
            'new_users'       => User::whereMonth('created_at', now()->month)->count(),
            'total_transaksi' => Booking::where('payment_status','paid')->sum('total_price'),
        ];

        $topTutors = TutorProfile::with('user')
            ->withCount('bookings')
            ->withAvg('reviews','rating')
            ->orderByDesc('bookings_count')
            ->take(5)
            ->get();

        return view('admin.laporan', compact('stats','topTutors'));
    }
}
