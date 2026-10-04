<?php

namespace App\Models;

use App\Observers\StudentObserver;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['user_id', 'name', 'tempat_lhr', 'tanggal_lhr', 'alamat', 'latitude_dom', 'langitude_dom'])]
#[ObservedBy([StudentObserver::class])]
class Student extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'tanggal_lhr' => 'date',
            'latitude_dom' => 'float',
            'langitude_dom' => 'float',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function orders(){
        return $this->hasMany(Order::class);
    }
    public function cacheDistances()
    {
        return $this->hasMany(CacheDistance::class);
    }
}
