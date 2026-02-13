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
        Schema::create('operators', function (Blueprint $table) {
            $table->id();
               $table->string('operator_name');      // e.g. Videocon Special
            $table->string('operator_id');        // e.g. 13
            $table->string('service_type');       // e.g. Prepaid
            $table->boolean('status')->default(0); // 0 = inactive, 1 = active
            $table->enum('biller_status', ['on', 'off'])->default('off');
            $table->enum('bill_fetch', ['YES', 'NO'])->default('NO');
            $table->string('supportValidation')->default('NOT_SUPPORTED');
            $table->enum('bbps_enabled', ['YES', 'NO'])->default('NO');
            $table->string('message')->nullable();
            $table->text('description')->nullable();
            $table->decimal('amount_minimum', 10, 2)->default(0);
            $table->decimal('amount_maximum', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operators');
    }
};
