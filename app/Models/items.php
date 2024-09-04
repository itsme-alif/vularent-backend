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

    protected $appends = ['owner', 'status_name', 'category_name'];

    /**
     * Relationship to the User model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Relationship to the ItemStatus model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(ItemStatus::class, 'status_id');
    }

    /**
     * Relationship to the Categories model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    /**
     * Get the owner's name.
     *
     * @return string
     */
    public function getOwnerAttribute()
    {
        return $this->owner()->first()->name; // Assuming the 'name' field exists in the User model
    }

    /**
     * Get the status name.
     *
     * @return string
     */
    public function getStatusNameAttribute()
    {
        return $this->status()->first()->status_name; // Assuming the 'status_name' field exists in the ItemStatus model
    }

    /**
     * Get the category name.
     *
     * @return string
     */
    public function getCategoryNameAttribute()
    {
        return $this->category()->first()->category_name;
    }

    /**
     * Relationship to the Orders model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Orders::class, 'item_id');
    }
}
