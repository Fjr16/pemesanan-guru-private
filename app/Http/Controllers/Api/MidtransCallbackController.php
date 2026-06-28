<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\ScheduleLock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request)
    {
        $body = $request->all();

        $serverKey = config('midtrans.server_key');

        $signature = hash('sha512',
            ($body['order_id'] ?? '')
            .($body['status_code'] ?? '')
            .($body['gross_amount'] ?? '')
            .$serverKey
        );

        if (($body['signature_key'] ?? '') !== $signature) {
            Log::warning('Midtrans callback: invalid signature', $body);

            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $payment = Payment::where('transactionId', $body['order_id'])->first();

        if (! $payment) {
            Log::warning('Midtrans callback: payment not found', $body);

            return response()->json(['message' => 'Payment not found'], 404);
        }

        $transactionStatus = $body['transaction_status'] ?? '';
        $paymentType = $body['payment_type'] ?? null;
        $fraudStatus = $body['fraud_status'] ?? null;

        DB::transaction(function () use ($payment, $transactionStatus, $paymentType, $fraudStatus, $body) {
            $payment->update(['raw_response' => $body]);

            if ($this->isPaymentSuccess($transactionStatus, $fraudStatus)) {
                $payment->update([
                    'status' => 'paid',
                    'metode' => $this->mapPaymentType($paymentType),
                    'paid_at' => now(),
                ]);

                ScheduleLock::whereIn(
                    'order_detail_id',
                    $payment->order->orderDetails->pluck('id')
                )->where('status', 'locked')->update([
                    'status' => 'confirmed',
                    'expired_at' => null,
                ]);

            } elseif ($transactionStatus === 'expire') {
                $payment->update(['status' => 'expired']);

                if ($payment->order->status === 'confirmed') {
                    $payment->order->update(['status' => 'expired']);
                }

                ScheduleLock::whereIn(
                    'order_detail_id',
                    $payment->order->orderDetails->pluck('id')
                )->where('status', 'locked')->update(['status' => 'release']);

            } elseif ($transactionStatus === 'cancel') {
                $payment->update(['status' => 'failed']);

            } elseif ($transactionStatus === 'deny') {
                $payment->update(['status' => 'failed']);
            }
        });

        return response()->json(['message' => 'OK']);
    }

    private function isPaymentSuccess(string $status, ?string $fraudStatus): bool
    {
        if ($status === 'settlement') {
            return true;
        }

        if ($status === 'capture' && $fraudStatus === 'accept') {
            return true;
        }

        return false;
    }

    private function mapPaymentType(?string $type): string
    {
        return match ($type) {
            'qris' => 'qris',
            'bank_transfer', 'bca_va', 'bni_va', 'bri_va', 'permata_va', 'echannel' => 'transfer',
            default => $type ?? 'unknown',
        };
    }
}
