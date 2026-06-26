<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
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
        $first = $this->orderDetails->first();

        return $first ? Carbon::parse($first->tanggal)->translatedFormat('d M Y') : null;
    }

    public function getJamRangeAttribute(): ?string
    {
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
        return $this->orderDetails->count();
    }

    public function getDayNameAttribute(): ?string
    {
        $first = $this->orderDetails->first();

        return $first ? Carbon::parse($first->tanggal)->translatedFormat('l') : null;
    }
}
