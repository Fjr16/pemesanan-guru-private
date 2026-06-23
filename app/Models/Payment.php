<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['order_id', 'transactionId', 'payment_token', 'metode', 'amount', 'status', 'expired_at', 'paid_at'])]
class Payment extends Model
{
    public function order(){
        return $this->belongsTo(Order::class);
    }
}
