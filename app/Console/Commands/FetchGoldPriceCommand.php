<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helper\Distribute;

class FetchGoldPriceCommand extends Command
{
    protected $signature = 'fetch:gold-price';
    protected $description = 'Fetch live gold rates';

    public function handle()
    {
        $this->info('Fetching live gold rates...');
        Distribute::fetchgold();
        $this->info('Gold price fetch completed.');
        return Command::SUCCESS;
    }
}
