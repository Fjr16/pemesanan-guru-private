<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ScheduleLock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemesananController extends Controller
{
    public function index(Request $request)
    {
        $tutor = Auth::user()->tutor;

        if (! $tutor) {
            return view('pages.tutor.pemesanan', [
                'orders' => collect()->paginate(10),
                'counts' => ['pending' => 0, 'confirmed' => 0, 'completed' => 0, 'canceled' => 0, 'rejected' => 0, 'expired' => 0],
            ]);
        }

        $baseQuery = Order::where('tutor_id', $tutor->id)
            ->with(['student.user', 'orderDetails'])
            ->latest();

        $counts = [
            'pending'   => (clone $baseQuery)->whereEffectiveStatus('pending')->count(),
            'confirmed' => (clone $baseQuery)->whereEffectiveStatus('confirmed')->count(),
            'completed' => (clone $baseQuery)->whereEffectiveStatus('complete')->count(),
            'canceled'  => (clone $baseQuery)->whereEffectiveStatus('canceled')->count(),
            'rejected'  => (clone $baseQuery)->whereEffectiveStatus('rejected')->count(),
            'expired'   => (clone $baseQuery)->whereEffectiveStatus('expired')->count(),
        ];

        $status = $request->input('status', '');

        if ($status) {
            $baseQuery->whereEffectiveStatus($status);
        }

        $orders = $baseQuery->paginate(10)->withQueryString();

        return view('pages.tutor.pemesanan', compact('orders', 'counts'));
    }

    public function show($id)
    {
        $tutor = Auth::user()->tutor;

        abort_unless($tutor, 404);

        $order = Order::where('tutor_id', $tutor->id)
            ->where('id', $id)
            ->with(['student.user', 'orderDetails.tutorSchedule'])
            ->firstOrFail();

        return view('pages.tutor.pemesanan-detail', compact('order'));
    }

    public function terima(Request $request, $id)
    {
        $tutor = Auth::user()->tutor;

        abort_unless($tutor, 404);

        $order = Order::where('tutor_id', $tutor->id)
            ->where('id', $id)
            ->where('status', 'pending')
            ->firstOrFail();

        $order->update([
            'status' => 'confirmed',
            'expired_at' => now()->addHours(24),
        ]);

        ScheduleLock::whereIn('order_detail_id', $order->orderDetails->pluck('id'))
            ->where('status', 'locked')
            ->update(['expired_at' => now()->addHours(24)]);

        return redirect()->route('tutor.pemesanan.show', $id)->with('success', 'Pesanan berhasil diterima. Menunggu pembayaran siswa.');
    }

    public function tolak(Request $request, $id)
    {
        $tutor = Auth::user()->tutor;

        abort_unless($tutor, 404);

        $order = Order::where('tutor_id', $tutor->id)
            ->where('id', $id)
            ->where('status', 'pending')
            ->firstOrFail();

        $order->update(['status' => 'rejected']);

        ScheduleLock::whereIn('order_detail_id', $order->orderDetails->pluck('id'))
            ->where('status', 'locked')
            ->update(['status' => 'release']);

        return redirect()->route('tutor.pemesanan')->with('success', 'Pesanan ditolak.');
    }

    public function selesai(Request $request, $id)
    {
        $tutor = Auth::user()->tutor;

        abort_unless($tutor, 404);

        $order = Order::where('tutor_id', $tutor->id)
            ->where('id', $id)
            ->where('status', 'confirmed')
            ->firstOrFail();

        $order->update(['expired_at' => null]);

        ScheduleLock::whereIn('order_detail_id', $order->orderDetails->pluck('id'))
            ->where('status', 'locked')
            ->update(['status' => 'confirmed', 'expired_at' => null]);

        $order->update(['status' => 'complete']);

        return redirect()->route('tutor.pemesanan.show', $id)->with('success', 'Sesi ditandai selesai.');
    }
}
