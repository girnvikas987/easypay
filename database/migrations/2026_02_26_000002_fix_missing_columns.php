<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add received_amnt to investments table
        if (Schema::hasTable('investments') && !Schema::hasColumn('investments', 'received_amnt')) {
            Schema::table('investments', function (Blueprint $table) {
                $table->double('received_amnt', 15, 2)->default(0)->after('amount');
            });
        }

        // Add status column to products if missing
        if (Schema::hasTable('products') && !Schema::hasColumn('products', 'status')) {
            Schema::table('products', function (Blueprint $table) {
                $table->tinyInteger('status')->default(1)->after('amount');
            });
        }

        // Create otp table if missing (referenced by DashboardController test)
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
        if (Schema::hasColumn('investments', 'received_amnt')) {
            Schema::table('investments', function (Blueprint $table) {
                $table->dropColumn('received_amnt');
            });
        }

        Schema::dropIfExists('otp');
    }
};
