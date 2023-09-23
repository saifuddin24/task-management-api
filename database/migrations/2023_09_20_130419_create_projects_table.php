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
            $table->increments('id');
            $table->string('title', 255);
            $table->string('slogan', 255)->nullable();
            $table->text('description')->nullable();
            $table->date('deadline')->nullable();
            $table->unsignedSmallInteger('working_days_needed')->nullable();
            $table->unsignedMediumInteger('manager_id');
            $table->foreign('manager_id')->references('id')->on('users');
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->index('manager_id');
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
