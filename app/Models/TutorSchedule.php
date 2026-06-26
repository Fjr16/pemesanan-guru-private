<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['tutor_id', 'day', 'jam_start', 'jam_end'])]
class TutorSchedule extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'jam_start' => 'datetime:H:i',
            'jam_end'   => 'datetime:H:i',
        ];
    }

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(Tutor::class);
    }

    public function scheduleLocks(): HasMany
    {
        return $this->hasMany(ScheduleLock::class);
    }

    public function activeLocks(): HasMany
    {
        return $this->hasMany(ScheduleLock::class)
            ->where('tanggal', '>=', now()->toDateString())
            ->where(function ($q) {
                $q->where('status', 'confirmed')
                  ->orWhere(function ($q2) {
                      $q2->where('status', 'locked')
                         ->where('expired_at', '>', now());
                  });
            });
    }
}
