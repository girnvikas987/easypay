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
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); 
            $table->foreign('user_id')->references('id')->on('users');
            //$table->foreignIdFor(User::class);
            //            $table->unsignedBigInteger('user_id');
            $table->decimal('amount', 15,8)->default(0)->comment("Withdrawal amount");
            $table->decimal('tx_charge', 15,8)->default(0);
            $table->decimal('tds_charge', 15,8)->default(0);
            $table->string('user_details')->nullable();
            $table->string('reason')->default(null)->nullable();
            $table->tinyInteger('status')->default(0)->nullable();
            //            $table->string('currency')->nullable()->index() ->comment("Currency code of withdrawn currency");


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};
