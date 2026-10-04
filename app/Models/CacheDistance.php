<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['tutor_id', 'student_id', 'distance_m', 'duration_s', 'computed_at'])]
class CacheDistance extends Model
{
    protected function casts(): array
    {
        return [
            'distance_m' => 'float',
            'duration_s' => 'integer',
            'computed_at' => 'datetime',
        ];
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }
    public function student()
    {
        return $this->belongsTo(Student::class);
    }   
}
