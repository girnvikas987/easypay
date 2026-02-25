<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helper\Distribute;

class DistributeEbikeCommand extends Command
{
    protected $signature = 'distribute:ebike {--type=daily : daily, binary-match, or binary-income}';
    protected $description = 'Distribute E-Bike income';

    public function handle()
    {
        $type = $this->option('type');

        switch ($type) {
            case 'binary-match':
                $this->info('Running E-Bike binary matching...');
                Distribute::distrbuteEbikeBinaryMacth();
                break;
            case 'binary-income':
                $this->info('Running E-Bike binary income distribution...');
                Distribute::distrbuteEbikeBinaryIncome();
                break;
            default:
                $this->info('Running E-Bike daily income...');
                Distribute::ebikeDailyIncome();
                break;
        }

        $this->info('E-Bike distribution completed.');
        return Command::SUCCESS;
    }
}
