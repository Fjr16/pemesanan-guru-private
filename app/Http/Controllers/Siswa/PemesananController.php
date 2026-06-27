<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ScheduleLock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemesananController extends Controller
{
    public function index(Request $request)
    {
        $student = Auth::user()->student;

        if (! $student) {
            return view('pages.siswa.pemesanan', [
                'orders' => collect(),
                'counts' => ['all' => 0, 'pending' => 0, 'confirmed' => 0, 'complete' => 0, 'canceled' => 0, 'rejected' => 0, 'expired' => 0],
            ]);
        }

        $status = $request->input('status', 'all');

        $baseQuery = Order::where('student_id', $student->id)
            ->with(['tutor', 'orderDetails'])
            ->latest();

        $counts = [
            'all'       => (clone $baseQuery)->count(),
            'pending'   => (clone $baseQuery)->whereEffectiveStatus('pending')->count(),
            'confirmed' => (clone $baseQuery)->whereEffectiveStatus('confirmed')->count(),
            'complete'  => (clone $baseQuery)->whereEffectiveStatus('complete')->count(),
            'canceled'  => (clone $baseQuery)->whereEffectiveStatus('canceled')->count(),
            'rejected'  => (clone $baseQuery)->whereEffectiveStatus('rejected')->count(),
            'expired'   => (clone $baseQuery)->whereEffectiveStatus('expired')->count(),
        ];

        $orders = $status === 'all'
            ? $baseQuery->get()
            : $baseQuery->whereEffectiveStatus($status)->get();

        return view('pages.siswa.pemesanan', compact('orders', 'counts'));
    }

    public function show($id)
    {
        $student = Auth::user()->student;

        abort_unless($student, 404);

        $order = Order::where('student_id', $student->id)
            ->where('id', $id)
            ->with(['tutor', 'orderDetails.tutorSchedule'])
            ->firstOrFail();

        return view('pages.siswa.pemesanan-detail', compact('order'));
    }

    public function cancel(Request $request, $id)
    {
        $student = Auth::user()->student;

        abort_unless($student, 404);

        $order = Order::where('student_id', $student->id)
            ->where('id', $id)
            ->where('status', 'pending')
            ->firstOrFail();

        $order->update([
            'status' => 'canceled',
            'expired_at' => null,
        ]);

        ScheduleLock::whereIn('order_detail_id', $order->orderDetails->pluck('id'))
            ->where('status', 'locked')
            ->update(['status' => 'release', 'expired_at' => null]);

        return redirect()->route('siswa.pemesanan')->with('success', 'Pemesanan berhasil dibatalkan.');
    }
}
