<?php

namespace App\Filament\Resources\PlanRoiResource\Pages;

use App\Filament\Resources\PlanRoiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPlanRois extends ListRecords
{
    protected static string $resource = PlanRoiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
