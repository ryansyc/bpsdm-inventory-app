<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemExit extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'unit',
        'quantity',
        'total_price',
        'provider',
        'receiver',
        'department_id',
        'item_id',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
