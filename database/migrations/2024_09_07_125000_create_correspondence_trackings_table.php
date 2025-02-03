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
        Schema::create('correspondence_trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('correspondence_id')->nullable()
                ->constrained('correspondences')->onDelete('cascade');
            $table->foreignId('from_user_id')->nullable()
                ->constrained('users')->onDelete('cascade');
            $table->foreignId('to_user_id')->nullable()
                ->constrained('users')->onDelete('cascade');
            $table->timestamp('request_date')->useCurrent();
            $table->text('notes')->nullable();
            $table->string('signature')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('correspondence_trackings');
    }
};
