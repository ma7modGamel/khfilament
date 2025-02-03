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
        Schema::create('competitions_prices', function (Blueprint $table) {
            $table->id();
            $table->morphs('modelable');
            $table->string('type')->nullable();
            $table->string('file')->nullable();
            $table->string('price')->nullable();
            $table->foreignId('project_id')
                ->references('id')->on('projects')
                ->onDelete('cascade');
            $table->string('file')->nullable();
            $table->foreignId('sender_id')->nullable()
                ->constrained('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competitions_prices');
    }
};
