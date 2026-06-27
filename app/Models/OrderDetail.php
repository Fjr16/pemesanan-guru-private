<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['order_id', 'tutor_schedule_id', 'tanggal', 'jam_start', 'jam_end', 'harga'])]
class OrderDetail extends Model
{
    public function order(){
        return $this->belongsTo(Order::class);
    }
    public function tutorSchedule(){
        return $this->belongsTo(TutorSchedule::class);
    }
    public function scheduleLock(){
        return $this->hasOne(ScheduleLock::class)
        ->where('status', 'locked')
        ->orWhere('status', 'confirmed');
    }
}
