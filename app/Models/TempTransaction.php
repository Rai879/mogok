<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'part_id',
        'quantity',
        'price_at_transaction',
    ];

    public function part()
    {
        return $this->belongsTo(Part::class);
    }
}