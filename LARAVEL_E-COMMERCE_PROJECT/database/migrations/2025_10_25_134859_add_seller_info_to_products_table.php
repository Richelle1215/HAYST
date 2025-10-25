<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('shop_name')->default('LUMIÈRE Main Store')->after('category_id');
            $table->unsignedBigInteger('seller_id')->nullable()->after('shop_name');
            
            // If you have a users table for sellers
            // $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['shop_name', 'seller_id']);
        });
    }
};