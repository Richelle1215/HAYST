<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Remove the old unique constraint on name only
            $table->dropUnique(['name']);
            
            // Add composite unique constraint: seller can't have duplicate category names
            // But different sellers CAN have same category names
            $table->unique(['seller_id', 'name'], 'categories_seller_id_name_unique');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Reverse the changes
            $table->dropUnique('categories_seller_id_name_unique');
            $table->unique('name');
        });
    }
};