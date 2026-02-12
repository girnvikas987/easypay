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
        Schema::create('saving_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->string('type')->nullable();
            $table->string('no_of_time')->nullable();
            $table->double('min', 15, 8)->nullable()->default(0);
            $table->double('max', 15, 8)->nullable()->default(0);
            $table->double('amount', 15, 8)->nullable()->default(0);
            $table->tinyInteger('status')->nullable()->default(1);
            $table->tinyInteger('pre_reqired')->nullable()->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saving_packages');
    }
};
