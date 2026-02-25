<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helper\Distribute;

class DistributeRoyaltyCommand extends Command
{
    protected $signature = 'distribute:royalty {--type=standard : standard or recharge}';
    protected $description = 'Distribute royalty income';

    public function handle()
    {
        $type = $this->option('type');

        if ($type === 'recharge') {
            $this->info('Running recharge royalty distribution...');
            Distribute::RoyaltyRechargeDistribution();
        } else {
            $this->info('Running standard royalty distribution...');
            Distribute::RoyaltyDistribution();
        }

        $this->info('Royalty distribution completed.');
        return Command::SUCCESS;
    }
}
