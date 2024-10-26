<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Observers\FileObserver;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'date',
        'name',
        'position',
        'file',
    ];
    public $timestamps = false;


    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    protected static function booted()
    {
        self::observe(FileObserver::class);
    }
}
