<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_temp_transactions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('temp_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('part_id')->constrained('parts')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('price_at_transaction', 12, 2); // Price of the part at the time of adding to cart
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('temp_transactions');
    }
};