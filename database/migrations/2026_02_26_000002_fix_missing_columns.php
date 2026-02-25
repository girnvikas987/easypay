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
    }

    public function down(): void
    {
        if (Schema::hasColumn('investments', 'received_amnt')) {
            Schema::table('investments', function (Blueprint $table) {
                $table->dropColumn('received_amnt');
            });
        }
    }
};
