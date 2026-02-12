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
        Schema::create('plan_recharge_commisions', function (Blueprint $table) {
            $table->id();
            $table->string('level')->nullable();
            $table->decimal('mobile', 10, 2)->default(0.00);
            $table->decimal('dth', 10, 2)->default(0.00);
            $table->decimal('bbps', 10, 2)->default(0.00);
            $table->decimal('gas', 10, 2)->default(0.00);
            $table->decimal('water', 10, 2)->default(0.00);
            $table->decimal('insurance', 10, 2)->default(0.00);
            $table->decimal('loan', 10, 2)->default(0.00);
            $table->tinyInteger('status')->default(1)->comment('1=active, 0=inactive');
            $table->string('type')->default('free')->comment('free, paid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_recharge_commisions');
    }
};
