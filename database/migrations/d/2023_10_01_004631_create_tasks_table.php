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

        Schema::create('tasks', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->string('title', 255);
            $table->timestamp('deadline');
            $table->text('description')->nullable();
            $table->unsignedMediumInteger('employee_id')->index();
            $table->unsignedSmallInteger('project_id')->index()->nullable()->default(null);
            $table->foreign('project_id')->references('id')->on('projects');
            $table->unsignedTinyInteger('difficulty_level')->default(1)->comment('1=Easy,2=Moderate,3=Challanging,4=Difficult,5=Expert');
            $table->unsignedTinyInteger('priority')->comment('1=No Priority, 2=Deferred Priority, 3=Low Priority, 4=Moderate Priority, 5=High Priority, 6=Urgent');
            $table->timestamp('created_at')->nullable()->default(null);
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
        Schema::dropIfExists('tasks');
    }
};
