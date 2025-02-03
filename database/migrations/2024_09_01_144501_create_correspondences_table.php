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
        Schema::create('correspondences', function (Blueprint $table) {
            $table->id();
            $table->string('number')->nullable();
            $table->date('date')->nullable();
            $table->foreignId('correspondent_id')->constrained();
            $table->foreignId('correspondence_document_id')->constrained();
            $table->foreignId('project_id')->nullable()
                ->constrained('projects')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->string('file')->nullable();
            $table->float('total_value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('correspondences');
    }
};
