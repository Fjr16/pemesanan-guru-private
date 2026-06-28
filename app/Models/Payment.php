<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['order_id', 'transactionId', 'payment_token', 'metode', 'amount', 'status', 'expired_at', 'paid_at', 'raw_response'])]
class Payment extends Model
{
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'expired_at' => 'datetime',
            'paid_at' => 'datetime',
            'raw_response' => 'array',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
