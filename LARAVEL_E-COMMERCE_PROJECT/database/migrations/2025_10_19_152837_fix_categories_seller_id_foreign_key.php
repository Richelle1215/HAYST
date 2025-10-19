<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Drop old foreign key
            $table->dropForeign(['seller_id']);
            
            // Add correct foreign key pointing to sellers table
            $table->foreign('seller_id')
                  ->references('id')
                  ->on('sellers')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['seller_id']);
            
            // Restore old foreign key
            $table->foreign('seller_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }
};