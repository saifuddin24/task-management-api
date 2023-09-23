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
            $table->string('name', 150);
            $table->string('phone', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('password', 96);
            $table->timestamp('email_verified_at')->nullable();
            $table->index('name');
            $table->index('phone');
            $table->unique('phone');
            $table->unique('email');
            $table->timestamps();
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
