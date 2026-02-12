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
        Schema::create('plan_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('plan_id')->nullable();
            $table->string('title')->nullable();
            $table->string('type')->nullable();
            $table->string('value')->nullable();
            $table->tinyInteger('status')->default(1)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_settings');
        Schema::dropIfExists('level_commision');
        Schema::dropIfExists('autopool');
    }
};
