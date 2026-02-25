<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helper\Distribute;

class CheckEligibilityCommand extends Command
{
    protected $signature = 'check:eligibility {--type=ebike : ebike or recharge-trip}';
    protected $description = 'Check user eligibility for E-Bike or Recharge Trip';

    public function handle()
    {
        $type = $this->option('type');

        if ($type === 'recharge-trip') {
            $this->info('Checking recharge trip eligibility...');
            Distribute::IsRechargeTripEligible();
        } else {
            $this->info('Checking E-Bike eligibility...');
            Distribute::IsEbikeEligible();
        }

        $this->info('Eligibility check completed.');
        return Command::SUCCESS;
    }
}
