<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public function getEffectiveStatusAttribute(): string
    {
        if (in_array($this->status, ['pending', 'confirmed']) && $this->expired_at?->isPast()) {
            return 'expired';
        }

        return $this->status;
    }

    public function scopeWhereEffectiveStatus(Builder $query, string $status): Builder
    {
        if ($status === 'expired') {
            return $query->where(function ($q) {
                $q->where('status', 'expired')
                    ->orWhere(function ($q2) {
                        $q2->whereIn('status', ['pending', 'confirmed'])
                            ->where('expired_at', '<', now());
                    });
            });
        }

        if ($status === 'pending') {
            return $query->where('status', 'pending')
                ->where(function ($q) {
                    $q->whereNull('expired_at')
                        ->orWhere('expired_at', '>=', now());
                });
        }

        if ($status === 'confirmed') {
            return $query->where('status', 'confirmed')
                ->where(function ($q) {
                    $q->whereNull('expired_at')
                        ->orWhere('expired_at', '>=', now());
                });
        }

        return $query->where('status', $status);
    }

    protected $fillable = [
        'tutor_id',
        'student_id',
        'status',
        'catatan',
        'total_payment',
        'expired_at',
    ];

    protected function casts(): array
    {
        return [
            'total_payment' => 'decimal:2',
            'expired_at' => 'datetime',
        ];
    }

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(Tutor::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getTanggalAttribute(): ?string
    {
        if (! $this->relationLoaded('orderDetails')) {
            $this->load('orderDetails');
        }

        $first = $this->orderDetails->first();

        return $first ? Carbon::parse($first->tanggal)->translatedFormat('d F Y') : null;
    }

    public function getJamRangeAttribute(): ?string
    {
        if (! $this->relationLoaded('orderDetails')) {
            $this->load('orderDetails');
        }

        if ($this->orderDetails->isEmpty()) {
            return null;
        }
        $sorted = $this->orderDetails->sortBy('jam_start')->values();
        $first = $sorted->first()->jam_start;
        $last = $sorted->last()->jam_end;

        return $first.' – '.$last.' WIB';
    }

    public function getJumlahJamAttribute(): int
    {
        if (! $this->relationLoaded('orderDetails')) {
            $this->load('orderDetails');
        }

        return $this->orderDetails->count();
    }

    public function getDayNameAttribute(): ?string
    {
        if (! $this->relationLoaded('orderDetails')) {
            $this->load('orderDetails');
        }

        $first = $this->orderDetails->first();

        return $first ? Carbon::parse($first->tanggal)->translatedFormat('l') : null;
    }
}
