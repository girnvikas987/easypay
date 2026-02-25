<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helper\Distribute;

class ClearWithdrawRequestsCommand extends Command
{
    protected $signature = 'withdraw:clear-requests {--confirm : Confirm the destructive operation}';
    protected $description = 'Clear processed withdrawal requests (DESTRUCTIVE - truncates table)';

    public function handle()
    {
        if (!$this->option('confirm')) {
            $this->error('This command TRUNCATES the withdrawal_requests table.');
            $this->error('Run with --confirm to proceed: php artisan withdraw:clear-requests --confirm');
            return Command::FAILURE;
        }

        if (!$this->confirm('Are you sure you want to truncate the withdrawal_requests table? This cannot be undone.')) {
            $this->info('Operation cancelled.');
            return Command::SUCCESS;
        }

        $this->warn('Clearing withdrawal requests...');
        Distribute::clrRequest();
        $this->info('Withdrawal requests cleared.');
        return Command::SUCCESS;
    }
}
