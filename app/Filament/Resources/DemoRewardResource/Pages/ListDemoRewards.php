<?php

namespace App\Filament\Resources\DemoRewardResource\Pages;

use App\Filament\Resources\DemoRewardResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDemoRewards extends ListRecords
{
    protected static string $resource = DemoRewardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
