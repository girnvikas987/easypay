<?php

namespace App\Filament\Resources\PlanRewardResource\Pages;

use App\Filament\Resources\PlanRewardResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPlanRewards extends ListRecords
{
    protected static string $resource = PlanRewardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
