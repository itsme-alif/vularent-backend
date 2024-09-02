<?php

namespace App\Models;

use App\Models\ItemStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Items extends Model
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

    protected $appends = ['owner', 'status_name'];

    // Relationship to the User model
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // Relationship to the ItemStatus model
    public function status()
    {
        return $this->belongsTo(ItemStatus::class, 'status_id');
    }

    public function getOwnerAttribute()
    {
        return $this->owner()->first()->name; // Assuming the 'name' field exists in the User model
    }

    public function getStatusNameAttribute()
    {
        return $this->status()->first()->status_name; // Assuming the 'status_name' field exists in the ItemStatus model
    }

    public function getCategoryAttribute()
    {
        return $this->category()->first()->category_name;
    }

    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    public function orders()
    {
        return $this->hasMany(Orders::class, 'item_id');
    }

}
