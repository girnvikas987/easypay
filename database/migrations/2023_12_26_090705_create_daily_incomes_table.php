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
        Schema::create('daily_incomes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->double('direct', 15, 8)->nullable()->default(null);
            $table->double('level', 15, 8)->nullable()->default(null);
            $table->double('roi', 15, 8)->nullable()->default(null);
            $table->double('reward', 15, 8)->nullable()->default(null);
            $table->double('autopool', 15, 8)->nullable()->default(null);
            $table->double('self_recharge', 15, 8)->nullable()->default(null);
            $table->double('recharge_level', 15, 8)->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_incomes');
    }
};
