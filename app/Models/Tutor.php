<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['user_id', 'name', 'jenis_kelamin', 'tanggal_lhr', 'foto', 'domisili', 'desc', 'job', 'hourly_rate', 'lokasi_mengajar'])]
class Tutor extends Model
{
    use SoftDeletes;

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function tutorSubjects(){
        return $this->hasMany(TutorSubject::class);
    }
    public function tutorProfiles(){
        return $this->hasMany(TutorProfile::class);
    }
    public function studiedHistories(){
        return $this->hasMany(StudiedHistory::class);
    }
    public function tutorSchedules(){
        return $this->hasMany(TutorSchedule::class);
    }
    public function orders(){
        return $this->hasMany(Order::class);
    }
}
