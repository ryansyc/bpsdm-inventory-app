<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name'
    ];
    public $timestamps = false;


    public function entryInvoices()
    {
        return $this->hasMany(EntryInvoice::class, 'category_id');
    }

    public function exitInvoices()
    {
        return $this->hasMany(ExitInvoice::class, 'category_id');
    }
}
