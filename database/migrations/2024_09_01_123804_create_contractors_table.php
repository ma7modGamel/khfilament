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
        Schema::create('contractors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('delivery_name')->nullable();
            $table->string('delivery_phone')->nullable();
            $table->string('manger_name')->nullable();
            $table->string('manger_phone')->nullable();
            $table->foreignId('type_id')->nullable()
                ->constrained('contractor_types')->onDelete('cascade');
            $table->foreignId('city_id')->nullable()
                ->constrained('cities')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contractors');
    }
};
