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
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        // Foreign Key
        $table->foreignId('category_id')->constrained()->onDelete('cascade');
        // Product Details
        $table->string('name');
        $table->text('description');
        $table->decimal('price', 8, 2); 
        $table->integer('stock');
        $table->string('image')->nullable(); // Para sa file path ng image
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
