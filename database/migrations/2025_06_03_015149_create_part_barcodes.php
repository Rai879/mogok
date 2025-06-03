<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_part_barcodes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('part_barcodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('part_id')->constrained('parts')->onDelete('cascade');
            $table->string('barcode')->unique();
            $table->timestamps();

            $table->index('barcode');
        });
    }

    public function down()
    {
        Schema::dropIfExists('part_barcodes');
    }
};