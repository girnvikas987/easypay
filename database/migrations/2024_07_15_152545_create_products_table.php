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
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('short_description')->nullable();
            $table->text('description')->nullable();
            $table->decimal('regular_price')->nullable()->default(0);
            $table->decimal('sale_price')->nullable()->default(0);
            $table->string('SKU');
            $table->string('stock_status')->nullable()->default('in_stock');
            $table->boolean('featured')->nullable()->default(false);
            $table->unsignedInteger('quantity')->default(1);
            $table->string('image')->nullable();
            $table->text('images')->nullable(); 
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
