<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cart', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('customer_name');
            $table->integer('quantity')->default(1);
            $table->timestamps();
            
            // Prevent duplicate entries
            $table->unique(['user_id', 'product_id']);
        });
    
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};