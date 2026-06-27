<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            return view('pages.tutor.dashboard', [
                'stats'          => ['pending' => 0, 'confirmed' => 0, 'completed' => 0, 'avg_rating' => 0, 'total_reviews' => 0, 'pendapatan' => 0],
                'pendingCount'   => 0,
                'pendingBookings'=> collect(),
                'todaySessions'  => collect(),
            ]);
        }

        $startOfMonth = Carbon::now()->copy()->startOfMonth();

        $stats = [
            'pending'       => Order::where('tutor_id', $tutor->id)->whereEffectiveStatus('pending')->count(),
            'confirmed'     => Order::where('tutor_id', $tutor->id)->whereEffectiveStatus('confirmed')->count(),
            'completed'     => Order::where('tutor_id', $tutor->id)->whereEffectiveStatus('complete')->count(),
            'avg_rating'    => 0,
            'total_reviews' => 0,
            'pendapatan'    => Payment::where('status', 'paid')
                ->whereHas('order', fn($q) => $q->where('tutor_id', $tutor->id))
                ->whereBetween('created_at', [$startOfMonth, Carbon::now()])
                ->sum('amount'),
        ];

        $pendingCount = $stats['pending'];

        $pendingBookings = Order::where('tutor_id', $tutor->id)
            ->whereEffectiveStatus('pending')
            ->with(['student.user', 'orderDetails'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($o) {
                $detail = $o->orderDetails->first();
                return (object)[
                    'id'     => $o->id,
                    'siswa'  => $o->student->user->username ?? '-',
                    'mapel'  => '-',
                    'hari'   => $detail ? Carbon::parse($detail->tanggal)->translatedFormat('l') : '-',
                    'jam'    => $detail ? $detail->jam_start : '-',
                    'durasi' => $detail ? round((Carbon::parse($detail->jam_end)->diffInMinutes(Carbon::parse($detail->jam_start))) / 60, 1) : 0,
                    'catatan'=> $o->catatan ?? '',
                    'waktu'  => $o->created_at->diffForHumans(),
                ];
            });

        $todaySessions = Order::where('tutor_id', $tutor->id)
            ->whereEffectiveStatus('confirmed')
            ->whereHas('orderDetails', fn($q) => $q->where('tanggal', Carbon::today()->toDateString()))
            ->with(['student.user', 'orderDetails'])
            ->get()
            ->flatMap(fn($o) => $o->orderDetails
                ->where('tanggal', Carbon::today()->toDateString())
                ->map(fn($d) => (object)[
                    'siswa' => $o->student->user->username ?? '-',
                    'jam'   => $d->jam_start,
                    'mapel' => '-',
                ])
            );

        return view('pages.tutor.dashboard', compact('stats', 'pendingCount', 'pendingBookings', 'todaySessions'));
    }
}
