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


    public function itemEntries()
    {
        return $this->hasMany(ItemEntry::class, 'category_id');
    }

    public function itemExits()
    {
        return $this->hasMany(ItemExit::class, 'category_id');
    }
}
