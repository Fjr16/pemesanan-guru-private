<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Stat cards
        $stats = [
            'total_booking' => Booking::where('user_id', $user->id)->count(),
            'pending'       => Booking::where('user_id', $user->id)->where('status', 'pending')->count(),
            'completed'     => Booking::where('user_id', $user->id)->where('status', 'completed')->count(),
            'cancelled'     => Booking::where('user_id', $user->id)->where('status', 'cancelled')->count(),
        ];

        $pendingCount = $stats['pending'];

        // Sesi mendatang (pending + confirmed)
        $upcomingSessions = Booking::with(['tutorProfile.user', 'mataPelajaran'])
            ->where('user_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Tutor sering dipesan
        $favoriteTutors = Booking::where('user_id', $user->id)
            ->select(
                'tutor_profile_id',
                DB::raw('COUNT(*) as session_count'),
                DB::raw('MAX(users.name) as tutor_name')
            )
            ->join('tutor_profiles', 'bookings.tutor_profile_id', '=', 'tutor_profiles.id')
            ->join('users', 'tutor_profiles.user_id', '=', 'users.id')
            ->groupBy('tutor_profile_id')
            ->orderByDesc('session_count')
            ->take(4)
            ->get()
            ->map(function ($t) {
                // Ambil nama mata pelajaran tutor tersebut
                $subjects = \App\Models\TutorProfile::find($t->tutor_profile_id)
                    ?->mataPelajaran->pluck('nama')->join(', ');
                $t->subjects = $subjects;
                return $t;
            });

        return view('siswa.dashboard', compact(
            'stats', 'pendingCount', 'upcomingSessions', 'favoriteTutors'
        ));
    }
}
