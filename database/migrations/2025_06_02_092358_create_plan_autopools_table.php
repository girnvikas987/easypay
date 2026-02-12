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
        Schema::create('plan_autopools', function (Blueprint $table) {
            $table->id();
               $table->integer('direct_required')->nullable()->default(0);
            $table->integer('level')->nullable()->default(1);
            $table->string('source')->nullable();
            $table->string('commision_type')->nullable()->default('percent');
            $table->double('value', 15, 8)->nullable()->default(0);
            $table->boolean('status')->nullable()->default(false);
            $table->unsignedBigInteger('wallet_type_id');
            $table->foreign('wallet_type_id')->references('id')->on('wallet_types')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_autopools');
    }
};
