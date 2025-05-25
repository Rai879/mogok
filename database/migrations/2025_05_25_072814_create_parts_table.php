<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_parts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('parts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('part_number')->unique();
            $table->text('description')->nullable();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('brand')->nullable();
            $table->decimal('price', 12, 2);
            $table->integer('stock_quantity')->default(0);
            $table->integer('minimum_stock')->default(0);
            $table->string('condition')->default('new'); // new, used, refurbished
            $table->json('specifications')->nullable(); // JSON untuk spek teknis
            $table->string('image')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Index untuk performa
            $table->index(['category_id', 'is_active']);
            $table->index('part_number');
            $table->index('brand');
        });
    }

    public function down()
    {
        Schema::dropIfExists('parts');
    }
};