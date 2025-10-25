<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('products', 'shop_name')) {
                $table->string('shop_name')->default('LUMIÈRE Main Store')->after('category_id');
            }
            
            if (!Schema::hasColumn('products', 'seller_id')) {
                $table->unsignedBigInteger('seller_id')->nullable()->after('shop_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['shop_name', 'seller_id']);
        });
    }
};