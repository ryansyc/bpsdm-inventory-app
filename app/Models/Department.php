<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    public $timestamps = false;


    public function exitInvoices()
    {
        return $this->hasMany(ExitInvoice::class, 'department_id');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'department_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'department_id');
    }
}
