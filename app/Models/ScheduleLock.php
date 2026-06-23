<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['tutor_schedule_id', 'order_detail_id', 'tanggal', 'status', 'locked_at', 'expired_at'])]
class ScheduleLock extends Model
{
    public function orderDetail(){
        return $this->belongsTo(OrderDetail::class);
    }
    public function tutorSchedule(){
        return $this->belongsTo(TutorSchedule::class);
    }
}
