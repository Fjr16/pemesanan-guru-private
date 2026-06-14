<?php

namespace App\Http\Controllers;

use App\Models\TutorProfile;
use App\Models\Review;
use Illuminate\Http\Request;

class TutorController extends Controller
{
    /** GET /tutor/{tutor} — halaman profil publik */
    public function show(TutorProfile $tutor)
    {
        abort_if($tutor->status !== 'active', 404, 'Tutor tidak ditemukan atau belum aktif.');

        $tutor->load(['user', 'mataPelajaran']);
        $tutor->loadCount(['reviews', 'bookings as session_count' => fn($q) => $q->where('status','completed')]);
        $tutor->loadAvg('reviews', 'rating');

        $reviews = Review::with('siswa')
            ->where('tutor_profile_id', $tutor->id)
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return view('tutor.show', compact('tutor', 'reviews'));
    }

    /** GET /tutor/{tutor}/booking — alias untuk halaman jadwal */
    public function booking(TutorProfile $tutor)
    {
        return $this->show($tutor);
    }
}
