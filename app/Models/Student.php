<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['user_id', 'name', 'tempat_lhr', 'tanggal_lhr', 'alamat'])]
class Student extends Model
{
    use SoftDeletes;
}
