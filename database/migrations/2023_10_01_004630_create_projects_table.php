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

        Schema::create('projects', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('title', 255);
            $table->string('slogan', 255)->nullable()->default(null);
            $table->text('description')->nullable();
            $table->date('deadline')->nullable()->default(null);
            $table->unsignedSmallInteger('working_days_needed')->nullable()->default(null);
            $table->unsignedMediumInteger('manager_id')->index();
            $table->foreign('manager_id')->references('id')->on('users');
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable()->default(null);
            $table->timestamp('deleted_at')->nullable()->default(null);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
