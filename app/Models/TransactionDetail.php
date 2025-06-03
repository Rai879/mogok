<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'part_id',
        'quantity',
        'price_at_transaction',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function part()
    {
        return $this->belongsTo(Part::class);
    }
}