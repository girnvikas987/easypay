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
        Schema::create('binaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); 
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('parent')->default(null)->nullable();
            $table->integer('left')->default(null)->nullable();  
            // $table->longText('left_team')->default('[]')->nullable();  
            $table->longText('left_team')->default(null)->nullable();  
            $table->integer('right')->default(null)->nullable();  
            // $table->longText('right_team')->default('[]')->nullable();  
            $table->longText('right_team')->default(null)->nullable();  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('binaries');
    }
};
