<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'amount',
        'status_id',
        'payment_method_id'
    ];

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }

    public function status()
    {
        return $this->belongsTo(TransactionStatus::class, 'status_id');
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
}
