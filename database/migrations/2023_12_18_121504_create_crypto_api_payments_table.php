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
        Schema::create('crypto_api_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('tx_id')->unique()->nullable()->default(null);
            $table->string('token_id')->nullable()->default(null);
            $table->double('amount', 15, 8)->nullable()->default(null);
            $table->string('wallet_address')->nullable()->default(null);
            $table->string('status')->nullable()->default('pending');
            $table->string('hash')->nullable()->default(null);
            $table->dateTime('end_at')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crypto_api_payments');
    }
};
