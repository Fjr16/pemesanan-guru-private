<?php

namespace App\Models;

use App\Observers\TutorObserver;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

#[Fillable(['user_id', 'name', 'jenis_kelamin', 'tanggal_lhr', 'foto', 'domisili', 'desc', 'job', 'hourly_rate', 'lokasi_mengajar', 'latitude', 'langitude'])]
#[ObservedBy([TutorObserver::class])]
class Tutor extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'tanggal_lhr' => 'date',
            'hourly_rate' => 'decimal:2',
            // 'latitude' => 'decimal:10,7',
            // 'langitude' => 'decimal:10,7',
            'latitude' => 'float',
            'langitude' => 'float',
        ];
    }

    public function user()
    {
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
    public function cacheDistances()
    {
        return $this->hasMany(CacheDistance::class);
    }
    public function scopeWithinRadius(Builder $query, float $lat, float $lng, float $radiusM): Builder
    {
        // Bounding box
        $latDelta = $radiusM / 111045;
        $lngDelta = $radiusM / (111045 * cos(deg2rad($lat)));

        $distance = '(6371000 * ACOS(LEAST(1, COS(RADIANS(?)) * COS(RADIANS(latitude)) * COS(RADIANS(langitude) - RADIANS(?)) + SIN(RADIANS(?)) * SIN(RADIANS(latitude)))))';
        if (empty($query->getQuery()->columns)) {
            $query->select('tutors.*');
        }
        return $query
            ->selectRaw(
                "$distance AS distance", [$lat, $lng, $lat]
            )
            ->whereBetween('latitude', [$lat - $latDelta, $lat + $latDelta])
            ->whereBetween('langitude', [$lng - $lngDelta, $lng + $lngDelta])
            ->whereRaw("$distance <= ?", [$lat, $lng, $lat, $radiusM]);
    }
}
