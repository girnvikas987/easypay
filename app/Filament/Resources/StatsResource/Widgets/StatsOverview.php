<?php

namespace App\Filament\Resources\StatsResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Models\User;
// use App\Models\User;
class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::all()->count()),
            Stat::make('Active Users', User::where('active_status',1)->count()),
            Stat::make('Inactive Users', User::where('active_status',0)->count()),
            
            
        ];
    }
}
