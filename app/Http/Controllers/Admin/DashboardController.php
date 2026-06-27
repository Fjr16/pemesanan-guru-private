<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\SubjectCategory;
use App\Models\Tutor;
use App\Models\User;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        $bookingThisMonth = Order::whereBetween('created_at', [$startOfMonth, $now])->count();
        $bookingLastMonth = Order::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
        $growth = $bookingLastMonth > 0
            ? round(($bookingThisMonth - $bookingLastMonth) / $bookingLastMonth * 100)
            : 0;

        $stats = [
            'total_siswa'       => User::where('role', 'siswa')->count(),
            'total_tutor_aktif' => Tutor::where('status', 'active')->count(),
            'tutor_pending'     => Tutor::where('status', 'pending')->count(),
            'total_booking'     => Order::count(),
            'booking_bulan_ini' => $bookingThisMonth,
            'sesi_selesai'      => Order::whereEffectiveStatus('complete')
                ->whereBetween('created_at', [$startOfMonth, $now])
                ->count(),
            'total_transaksi'   => Payment::where('status', 'paid')->sum('amount'),
            'growth_booking'    => max($growth, 0),
        ];

        $pendingTutors = Tutor::where('status', 'pending')
            ->with(['user', 'tutorSubjects.subjectCategory'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn($t) => (object)[
                'id'            => $t->id,
                'user'          => (object)['name' => $t->user->username ?? $t->name, 'email' => $t->user->email ?? '-'],
                'created_at'    => $t->created_at,
                'mataPelajaran' => $t->tutorSubjects->map(fn($ts) => (object)['nama' => $ts->subjectCategory->name ?? '-']),
            ]);

        $recentBookings = Order::with(['student.user', 'tutor.tutorSubjects.subjectCategory'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn($o) => (object)[
                'id'           => $o->id,
                'user'         => (object)['name' => $o->student->user->username ?? '-'],
                'mataPelajaran'=> (object)['nama' => $o->tutor->tutorSubjects->first()->subjectCategory->name ?? '-'],
                'status'       => $o->effective_status,
                'created_at'   => $o->created_at,
            ]);

        $popularMapel = SubjectCategory::where('is_active', true)
            ->withCount('tutorSubjects')
            ->orderByDesc('tutor_subjects_count')
            ->limit(5)
            ->get()
            ->map(fn($sc) => (object)[
                'nama'          => $sc->name,
                'bookings_count' => $sc->tutor_subjects_count,
            ]);

        return view('pages.admin.dashboard', compact(
            'stats', 'pendingTutors', 'recentBookings', 'popularMapel'
        ));
    }
}
