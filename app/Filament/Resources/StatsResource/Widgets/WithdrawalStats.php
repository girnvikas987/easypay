<?php

namespace App\Filament\Resources\StatsResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Models\User;
use App\Models\Withdrawal;

// use App\Models\User;
class WithdrawalStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Withdrawal', Withdrawal::sum('amount')),
            Stat::make('Success Withdrawal', Withdrawal::where('status',1)->sum('amount')),
            Stat::make('Pending Withdrawal', Withdrawal::where('status',0)->sum('amount')),
            Stat::make('Failed Withdrawal', Withdrawal::where('status',4)->sum('amount')),
            
            
        ];
    }
}
