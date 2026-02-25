<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helper\Distribute;

class ClearDailyIncomeCommand extends Command
{
    protected $signature = 'income:clear-daily';
    protected $description = 'Clear daily income records';

    public function handle()
    {
        $this->info('Clearing daily income...');
        Distribute::clearDailyInmcome();
        $this->info('Daily income cleared.');
        return Command::SUCCESS;
    }
}
