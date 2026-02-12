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
        Schema::create('plan_months', function (Blueprint $table) {
            $table->id();
            $table->decimal('direct_required')->nullable()->default(0);
            $table->decimal('generation_team_required')->nullable()->default(0);
            $table->string('name')->nullable();
            $table->string('rank')->nullable();
            $table->string('month')->nullable();
            $table->string('value')->nullable();
            $table->string('total_reward', 200)->nullable();
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
        Schema::dropIfExists('plan_months');
    }
};
