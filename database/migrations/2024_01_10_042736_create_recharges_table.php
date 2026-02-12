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
        Schema::create('recharges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('api_tansasction_id')->nullable()->default(null);
            $table->string('provider_id')->nullable()->default(null);
            $table->double('amount', 15, 8)->nullable()->default(null);
            $table->double('bouns_balance', 15, 8)->nullable()->default(null);
            $table->string('tx_id')->nullable()->default(null);
            $table->string('api_status')->nullable()->default(null);
            $table->string('recharge_type')->nullable()->default(null);
            $table->string('remark')->nullable()->default(null);
            $table->string('wallet_type')->nullable()->default(null);
            $table->string('mobile')->nullable()->default(null);
            $table->boolean('status')->nullable()->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recharges');
    }
};
