<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExitItem extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'invoice_id',
        'item_id',
        'unit',
        'unit_price',
        'unit_quantity',
        'total_price',
    ];

    public function exitInvoice()
    {
        return $this->belongsTo(ExitInvoice::class, 'invoice_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
