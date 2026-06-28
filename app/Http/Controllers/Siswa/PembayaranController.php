<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;

class PembayaranController extends Controller
{
    public function show(Order $order, MidtransService $midtrans)
    {
        $student = auth()->user()->student;

        abort_unless($student && $order->student_id === $student->id, 403);
        abort_unless(in_array($order->effective_status, ['confirmed', 'pending']), 403);

        $payment = $order->payments()
            ->where('status', 'pending')
            ->first();

        if (! $payment) {
            $result = $midtrans->createTransaction($order);

            $payment = Payment::create([
                'order_id' => $order->id,
                'transactionId' => $result['order_id'],
                'payment_token' => $result['snap_token'],
                'metode' => null,
                'amount' => $order->total_payment,
                'status' => 'pending',
                'expired_at' => now()->addHours(24),
            ]);
        }

        return view('pages.siswa.pembayaran', compact('order', 'payment'));
    }

    public function process(Order $order): JsonResponse
    {
        $student = auth()->user()->student;

        abort_unless($student && $order->student_id === $student->id, 403);

        $payment = $order->payments()
            ->where('status', 'pending')
            ->firstOrFail();

        return response()->json([
            'snap_token' => $payment->payment_token,
        ]);
    }

    public function success(Order $order)
    {
        $student = auth()->user()->student;

        abort_unless($student && $order->student_id === $student->id, 403);

        $payment = $order->payments()
            ->whereIn('status', ['paid', 'pending'])
            ->latest()
            ->first();

        return view('pages.siswa.pembayaran-sukses', compact('order', 'payment'));
    }
}
