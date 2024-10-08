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
        'user_id',
        'total_price',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
