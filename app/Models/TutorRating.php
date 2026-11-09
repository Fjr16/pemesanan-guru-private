<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutorRating extends Model
{
    protected $fillable = [
        'order_id',
        'tutor_id',
        'student_id',
        'score',
        'ulasan',
        'expired_at',
        'status',
        'is_appear'
    ];

    protected $casts = [
        'expired_at' => 'datetime'
    ];

    public function order() {
        return $this->belongsTo(Order::class);
    }
    public function tutor() {
        return $this->belongsTo(Tutor::class);
    }
    public function student() {
        return $this->belongsTo(Student::class);
    }
}
