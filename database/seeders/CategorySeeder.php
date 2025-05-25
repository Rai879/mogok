<?php
// database/seeders/CategorySeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Engine Parts',
                'description' => 'Komponen mesin kendaraan'
            ],
            [
                'name' => 'Brake System',
                'description' => 'Sistem rem dan komponennya'
            ],
            [
                'name' => 'Electrical',
                'description' => 'Komponen kelistrikan'
            ],
            [
                'name' => 'Body Parts',
                'description' => 'Sparepart bodi kendaraan'
            ],
            [
                'name' => 'Suspension',
                'description' => 'Sistem suspensi'
            ],
            [
                'name' => 'Filters',
                'description' => 'Filter oli, udara, dan bahan bakar'
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'is_active' => true,
            ]);
        }
    }
}