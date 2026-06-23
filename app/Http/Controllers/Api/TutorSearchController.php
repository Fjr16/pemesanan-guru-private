<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubjectCategory;
use App\Models\Tutor;
use App\Models\TutorSchedule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TutorSearchController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $query = Tutor::where('status', 'active')
            ->with(['user', 'tutorSubjects.subjectCategory'])
            ->withCount('orders as session_count');

        if ($request->filled('mata_pelajaran_id')) {
            $mapelId = (int) $request->mata_pelajaran_id;
            $query->whereHas('tutorSubjects', fn($q) => $q->where('subject_category_id', $mapelId));
        }

        switch ($request->input('sort', 'popular')) {
            case 'rating':
                $query->orderByDesc('session_count');
                break;
            case 'price_asc':
                $query->orderBy('hourly_rate');
                break;
            case 'price_desc':
                $query->orderByDesc('hourly_rate');
                break;
            default:
                $query->orderByDesc('session_count');
        }

        $perPage = 6;
        $page = max(1, (int) $request->input('page', 1));
        $total = $query->count();
        $tutors = $query->skip(($page - 1) * $perPage)->take($perPage)->get();

        $data = $tutors->map(fn($t) => [
            'id'            => $t->id,
            'name'          => $t->name,
            'bio'           => $t->desc ?? 'Tutor berpengalaman siap membantu belajarmu.',
            'hourly_rate'   => (float) $t->hourly_rate,
            'rating'        => 0,
            'rating_count'  => 0,
            'session_count' => $t->session_count,
            'subjects'      => $t->tutorSubjects->map(fn($ts) => $ts->subjectCategory->name ?? '-')->toArray(),
        ])->toArray();

        return response()->json([
            'data'     => $data,
            'total'    => $total,
            'page'     => $page,
            'has_more' => ($page * $perPage) < $total,
        ]);
    }

    public function jadwal(Request $request, int $tutorId): JsonResponse
    {
        $schedules = TutorSchedule::where('tutor_id', $tutorId)
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get(['id', 'hari', 'jam_mulai', 'jam_selesai'])
            ->map(fn($s) => [
                'id'          => $s->id,
                'hari'        => $s->hari,
                'jam_mulai'   => $s->jam_mulai,
                'jam_selesai' => $s->jam_selesai,
                'is_booked'   => false,
            ]);

        return response()->json(['data' => $schedules]);
    }
}
