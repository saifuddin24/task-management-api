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

        Schema::create('users', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->string('name', 150)->index();
            $table->string('phone', 20)->unique()->index()->nullable()->default(null);
            $table->string('email', 100)->unique()->nullable()->default(null);
            $table->string('password', 96);
            $table->timestamp('email_verified_at')->nullable()->default(null);
            $table->unsignedSmallInteger('role_id')->index();
            $table->foreign('role_id')->references('id')->on('roles');
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
