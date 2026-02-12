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
        Schema::create('saving_fund_investments', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('user_id'); 
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('package_id'); 
            $table->foreign('package_id')->references('id')->on('saving_packages');
            $table->double('amount', 8, 2); 
            $table->string('days')->nullable()->default(0); 
            $table->tinyInteger('status')->default(0);
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saving_fund_investments');
    }
};
