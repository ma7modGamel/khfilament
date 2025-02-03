<?php

use App\Models\FormStatusRole;
use App\Models\ProjectUser;
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
        Schema::table('form_status_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('project_user_id')->nullable();
            $table->foreignId(ProjectUser::class, 'project_user_id')->references('id')->on('form_status_roles')->before('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_status_roles', function (Blueprint $table) {
            $table->dropForeign('project_user_id');
            $table->dropColumn('project_user_id');
        });
    }
};
