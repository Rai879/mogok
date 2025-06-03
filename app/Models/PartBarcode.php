<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartBarcode extends Model
{
    use HasFactory;

    protected $fillable = ['part_id', 'barcode'];

    public function part()
    {
        return $this->belongsTo(Part::class);
    }
}