<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use App\Models\TutorProfile;
use App\Models\Booking;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Tampilkan halaman homepage.
     * Mata pelajaran & stats di-render server-side.
     * Daftar tutor di-load via AJAX (TutorSearchController).
     */
    public function index()
    {
        // Mata pelajaran untuk dropdown search & grid
        // $mataPelajaran = MataPelajaran::withCount('tutors')
        //     ->orderBy('nama')
        //     ->get();
        $mataPelajaran = collect();

        // Stats ringkas
        $stats = [
            // 'total_tutor' => TutorProfile::where('status', 'active')->count(),
            // 'total_sesi'  => Booking::where('status', 'completed')->count(),
            'total_tutor' => 90,
            'total_sesi'  => 100,
            'kepuasan'    => 98, // bisa diganti dengan kalkulasi rating rata-rata
        ];

        // Server-side tutor listing (untuk first paint / SEO)
        // AJAX akan override ini saat user berinteraksi
        // $tutors = TutorProfile::with(['user', 'mataPelajaran'])
        //     ->where('status', 'active')
        //     ->withAvg('reviews', 'rating')
        //     ->withCount('reviews as review_count')
        //     ->withCount('bookings as session_count')
        //     ->orderByDesc('reviews_avg_rating')
        //     ->paginate(6);
        $tutors = collect();

        return view('home', compact('mataPelajaran', 'stats', 'tutors'));
    }
}
