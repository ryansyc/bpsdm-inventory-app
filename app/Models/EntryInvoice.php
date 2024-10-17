<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntryInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'date',
        'total'
    ];

    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function entryItems()
    {
        return $this->hasMany(EntryItem::class);
    }
}
