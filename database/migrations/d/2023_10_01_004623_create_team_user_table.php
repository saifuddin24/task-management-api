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

        Schema::create('team_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedMediumInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedMediumInteger('team_id')->index();
            $table->foreign('team_id')->references('id')->on('teams');
            $table->timestamp('created_at')->nullable()->default(null);
            $table->unique(['user_id', 'team_id']);
            $table->index(['user_id', 'team_id']);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_user');
    }
};
