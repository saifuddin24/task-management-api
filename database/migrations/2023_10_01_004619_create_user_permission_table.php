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

        Schema::create('user_permission', function (Blueprint $table) {
            $table->unsignedInteger('permission_id')->index();
            $table->foreign('permission_id')->references('id')->on('permissions');
            $table->unsignedMediumInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamp('created_at')->nullable()->default(null);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_permission');
    }
};
