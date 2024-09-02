<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transactions extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'amount',
        'status_id',
        'payment_method_id'
    ];
}
