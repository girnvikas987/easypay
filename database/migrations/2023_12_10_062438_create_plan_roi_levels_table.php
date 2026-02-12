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
        Schema::create('plan_roi_levels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_roi_id');
            $table->foreign('plan_roi_id')->references('id')->on('plan_rois')->onDelete('cascade');
            $table->integer('direct_required')->nullable()->default(0);
            $table->integer('level')->nullable()->default(1);
            $table->string('source')->nullable();
            $table->string('type')->nullable();
            $table->string('commision_type')->nullable()->default('percent');
            $table->double('value', 15, 8)->nullable()->default(0);
            $table->boolean('status')->nullable()->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_roi_levels');
    }
};
