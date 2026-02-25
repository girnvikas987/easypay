<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helper\Distribute;

class DistributeLoanCommand extends Command
{
    protected $signature = 'distribute:loan';
    protected $description = 'Process loan payment distribution';

    public function handle()
    {
        $this->info('Running loan distribution...');
        Distribute::loanDistribution();
        $this->info('Loan distribution completed.');
        return Command::SUCCESS;
    }
}
