<?php

namespace App\Http\Controllers;

use App\Models\Tutor;

class TutorController extends Controller
{
    public function show(int $id)
    {
        $tutor = Tutor::where('status', 'active')
            ->with([
                'user',
                'tutorSubjects.subjectCategory',
                'studiedHistories',
                'tutorProfiles',
                'tutorSchedules' => function ($q) {
                    $q->orderByRaw("FIELD(day, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')")
                        ->orderBy('jam_start');
                },
            ])
            ->withCount(['orders as session_count' => function ($q) {
                $q->where('status', 'complete');
            }])
            ->findOrFail($id);

        $totalExperience = $tutor->tutorProfiles->sum('experience_years');

        $schedulesByDay = $tutor->tutorSchedules->groupBy('day')->map(function ($slots) {
            return $slots->map(function ($s) {
                return (object) [
                    'id' => $s->id,
                    'jam_start' => $s->jam_start->format('H:i'),
                    'jam_end' => $s->jam_end->format('H:i'),
                ];
            });
        });

        return view('show', compact(
            'tutor',
            'totalExperience',
            'schedulesByDay',
        ));
    }
}
