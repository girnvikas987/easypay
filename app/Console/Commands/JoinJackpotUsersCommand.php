<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helper\Distribute;

class JoinJackpotUsersCommand extends Command
{
    protected $signature = 'jackpot:join-users {--weekly : Join weekly jackpot instead of daily}';
    protected $description = 'Join eligible users to jackpot draw';

    public function handle()
    {
        if ($this->option('weekly')) {
            $this->info('Joining users to weekly jackpot draw...');
            Distribute::joinJackpotDrawUsersWeekly();
        } else {
            $this->info('Joining users to daily jackpot draw...');
            Distribute::joinJackpotDrawUsers();
        }

        $this->info('Jackpot user join completed.');
        return Command::SUCCESS;
    }
}
