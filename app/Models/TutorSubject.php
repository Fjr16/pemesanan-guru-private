<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['tutor_id', 'subject_category_id', 'deskripsi', 'tingkatan'])]
class TutorSubject extends Model
{
    public function subjectCategory(){
        return $this->belongsTo(SubjectCategory::class);
    }
    public function tutor(){
        return $this->belongsTo(Tutor::class);
    }
}
