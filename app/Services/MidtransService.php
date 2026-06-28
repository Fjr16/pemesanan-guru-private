<?php

namespace App\Services;

use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        if (! config('midtrans.is_production')) {
            Config::$curlOptions = [
                CURLOPT_CAINFO => 'C:/xampp/php/extras/cacert.pem',
                CURLOPT_HTTPHEADER => [],
            ];
        }
    }

    public function createTransaction(Order $order): array
    {
        $params = [
            'transaction_details' => [
                'order_id' => 'TUTOR-'.$order->id.'-'.time(),
                'gross_amount' => (int) $order->total_payment,
            ],
            'item_details' => $order->orderDetails->map(fn ($d) => [
                'id' => 'SLOT-'.$d->id,
                'price' => (int) $d->harga,
                'quantity' => 1,
                'name' => 'Sesi '.$order->tutor->name.' - '.$d->tanggal,
            ])->toArray(),
            'customer_details' => [
                'first_name' => $order->student->name,
                'email' => $order->student->user->email,
                'phone' => $order->student->user->no_hp,
            ],
            'callbacks' => [
                'finish' => route('siswa.pembayaran.sukses', $order->id),
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return [
            'snap_token' => $snapToken,
            'order_id' => $params['transaction_details']['order_id'],
        ];
    }

    public function getTransactionStatus(string $orderId): array
    {
        return (array) Transaction::status($orderId);
    }
}
