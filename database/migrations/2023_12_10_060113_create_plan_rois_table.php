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
        Schema::create('plan_rois', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('package_id');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
            $table->integer('direct_required')->nullable()->default(0);
            $table->string('commision_type')->nullable()->default('percent');
            $table->double('value', 15, 8)->nullable()->default(0);
            $table->boolean('status')->nullable()->default(false);
            $table->boolean('level_on_roi')->nullable()->default(false);
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
        Schema::dropIfExists('plan_rois');
    }
};
