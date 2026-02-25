<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helper\RoiDistribute;

class DistributeRoiCommand extends Command
{
    protected $signature = 'distribute:roi {--type=standard : standard or four}';
    protected $description = 'Process ROI income distribution';

    public function handle()
    {
        $type = $this->option('type');

        if ($type === 'four') {
            $this->info('Running ROI 4x closing...');
            RoiDistribute::roiFourClosing();
        } else {
            $this->info('Running ROI standard closing...');
            RoiDistribute::roiClosing();
        }

        $this->info('ROI distribution completed.');
        return Command::SUCCESS;
    }
}
