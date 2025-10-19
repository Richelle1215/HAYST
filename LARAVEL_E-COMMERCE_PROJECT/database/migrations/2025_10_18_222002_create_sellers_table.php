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
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();

            // Foreign key to users table
            $table->unsignedBigInteger('user_id');

            // Shop details
            $table->string('shop_name')->unique(); // Must match validation in CreateNewUser.php
            $table->text('shop_name_container')->nullable(); // Optional description

            $table->timestamps();

            // Set foreign key constraint
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sellers');
    }
};
