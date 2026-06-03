<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['tutor_id', 'day', 'jam_start', 'jam_end'])]
class TutorSchedule extends Model
{
    use SoftDeletes;
}
