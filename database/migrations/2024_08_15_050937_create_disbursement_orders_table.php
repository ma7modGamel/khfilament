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
        Schema::create('disbursement_orders', function (Blueprint $table) {
            $table->id();
            $table->string('project_name')->nullable();
            $table->string('project_manager')->nullable();
            $table->string('project_employee')->nullable();
            $table->string('purchase_code')->nullable();
            $table->string('purchase_date')->nullable();
            $table->string('total_value')->nullable();
            $table->string('residual_value')->nullable();
            $table->string('payment')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('purchase_order_id')->nullable()->constrained('purchase_orders')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disbursement_orders');
    }
};
