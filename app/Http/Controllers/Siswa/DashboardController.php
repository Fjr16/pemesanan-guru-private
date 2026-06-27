<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return view('pages.siswa.dashboard', [
                'stats'            => ['total_booking' => 0, 'pending' => 0, 'completed' => 0, 'cancelled' => 0],
                'pendingCount'     => 0,
                'upcomingSessions' => collect(),
                'favoriteTutors'   => collect(),
            ]);
        }

        $stats = [
            'total_booking' => Order::where('student_id', $student->id)->count(),
            'pending'       => Order::where('student_id', $student->id)->whereEffectiveStatus('pending')->count(),
            'completed'     => Order::where('student_id', $student->id)->whereEffectiveStatus('complete')->count(),
            'cancelled'     => Order::where('student_id', $student->id)->whereEffectiveStatus('canceled')->count(),
        ];

        $pendingCount = $stats['pending'];

        $upcomingSessions = Order::where('student_id', $student->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($q) {
                $q->where('status', 'confirmed')
                    ->orWhere(function ($q2) {
                        $q2->where('status', 'pending')
                            ->where(function ($q3) {
                                $q3->whereNull('expired_at')
                                    ->orWhere('expired_at', '>=', now());
                            });
                    });
            })
            ->with(['tutor.user', 'tutor.tutorSubjects.subjectCategory', 'orderDetails'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($o) {
                $detail = $o->orderDetails->first();
                return [
                    'tutor_name' => $o->tutor->name ?? '-',
                    'mapel'      => $o->tutor->tutorSubjects->first()->subjectCategory->name ?? '-',
                    'hari'       => $detail ? Carbon::parse($detail->tanggal)->translatedFormat('l') : '-',
                    'jam'        => $detail ? $detail->jam_start : '-',
                    'status'     => $o->effective_status,
                ];
            })
            ->toArray();

        $favoriteTutors = Order::where('student_id', $student->id)
            ->selectRaw('tutor_id, COUNT(*) as total_sesi')
            ->groupBy('tutor_id')
            ->orderByDesc('total_sesi')
            ->limit(3)
            ->get()
            ->map(function ($row) {
                $tutor = \App\Models\Tutor::with('tutorSubjects.subjectCategory')->find($row->tutor_id);
                return [
                    'name'       => $tutor->name ?? '-',
                    'mapel'      => $tutor->tutorSubjects->first()->subjectCategory->name ?? '-',
                    'total_sesi' => $row->total_sesi,
                ];
            })
            ->toArray();

        return view('pages.siswa.dashboard', compact(
            'stats', 'pendingCount', 'upcomingSessions', 'favoriteTutors'
        ));
    }
}
