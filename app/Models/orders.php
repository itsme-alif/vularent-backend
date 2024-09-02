<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orders extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'start_date',
        'end_date',
        'total_price',
        'status_id'
    ];
}
