<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\SubjectCategory;
use App\Models\Tutor;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $mataPelajaran = SubjectCategory::where('is_active', true)
            ->withCount('tutorSubjects as tutor_count')
            ->orderBy('name')
            ->get()
            ->map(fn($sc) => (object)[
                'id'          => $sc->id,
                'nama'        => $sc->name,
                'icon'        => $sc->foto,
                'tutor_count' => $sc->tutor_count,
            ]);

        $stats = [
            'total_tutor' => Tutor::where('status', 'active')->count(),
            'total_sesi'  => Order::where('status', 'complete')->count(),
            'kepuasan'    => 98,
        ];

        $tutors = Tutor::where('status', 'active')
            ->with(['user', 'tutorSubjects.subjectCategory'])
            ->withCount('orders')
            ->orderByDesc('orders_count')
            ->limit(6)
            ->get()
            ->map(fn($t) => (object)[
                'id'              => $t->id,
                'user'            => (object)['name' => $t->user->username ?? $t->name],
                'hourly_rate'     => $t->hourly_rate,
                'avg_rating'      => 0,
                'review_count'    => 0,
                'session_count'   => $t->orders_count,
                'experience_years'=> 0,
                'bio'             => $t->desc ?? 'Tutor berpengalaman siap membantu belajarmu.',
                'mataPelajaran'   => $t->tutorSubjects->map(fn($ts) => (object)['nama' => $ts->subjectCategory->name ?? '-']),
            ]);

        return view('home', compact('mataPelajaran', 'stats', 'tutors'));
    }
}
