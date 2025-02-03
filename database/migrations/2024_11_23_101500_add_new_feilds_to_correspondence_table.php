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
        Schema::table('correspondences', function (Blueprint $table) {
            $table->foreignId('receive_method_id')->nullable()
                ->constrained('document_receive_methods')->onDelete('cascade');
            $table->enum('type', ['original', 'copy'])->default('original');
            $table->boolean('finished')->default(false);
            $table->string('path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('correspondences', function (Blueprint $table) {
            //
        });
    }
};
