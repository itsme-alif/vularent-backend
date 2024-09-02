<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class items extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name',
        'item_description',
        'category_id',
        'owner_id',
        'price_per_day',
        'status_id'
    ];
}
