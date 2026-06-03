<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['tutor_id', 'student_id', 'status', 'catatan', 'total_payment', 'expired_at'])]
class Order extends Model
{
    //
}
