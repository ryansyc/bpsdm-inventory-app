<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'entry_date',
        'item_id',
        'quantity',
        'description',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
