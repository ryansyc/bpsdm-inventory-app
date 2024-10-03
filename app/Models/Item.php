<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'category_id',
        'quantity',
        'user_id',
    ];

    // Define the relationship with ItemCategory
    public function category()
    {
        return $this->belongsTo(ItemCategory::class, 'category_id');
    }

    // Define the relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Define the relationship with ItemEntry
    public function entries()
    {
        return $this->hasMany(ItemEntry::class, 'item_id');
    }

    // Define the relationship with ItemExit
    public function exits()
    {
        return $this->hasMany(ItemExit::class, 'item_id');
    }
}
