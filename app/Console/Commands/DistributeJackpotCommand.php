<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helper\Distribute;

class DistributeJackpotCommand extends Command
{
    protected $signature = 'distribute:jackpot {--weekly : Run weekly jackpot instead of daily}';
    protected $description = 'Distribute jackpot prizes';

    public function handle()
    {
        if ($this->option('weekly')) {
            $this->info('Running weekly jackpot distribution...');
            Distribute::JackpotWeeklyDistribution();
        } else {
            $this->info('Running daily jackpot distribution...');
            Distribute::JackpotDistribution();
        }

        $this->info('Jackpot distribution completed.');
        return Command::SUCCESS;
    }
}
