<?php
// app/Models/Compatible.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compatible extends Model
{
    use HasFactory;

    protected $table = 'compatibles';

    protected $fillable = [
        'part_id',
        'vehicle_make',
        'vehicle_model',
        'vehicle_year',
        'engine_type',
        'notes',
        'is_verified',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    // Relasi ke Part
    public function part()
    {
        return $this->belongsTo(Part::class);
    }

    // Scope untuk kompatibilitas terverifikasi
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    // Scope pencarian berdasarkan kendaraan
    public function scopeForVehicle($query, $make, $model = null, $year = null)
    {
        $query->where('vehicle_make', $make);
        
        if ($model) {
            $query->where('vehicle_model', $model);
        }
        
        if ($year) {
            $query->where(function($q) use ($year) {
                $q->where('vehicle_year', $year)
                  ->orWhere('vehicle_year', 'like', "%{$year}%")
                  ->orWhereNull('vehicle_year');
            });
        }
        
        return $query;
    }
}