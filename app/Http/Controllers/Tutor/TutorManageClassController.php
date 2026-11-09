<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\OrderConfirmedMail;
use App\Mail\OrderRejectedMail;
use App\Models\Order;
use App\Models\ScheduleLock;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TutorManageClassController extends Controller
{
   public function index()
    {
        $tutor = Auth::user()->tutor;

        if (! $tutor) {
            return view('pages.tutor.manage-class', [
                'orders' => [],
            ]);
        }

        $orders = Order::where('tutor_id', $tutor->id)
            ->whereIn('status', ['confirmed', 'complete'])
            ->whereHas('payments', function($item){
                return $item->where('status', 'paid');
            })
            ->with(['student.user', 'orderDetails'])
            ->latest()
            ->get();

        return view('pages.tutor.manage-class', compact('orders'));
    }

    public function show($id)
    {
        $tutor = Auth::user()->tutor;

        abort_unless($tutor, 404);

        $order = Order::where('tutor_id', $tutor->id)
            ->where('id', $id)
            ->with(['student.user', 'orderDetails.tutorSchedule'])
            ->firstOrFail();

        return view('pages.tutor.manage-class-show', compact('order'));
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

        Mail::to($order->student->user->email)->queue(new OrderConfirmedMail($order));

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

        Mail::to($order->student->user->email)->queue(new OrderRejectedMail($order));

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

        abort_unless($order->payments()->where('status', 'paid')->exists(), 403, 'Pembayaran belum terverifikasi.');

        $order->update(['expired_at' => null]);

        ScheduleLock::whereIn('order_detail_id', $order->orderDetails->pluck('id'))
            ->where('status', 'locked')
            ->update(['status' => 'confirmed', 'expired_at' => null]);

        $order->update(['status' => 'complete']);

        return redirect()->route('tutor.pemesanan.show', $id)->with('success', 'Sesi ditandai selesai.');
    }
}
