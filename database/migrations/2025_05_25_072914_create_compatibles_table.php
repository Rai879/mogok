<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_compatibles_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('compatibles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('part_id')->constrained('parts')->onDelete('cascade');
            $table->string('vehicle_make'); // Honda, Toyota, Yamaha, dll
            $table->string('vehicle_model'); // Supra, Vario, Beat, dll
            $table->string('vehicle_year')->nullable(); // 2020, 2021-2023, dll
            $table->string('engine_type')->nullable(); // 125cc, 150cc, 1500cc, dll
            $table->text('notes')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();

            // Index untuk performa pencarian
            $table->index(['part_id', 'vehicle_make', 'vehicle_model']);
            $table->index('vehicle_make');
            $table->index('vehicle_model');
            
            // Unique constraint untuk mencegah duplikasi
            $table->unique(['part_id', 'vehicle_make', 'vehicle_model', 'vehicle_year'], 'unique_compatibility');
        });
    }

    public function down()
    {
        Schema::dropIfExists('compatibles');
    }
};