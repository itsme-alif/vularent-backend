<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'status_name'
    ];

    public function items()
    {
        return $this->hasOne(Items::class, 'status_id');
    }
}
