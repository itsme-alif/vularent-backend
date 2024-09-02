<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'status_name'
    ];


    public function transaction()
    {
        return $this->hasOne(Transactions::class, 'status_id');
    }
}
