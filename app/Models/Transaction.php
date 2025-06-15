<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'total_amount',
        'cash_paid',
        'change_due',
    ];

    public function details()
{
    return $this->hasMany(TransactionDetail::class);
}

// app/Models/TransactionDetail.php
public function part()
{
    return $this->belongsTo(Part::class);
}
}