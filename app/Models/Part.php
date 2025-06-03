<?php
// app/Models/Part.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'part_number',
        'description',
        'category_id',
        'brand',
        'price',
        'stock_quantity',
        'minimum_stock',
        'condition',
        'specifications',
        'image',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'minimum_stock' => 'integer',
        'specifications' => 'array',
        'is_active' => 'boolean',
    ];

    // Relasi ke Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi ke Compatibles
    public function compatibles()
    {
        return $this->hasMany(Compatible::class);
    }

    public function barcodes()
    {
        return $this->hasMany(PartBarcode::class);
    }

    public function tempTransactions()
    {
        return $this->hasMany(TempTransaction::class);
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    // Scope untuk part aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk stok rendah
    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock_quantity', '<=', 'minimum_stock');
    }

    // Accessor untuk status stok
    public function getStockStatusAttribute()
    {
        if ($this->stock_quantity <= 0) {
            return 'out_of_stock';
        } elseif ($this->stock_quantity <= $this->minimum_stock) {
            return 'low_stock';
        }
        return 'in_stock';
    }
}