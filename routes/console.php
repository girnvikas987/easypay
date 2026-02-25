<?php

use Illuminate\Support\Facades\Schedule;

// =============================================================================
// Financial Distribution Schedule
// Set up server cron: * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
// =============================================================================

// ROI Distribution
Schedule::command('distribute:roi')->dailyAt('00:00');
Schedule::command('distribute:roi --type=four')->dailyAt('00:15');

// Clear daily income
Schedule::command('income:clear-daily')->dailyAt('23:59');

// Royalty distribution
Schedule::command('distribute:royalty')->dailyAt('01:00');
Schedule::command('distribute:royalty --type=recharge')->dailyAt('01:30');

// Loan distribution
Schedule::command('distribute:loan')->dailyAt('02:00');

// Jackpot distribution
Schedule::command('distribute:jackpot')->dailyAt('03:00');
Schedule::command('distribute:jackpot --weekly')->weeklyOn(1, '03:30');

// Jackpot user enrollment
Schedule::command('jackpot:join-users')->dailyAt('22:00');
Schedule::command('jackpot:join-users --weekly')->weeklyOn(1, '22:30');

// E-Bike income
Schedule::command('distribute:ebike --type=daily')->dailyAt('04:00');
Schedule::command('distribute:ebike --type=binary-match')->dailyAt('04:30');
Schedule::command('distribute:ebike --type=binary-income')->dailyAt('05:00');

// Tour income
Schedule::command('distribute:tour --type=daily')->dailyAt('05:30');
Schedule::command('distribute:tour --type=binary-match')->dailyAt('06:00');

// Elite income
Schedule::command('distribute:elite --type=daily')->dailyAt('06:30');
Schedule::command('distribute:elite --type=daily-new')->dailyAt('07:00');
Schedule::command('distribute:elite --type=binary-match')->dailyAt('07:30');
Schedule::command('distribute:elite --type=binary-income')->dailyAt('08:00');

// Eligibility checks
Schedule::command('check:eligibility --type=ebike')->dailyAt('09:00');
Schedule::command('check:eligibility --type=recharge-trip')->dailyAt('09:30');

// Gold price
Schedule::command('fetch:gold-price')->everyThirtyMinutes();
