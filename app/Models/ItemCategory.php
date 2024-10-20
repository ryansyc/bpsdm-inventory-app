<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name'
    ];

    // Define the relationship with Item
    public function items()
    {
        return $this->hasMany(Item::class, 'category_id');
    }
}
