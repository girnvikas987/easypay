<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('otp')) {
            Schema::create('otp', function (Blueprint $table) {
                $table->id();
                $table->string('mobile')->nullable();
                $table->string('code')->nullable();
                $table->tinyInteger('status')->default(0);
                $table->dateTime('time')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('otp');
    }
};
