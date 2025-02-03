<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('competitions_prices_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')
                ->references('id')->on('items')
                ->onDelete('cascade');
            $table->foreignId('competitions_price_id')
                ->references('id')->on('competitions_prices')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competitions_prices_items');
    }
};
