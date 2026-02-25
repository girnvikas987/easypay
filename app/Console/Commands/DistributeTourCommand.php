<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helper\Distribute;

class DistributeTourCommand extends Command
{
    protected $signature = 'distribute:tour {--type=daily : daily or binary-match}';
    protected $description = 'Distribute Tour income';

    public function handle()
    {
        $type = $this->option('type');

        if ($type === 'binary-match') {
            $this->info('Running Tour binary matching...');
            Distribute::distrbuteTourBinaryMacth();
        } else {
            $this->info('Running Tour daily income...');
            Distribute::tourDailyIncome();
        }

        $this->info('Tour distribution completed.');
        return Command::SUCCESS;
    }
}
