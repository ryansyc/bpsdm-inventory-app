<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Observers\FileObserver;

class EntryInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'date',
        'total',
        'file',
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

    protected static function booted()
    {
        self::observe(FileObserver::class);
    }
}
