<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Observers\FileObserver;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'position',
        'file',
        'status',
        'description',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        self::observe(FileObserver::class);
    }
}
