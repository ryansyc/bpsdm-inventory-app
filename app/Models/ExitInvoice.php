<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExitInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'category_id',
        'date',
        'total',
        'provider',
        'receiver',
    ];

    public $timestamps = false;

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function exitItems()
    {
        return $this->hasMany(ExitItem::class, 'invoice_id');
    }
}
