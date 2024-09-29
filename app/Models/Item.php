<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'quantity'
    ];

    // Define the relationship with ItemCategory
    public function category()
    {
        return $this->belongsTo(ItemCategory::class);
    }

    // Define the relationship with ItemEntry
    public function entries()
    {
        return $this->hasMany(ItemEntry::class);
    }

    // Define the relationship with ItemExit
    public function exits()
    {
        return $this->hasMany(ItemExit::class);
    }
}
