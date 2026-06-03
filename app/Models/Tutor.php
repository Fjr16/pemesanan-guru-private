<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['user_id', 'name', 'jenis_kelamin', 'tanggal_lhr', 'foto', 'domisili', 'desc', 'job', 'hourly_rate', 'lokasi_mengajar'])]
class Tutor extends Model
{
    use SoftDeletes;
}
