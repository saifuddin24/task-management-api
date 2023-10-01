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

        Schema::create('user_role', function (Blueprint $table) {
            $table->unsignedSmallInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->unsignedMediumInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamp('created_at')->nullable()->default(null);
            $table->primary(['role_id', 'user_id']);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_role');
    }
};
