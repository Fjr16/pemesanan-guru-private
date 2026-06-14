<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user         = Auth::user();
        $tutorProfile = $user->tutorProfile;

        abort_if(!$tutorProfile, 404, 'Profil tutor tidak ditemukan.');

        $tutorId = $tutorProfile->id;

        // Stat cards
        $stats = [
            'pending'       => Booking::where('tutor_profile_id', $tutorId)->where('status', 'pending')->count(),
            'confirmed'     => Booking::where('tutor_profile_id', $tutorId)->where('status', 'confirmed')->count(),
            'completed'     => Booking::where('tutor_profile_id', $tutorId)->where('status', 'completed')->count(),
            'avg_rating'    => Review::where('tutor_profile_id', $tutorId)->avg('rating') ?? 0,
            'total_reviews' => Review::where('tutor_profile_id', $tutorId)->count(),
            'pendapatan'    => Booking::where('tutor_profile_id', $tutorId)
                ->where('status', 'completed')
                ->where('payment_status', 'paid')
                ->whereMonth('updated_at', now()->month)
                ->whereYear('updated_at', now()->year)
                ->sum('total_price'),
        ];

        $pendingCount = $stats['pending'];

        // Request booking pending (maks 5)
        $pendingBookings = Booking::with(['user', 'mataPelajaran'])
            ->where('tutor_profile_id', $tutorId)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Sesi hari ini
        $hariIni = now()->locale('id')->translatedFormat('l'); // cth: Senin
        $todaySessions = Booking::with(['user', 'mataPelajaran'])
            ->where('tutor_profile_id', $tutorId)
            ->where('status', 'confirmed')
            ->where('scheduled_day', $hariIni)
            ->orderBy('scheduled_time')
            ->get();

        return view('tutor.dashboard', compact(
            'stats', 'pendingCount', 'pendingBookings', 'todaySessions'
        ));
    }
}
