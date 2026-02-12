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
        Schema::create('kycs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('aadhar_no')->nullable()->default(null);
            $table->boolean('aadhar_status')->nullable()->default(null);
            $table->string('pan_no')->nullable()->default(null);
            $table->boolean('pan_status')->nullable()->default(null);
            $table->string('aadhar_front_image')->nullable()->default(null);
            $table->string('aadhar_back_image')->nullable()->default(null);
            $table->string('pan_image')->nullable()->default(null);
            $table->string('self_image')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kycs');
    }
};
