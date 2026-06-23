<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['user_id', 'name', 'tempat_lhr', 'tanggal_lhr', 'alamat'])]
class Student extends Model
{
    use SoftDeletes;

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function orders(){
        return $this->hasMany(Order::class);
    }
}
