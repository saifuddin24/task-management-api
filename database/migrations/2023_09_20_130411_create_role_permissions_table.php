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

        Schema::create('role_permissions', function (Blueprint $table) {
            $table->unsignedMediumInteger('permission_id');
            $table->foreign('permission_id')->references('id')->on('permissions');
            $table->unsignedSmallInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
