<?php

namespace App\Filament\Resources\PlanLevelResource\Pages;

use App\Filament\Resources\PlanLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPlanLevels extends ListRecords
{
    protected static string $resource = PlanLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
