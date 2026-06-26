<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleLock extends Model
{
    protected $fillable = [
        'tutor_schedule_id',
        'order_detail_id',
        'tanggal',
        'status',
        'locked_at',
        'expired_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'locked_at' => 'datetime',
            'expired_at' => 'datetime',
        ];
    }

    public function orderDetail(): BelongsTo
    {
        return $this->belongsTo(OrderDetail::class);
    }

    public function tutorSchedule(): BelongsTo
    {
        return $this->belongsTo(TutorSchedule::class);
    }

    public function isActive(): bool
    {
        if ($this->tanggal < now()->toDateString()) {
            return false;
        }

        if ($this->status === 'confirmed') {
            return true;
        }

        if ($this->status === 'locked' && $this->expired_at && $this->expired_at->isFuture()) {
            return true;
        }

        return false;
    }
}
