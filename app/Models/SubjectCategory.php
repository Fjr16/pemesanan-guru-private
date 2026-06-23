<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

#[Fillable(['name', 'deskripsi', 'foto', 'is_active'])]
class SubjectCategory extends Model
{
    public function tutorSubjects(): HasMany
    {
        return $this->hasMany(TutorSubject::class);
    }

    public function tutors(): HasManyThrough
    {
        return $this->hasManyThrough(Tutor::class, TutorSubject::class, 'subject_category_id', 'id', 'id', 'tutor_id');
    }
}
