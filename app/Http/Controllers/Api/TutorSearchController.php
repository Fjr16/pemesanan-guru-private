<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TutorProfile;
use App\Models\TutorSchedule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TutorSearchController extends Controller
{
    /**
     * GET /api/tutor/search
     * Params: mata_pelajaran_id, hari, sort, page
     */
    public function search(Request $request): JsonResponse
    {
        $query = TutorProfile::with(['user', 'mataPelajaran'])
            ->where('status', 'active')
            ->withAvg('reviews', 'rating')
            ->withCount(['reviews as review_count', 'bookings as session_count']);

        // Filter: mata pelajaran
        if ($request->filled('mata_pelajaran_id')) {
            $query->whereHas('mataPelajaran', function ($q) use ($request) {
                $q->where('mata_pelajarans.id', $request->mata_pelajaran_id);
            });
        }

        // Filter: hari ketersediaan
        if ($request->filled('hari')) {
            $query->whereHas('schedules', function ($q) use ($request) {
                $q->where('hari', $request->hari)->where('is_available', true);
            });
        }

        // Sorting
        switch ($request->input('sort', 'popular')) {
            case 'rating':
                $query->orderByDesc('reviews_avg_rating');
                break;
            case 'price_asc':
                $query->orderBy('hourly_rate');
                break;
            case 'price_desc':
                $query->orderByDesc('hourly_rate');
                break;
            default: // popular
                $query->orderByDesc('session_count');
                break;
        }

        $perPage = 6;
        $page    = max(1, (int) $request->input('page', 1));
        $total   = $query->count();
        $tutors  = $query->skip(($page - 1) * $perPage)->take($perPage)->get();

        $data = $tutors->map(function ($t) {
            return [
                'id'           => $t->id,
                'name'         => $t->user->name,
                'bio'          => $t->bio,
                'hourly_rate'  => $t->hourly_rate,
                'rating'       => round($t->reviews_avg_rating ?? 0, 1),
                'rating_count' => $t->review_count ?? 0,
                'session_count'=> $t->session_count ?? 0,
                'subjects'     => $t->mataPelajaran->pluck('nama')->toArray(),
                'experience'   => $t->experience_years,
            ];
        });

        return response()->json([
            'data'     => $data,
            'total'    => $total,
            'page'     => $page,
            'has_more' => ($page * $perPage) < $total,
        ]);
    }

    /**
     * GET /api/tutor/{tutor}/jadwal
     * Mengembalikan slot jadwal tutor beserta status (free/booked)
     */
    public function jadwal(Request $request, int $tutorId): JsonResponse
    {
        // Semua jadwal aktif tutor
        $schedules = TutorSchedule::where('tutor_profile_id', $tutorId)
            ->where('is_available', true)
            ->get();

        // Slot yang sudah di-booking (confirmed/pending)
        $bookedSlots = \App\Models\Booking::where('tutor_profile_id', $tutorId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get(['scheduled_day', 'scheduled_time'])
            ->map(fn($b) => $b->scheduled_day . '_' . $b->scheduled_time)
            ->toArray();

        $result = $schedules->map(function ($s) use ($bookedSlots) {
            $key = $s->hari . '_' . $s->jam_mulai;
            return [
                'id'         => $s->id,
                'hari'       => $s->hari,
                'jam_mulai'  => $s->jam_mulai,
                'jam_selesai'=> $s->jam_selesai,
                'is_booked'  => in_array($key, $bookedSlots),
            ];
        });

        return response()->json(['data' => $result]);
    }
}
