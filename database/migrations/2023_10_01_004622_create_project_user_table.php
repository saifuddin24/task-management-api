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
        Schema::disableForeignKeyConstraints();

        Schema::create('project_user', function (Blueprint $table) {
            $table->unsignedMediumInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedSmallInteger('project_id');
            $table->foreign('project_id')->references('id')->on('projects');
            $table->timestamp('created_at')->nullable()->default(null);
            $table->primary(['user_id', 'project_id']);
            $table->index(['user_id', 'project_id']);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_user');
    }
};
