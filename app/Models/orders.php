<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
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

    // Relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship to the OrderStatus model
    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'status_id');
    }

    // Relationship to the Item Model
    public function item()
    {
        return $this->belongsTo(Items::class, 'item_id');
    }
}
