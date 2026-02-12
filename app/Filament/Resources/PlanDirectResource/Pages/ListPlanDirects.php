<?php

namespace App\Filament\Resources\PlanDirectResource\Pages;

use App\Filament\Resources\PlanDirectResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPlanDirects extends ListRecords
{
    protected static string $resource = PlanDirectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
