<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['tutor_id', 'jenjang', 'sekolah', 'jurusan', 'periode'])]
class StudiedHistory extends Model
{
    public function tutor(){
        return $this->belongsTo(Tutor::class);
    }
}
