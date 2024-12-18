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
        'unit',
        'unit_quantity',
        'unit_price',
        'total_price',
    ];
    public $timestamps = false;

    public function entryItems()
    {
        return $this->hasMany(EntryItem::class, 'item_id');
    }

    public function exitItems()
    {
        return $this->hasMany(ExitItem::class, 'item_id');
    }

    public function calculateTotalPrice($quantity): int
    {
        return $this->unit_price * $quantity;
    }
}
