<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CacheDistance;
use App\Models\Tutor;
use App\Models\TutorSchedule;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TutorSearchController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $query = Tutor::where('status', 'active')
            ->with(['user', 'tutorSubjects.subjectCategory'])
            ->withCount('orders as session_count');

        if ($request->filled('mata_pelajaran_id')) {
            $mapelId = (int) $request->mata_pelajaran_id;
            $query->whereHas('tutorSubjects', fn ($q) => $q->where('subject_category_id', $mapelId));
        }

        $student = auth()->user()->student ?? null;
        $nearest = $request->input('lokasi_filter') === 'terdekat';
        if ($nearest && $student) {
            $lat = $student->latitude_dom ?? null;
            $lng = $student->langitude_dom ?? null;

            if ($lat === null || $lng === null) {
                return response()->json(['data' => [], 'total' => 0, 'page' => 0, 'has_more' => false]);
            }

            $lat = (float) $lat;
            $lng = (float) $lng;
            $query->withinRadius($lat, $lng, 5000);
        }

        switch ($request->input('sort')) {
            case 'rating':
                $query->orderByDesc('session_count');
                break;
            case 'price_asc':
                $query->orderBy('hourly_rate');
                break;
            case 'price_desc':
                $query->orderByDesc('hourly_rate');
                break;
            case 'populer' : 
                $query->orderByDesc('session_count');
                break;
            default:
                ($nearest && $student) ? $query->orderBy('distance') : null;
        }

        $perPage = 6;
        $page = max(1, (int) $request->input('page', 1));
        $total = $query->count();
        $tutors = $query->skip(($page - 1) * $perPage)->take($perPage)->get();

        $routes = ($nearest && $student) ? $this->routeDistances($tutors, $student, $lat, $lng) : [];

        $data = $tutors->map(function ($t) use ($routes, $nearest) {
            $row = [
                'id' => $t->id,
                'name' => $t->name,
                'bio' => $t->desc ?? 'Tutor berpengalaman siap membantu belajarmu.',
                'hourly_rate' => (float) $t->hourly_rate,
                'rating' => 0,
                'rating_count' => 0,
                'session_count' => $t->session_count,
                'subjects' => $t->tutorSubjects->map(fn ($ts) => $ts->subjectCategory->name ?? '-')->toArray(),
            ];
            if ($nearest) {
                $row['route_m']   = $routes[$t->id]['m'] ?? round((float) $t->distance, 2);
                $row['route_sec'] = $routes[$t->id]['s'] ?? null;
            }

            return $row;
        })->all();

        return response()->json([
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'has_more' => ($page * $perPage) < $total,
        ]);
    }

    public function jadwal(Request $request, int $tutorId): JsonResponse
    {
        $request->validate([
            'tanggal' => ['required', 'date'],
        ]);

        $tanggal = Carbon::parse($request->tanggal);
        $dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $dayName = $dayNames[$tanggal->dayOfWeek];

        $schedules = TutorSchedule::where('tutor_id', $tutorId)
            ->where('day', $dayName)
            ->with(['scheduleLocks' => function ($q) use ($tanggal) {
                $q->where('tanggal', $tanggal->toDateString())
                    ->whereIn('status', ['locked', 'confirmed']);
            }])
            ->orderBy('jam_start')
            ->get()
            ->map(function ($s) {
                $lock = $s->scheduleLocks->first();
                $isBooked = false;

                if ($lock) {
                    if ($lock->status === 'confirmed') {
                        $isBooked = true;
                    } elseif ($lock->status === 'locked' && $lock->expired_at && $lock->expired_at->isFuture()) {
                        $isBooked = true;
                    }
                }

                return [
                    'id' => $s->id,
                    'jam_mulai' => $s->jam_start->format('H:i'),
                    'jam_selesai' => $s->jam_end->format('H:i'),
                    'is_booked' => $isBooked,
                ];
            });

        return response()->json([
            'tanggal' => $tanggal->toDateString(),
            'hari' => $dayName,
            'data' => $schedules,
        ]);
    }

    public function availableDays(Request $request, int $tutorId): JsonResponse
    {
        $days = TutorSchedule::where('tutor_id', $tutorId)
            ->distinct()
            ->pluck('day');

        return response()->json(['days' => $days->values()->all()]);
    }

    private function routeDistances($tutors, $student, float $lat, float $lng): array
    {
        if ($tutors->isEmpty()) return [];

        $cached = CacheDistance::where('student_id', $student->id)
            ->whereIn('tutor_id', $tutors->pluck('id'))
            // ->where('computed_at', '>=', now()->subDays(7))
            ->get()->keyBy('tutor_id');

        $result = $cached->map(fn ($c) => ['m' => $c->distance_m, 's' => $c->duration_s])->all();
        $missing = $tutors->whereNotIn('id', $cached->keys())->values();

        if ($missing->isEmpty()) return $result;

        try {
            $locations = $missing
                ->map(fn ($t) => [(float) $t->langitude, (float) $t->latitude])
                ->prepend([$lng, $lat])->values()->all();

            $res = Http::timeout(30)
                ->withHeaders(['Authorization' => config('services.open_route_service.key')])
                ->post('https://api.heigit.org/openrouteservice/v2/matrix/driving-car', [
                    'locations'    => $locations,
                    'sources'      => ['0'],
                    'destinations' => array_map('strval', range(1, $missing->count())),
                    'metrics'      => ['distance', 'duration'],
                    'units'        => 'm',
                ])->throw()->json();

            $rows = [];
            foreach ($missing as $i => $t) {
                $m = $res['distances'][0][$i] ?? null;
                $s = $res['durations'][0][$i] ?? null;
                if ($m === null) continue;

                $result[$t->id] = ['m' => round($m, 2), 's' => $s];
                $rows[] = [
                    'student_id'  => $student->id,
                    'tutor_id'    => $t->id,
                    'distance_m'  => round($m, 2),
                    'duration_s'  => $s,
                    'computed_at' => now(),
                ];
            }

            if ($rows) {
                CacheDistance::upsert($rows, ['student_id', 'tutor_id'], ['distance_m', 'duration_s', 'computed_at']);
            }
        } catch (\Throwable $e) {
            report($e); // fallback ke jarak garis lurus (distance)
        }

        return $result;
    }
}
