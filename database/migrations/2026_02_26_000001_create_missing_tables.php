<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Package tables ──────────────────────────────────────────────

        if (!Schema::hasTable('ebike_packages')) {
            Schema::create('ebike_packages', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('slug')->nullable();
                $table->string('type')->nullable();
                $table->string('no_of_time')->nullable();
                $table->double('min', 15, 2)->nullable()->default(0);
                $table->double('max', 15, 2)->nullable()->default(0);
                $table->double('amount', 15, 2)->nullable()->default(0);
                $table->tinyInteger('status')->default(1);
                $table->tinyInteger('pre_reqired')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('elite_packages')) {
            Schema::create('elite_packages', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('slug')->nullable();
                $table->string('type')->nullable();
                $table->string('no_of_time')->nullable();
                $table->double('min', 15, 2)->nullable()->default(0);
                $table->double('max', 15, 2)->nullable()->default(0);
                $table->double('amount', 15, 2)->nullable()->default(0);
                $table->tinyInteger('status')->default(1);
                $table->tinyInteger('pre_reqired')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('fly_packages')) {
            Schema::create('fly_packages', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('slug')->nullable();
                $table->string('type')->nullable();
                $table->string('no_of_time')->nullable();
                $table->double('min', 15, 2)->nullable()->default(0);
                $table->double('max', 15, 2)->nullable()->default(0);
                $table->double('amount', 15, 2)->nullable()->default(0);
                $table->tinyInteger('status')->default(1);
                $table->tinyInteger('pre_reqired')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('gold_packages')) {
            Schema::create('gold_packages', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('slug')->nullable();
                $table->string('type')->nullable();
                $table->string('no_of_time')->nullable();
                $table->double('min', 15, 2)->nullable()->default(0);
                $table->double('max', 15, 2)->nullable()->default(0);
                $table->double('amount', 15, 2)->nullable()->default(0);
                $table->tinyInteger('status')->default(1);
                $table->tinyInteger('pre_reqired')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('tour_packages')) {
            Schema::create('tour_packages', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('slug')->nullable();
                $table->string('type')->nullable();
                $table->string('no_of_time')->nullable();
                $table->double('min', 15, 2)->nullable()->default(0);
                $table->double('max', 15, 2)->nullable()->default(0);
                $table->double('amount', 15, 2)->nullable()->default(0);
                $table->tinyInteger('status')->default(1);
                $table->tinyInteger('pre_reqired')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('loan_packages')) {
            Schema::create('loan_packages', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('slug')->nullable();
                $table->string('type')->nullable();
                $table->string('no_of_time')->nullable();
                $table->double('min', 15, 2)->nullable()->default(0);
                $table->double('max', 15, 2)->nullable()->default(0);
                $table->double('amount', 15, 2)->nullable()->default(0);
                $table->tinyInteger('status')->default(1);
                $table->tinyInteger('pre_reqired')->default(0);
                $table->timestamps();
            });
        }

        // ── Investment tables ───────────────────────────────────────────

        if (!Schema::hasTable('ebike_investments')) {
            Schema::create('ebike_investments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->integer('tx_user')->nullable();
                $table->integer('package_id')->nullable();
                $table->integer('days')->default(0);
                $table->integer('pair_days')->default(0);
                $table->double('amount', 15, 2)->default(0);
                $table->double('received_amnt', 15, 2)->default(0);
                $table->tinyInteger('status')->default(0);
                $table->tinyInteger('package_status')->default(1);
                $table->timestamps();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('elite_investments')) {
            Schema::create('elite_investments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->integer('tx_user')->nullable();
                $table->integer('package_id')->nullable();
                $table->integer('days')->default(0);
                $table->integer('pair_days')->default(0);
                $table->double('amount', 15, 2)->default(0);
                $table->double('received_amnt', 15, 2)->default(0);
                $table->double('cashback_amount', 15, 2)->default(0);
                $table->tinyInteger('status')->default(0);
                $table->tinyInteger('package_status')->default(1);
                $table->timestamps();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('fly_investments')) {
            Schema::create('fly_investments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->integer('tx_user')->nullable();
                $table->integer('package_id')->nullable();
                $table->integer('days')->default(0);
                $table->double('amount', 15, 2)->default(0);
                $table->double('received_amnt', 15, 2)->default(0);
                $table->double('cashback_amount', 15, 2)->default(0);
                $table->string('flash')->nullable();
                $table->string('invoice')->nullable();
                $table->tinyInteger('status')->default(0);
                $table->tinyInteger('package_status')->default(1);
                $table->timestamps();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('gold_investments')) {
            Schema::create('gold_investments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->integer('tx_user')->nullable();
                $table->integer('package_id')->nullable();
                $table->double('received_amnt', 15, 2)->default(0);
                $table->double('amount', 15, 2)->default(0);
                $table->tinyInteger('status')->default(0);
                $table->tinyInteger('package_status')->default(1);
                $table->tinyInteger('pkg_status')->default(1);
                $table->timestamps();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('tour_investments')) {
            Schema::create('tour_investments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->integer('package_id')->nullable();
                $table->integer('days')->default(0);
                $table->double('amount', 15, 2)->default(0);
                $table->double('received_amnt', 15, 2)->default(0);
                $table->tinyInteger('status')->default(0);
                $table->timestamps();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('loan_investments')) {
            Schema::create('loan_investments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->integer('package_id')->nullable();
                $table->double('amount', 15, 2)->default(0);
                $table->tinyInteger('status')->default(0);
                $table->timestamps();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        // ── Loans table ─────────────────────────────────────────────────

        if (!Schema::hasTable('loans')) {
            Schema::create('loans', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->integer('loan_id')->nullable();
                $table->double('amount', 15, 2)->default(0);
                $table->double('charges', 15, 2)->default(0);
                $table->string('wallet')->nullable();
                $table->string('tx_id')->nullable();
                $table->string('remark')->nullable();
                $table->string('loan_status')->nullable();
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        // ── Games table ─────────────────────────────────────────────────

        if (!Schema::hasTable('games')) {
            Schema::create('games', function (Blueprint $table) {
                $table->id();
                $table->string('type')->nullable();
                $table->dateTime('time')->nullable();
                $table->integer('number')->nullable();
                $table->double('amount', 15, 2)->default(0);
                $table->integer('no_of_users')->default(0);
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
            });
        }

        // ── Plan tables ─────────────────────────────────────────────────

        if (!Schema::hasTable('plan_ebike_royalties')) {
            Schema::create('plan_ebike_royalties', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->integer('team_required')->default(0);
                $table->integer('level')->default(0);
                $table->double('value', 15, 2)->default(0);
                $table->integer('Commission_limit')->default(0);
                $table->string('commission_type')->nullable();
                $table->integer('wallet_type_id')->nullable();
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('plan_gold_royalties')) {
            Schema::create('plan_gold_royalties', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->integer('team_required')->default(0);
                $table->integer('level')->default(0);
                $table->double('value', 15, 2)->default(0);
                $table->integer('Commission_limit')->default(0);
                $table->string('commission_type')->nullable();
                $table->integer('wallet_type_id')->nullable();
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('plan_lucky_draws')) {
            Schema::create('plan_lucky_draws', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->double('amount', 15, 2)->default(0);
                $table->double('win_amount', 15, 2)->default(0);
                $table->integer('wallet_type_id')->nullable();
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('plan_jackpots')) {
            Schema::create('plan_jackpots', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->double('amount', 15, 2)->default(0);
                $table->double('win_amount', 15, 2)->default(0);
                $table->integer('wallet_type_id')->nullable();
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('plan_gift_draws')) {
            Schema::create('plan_gift_draws', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->double('amount', 15, 2)->default(0);
                $table->double('win_amount', 15, 2)->default(0);
                $table->integer('wallet_type_id')->nullable();
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('plan_tour_draws')) {
            Schema::create('plan_tour_draws', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->double('amount', 15, 2)->default(0);
                $table->double('win_amount', 15, 2)->default(0);
                $table->integer('wallet_type_id')->nullable();
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('plan_trips')) {
            Schema::create('plan_trips', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->double('amount', 15, 2)->default(0);
                $table->integer('team_required')->default(0);
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
            });
        }

        // ── Draw participation tables ───────────────────────────────────

        if (!Schema::hasTable('lucky_dra_participates')) {
            Schema::create('lucky_dra_participates', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->integer('draw_id')->nullable();
                $table->double('pay_amount', 15, 2)->default(0);
                $table->double('amount', 15, 2)->default(0);
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('gift_draw_participates')) {
            Schema::create('gift_draw_participates', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->integer('draw_id')->nullable();
                $table->double('pay_amount', 15, 2)->default(0);
                $table->double('amount', 15, 2)->default(0);
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('jackpot_draw_participates')) {
            Schema::create('jackpot_draw_participates', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->integer('jackpot_id')->nullable();
                $table->double('pay_amount', 15, 2)->default(0);
                $table->double('amount', 15, 2)->default(0);
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('tour_draw_participates')) {
            Schema::create('tour_draw_participates', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->integer('draw_id')->nullable();
                $table->double('pay_amount', 15, 2)->default(0);
                $table->double('amount', 15, 2)->default(0);
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('spinner_participates')) {
            Schema::create('spinner_participates', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->double('amount', 15, 2)->default(0);
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        // ── Recharge trip achivers ──────────────────────────────────────

        if (!Schema::hasTable('recharge_trip_achivers')) {
            Schema::create('recharge_trip_achivers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->integer('trip_id')->nullable();
                $table->string('trip_name')->nullable();
                $table->string('trip_time')->nullable();
                $table->double('amount', 15, 2)->default(0);
                $table->tinyInteger('status')->default(0);
                $table->timestamps();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        // ── Loan lists (referenced by Loan model) ──────────────────────

        if (!Schema::hasTable('loan_lists')) {
            Schema::create('loan_lists', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->double('amount', 15, 2)->default(0);
                $table->double('interest', 15, 2)->default(0);
                $table->integer('tenure')->default(0);
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'recharge_trip_achivers', 'spinner_participates', 'tour_draw_participates',
            'jackpot_draw_participates', 'gift_draw_participates', 'lucky_dra_participates',
            'plan_trips', 'plan_tour_draws', 'plan_gift_draws', 'plan_jackpots',
            'plan_lucky_draws', 'plan_gold_royalties', 'plan_ebike_royalties',
            'games', 'loans', 'loan_lists', 'loan_investments', 'tour_investments',
            'gold_investments', 'fly_investments', 'elite_investments', 'ebike_investments',
            'loan_packages', 'tour_packages', 'gold_packages', 'fly_packages',
            'elite_packages', 'ebike_packages',
        ];

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
    }
};
