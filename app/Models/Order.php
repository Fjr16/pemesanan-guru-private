<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['tutor_id', 'student_id', 'status', 'catatan', 'total_payment', 'expired_at'])]
class Order extends Model
{
    protected function casts(): array
    {
        return [
            'total_payment' => 'decimal:2',
            'expired_at' => 'datetime',
        ];
    }

    public function tutor(){
        return $this->belongsTo(Tutor::class);
    }
    public function student(){
        return $this->belongsTo(Student::class);
    }
    public function orderDetails(){
        return $this->hasMany(OrderDetail::class);
    }
    public function payments(){
        return $this->hasMany(Payment::class);
    }
}
