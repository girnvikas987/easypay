<?php

namespace App\Filament\Resources\StatsResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Investment;
use App\Models\Transaction;

class BusinessOverview extends BaseWidget
{
   protected function getColumns(): int
    {
        return 2;
    }
    protected function getStats(): array
    {
        return [
            Stat::make('Total Investment', Investment::where('status',1)->sum('amount'))
            ->description('1% increase')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('warning')
            ->chart([17, 2, 0, 13, 5, 4, 17]),
            Stat::make('Total Income', Transaction::whereNotNull('income')->sum('amount'))
            ->description('1% increase')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('warning')
            ->chart([7, 2, 10, 3, 15, 4, 17]),
        ];
    }
}
