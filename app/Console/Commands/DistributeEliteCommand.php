<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helper\Distribute;

class DistributeEliteCommand extends Command
{
    protected $signature = 'distribute:elite {--type=daily : daily, daily-new, binary-match, or binary-income}';
    protected $description = 'Distribute Elite income';

    public function handle()
    {
        $type = $this->option('type');

        switch ($type) {
            case 'daily-new':
                $this->info('Running Elite new daily income...');
                Distribute::eliteDailynewIncome();
                break;
            case 'binary-match':
                $this->info('Running Elite binary matching...');
                Distribute::distrbuteEliteBinaryMacth();
                break;
            case 'binary-income':
                $this->info('Running Elite binary income distribution...');
                Distribute::distrbuteEliteBinaryIncome();
                break;
            default:
                $this->info('Running Elite daily income...');
                Distribute::eliteDailyIncome();
                break;
        }

        $this->info('Elite distribution completed.');
        return Command::SUCCESS;
    }
}
