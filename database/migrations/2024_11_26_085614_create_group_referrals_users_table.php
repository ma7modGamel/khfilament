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
        Schema::create('group_referrals_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_referral_id')->nullable()
                ->constrained('group_referrals')->onDelete('cascade');

            $table->foreignId('user_id')->nullable()
                ->constrained('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_referrals_users');
    }
};
