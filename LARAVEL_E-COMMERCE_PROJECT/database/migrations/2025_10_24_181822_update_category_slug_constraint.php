<?php

// database/migrations/..._update_category_slug_constraint.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // 1. Drop the existing unique constraint on the 'slug' column
            // (The name is usually 'tablename_columnname_unique' in Laravel)
            $table->dropUnique('categories_slug_unique');

            // 2. Add the new unique constraint on a combination of 'slug' AND 'seller_id'
            $table->unique(['slug', 'seller_id']);
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Reverse the changes: drop the composite index and restore the single-column index (if desired)
            $table->dropUnique(['slug', 'seller_id']);

            // Re-add the single-column unique index (optional, only if needed globally)
            // $table->unique('slug', 'categories_slug_unique'); 
        });
    }
};