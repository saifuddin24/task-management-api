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

        Schema::create('team_project', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('project_id')->index();
            $table->foreign('project_id')->references('id')->on('projects');
            $table->unsignedMediumInteger('team_id')->index();
            $table->foreign('team_id')->references('id')->on('teams');
            $table->timestamp('created_at')->nullable()->default(null);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_project');
    }
};
