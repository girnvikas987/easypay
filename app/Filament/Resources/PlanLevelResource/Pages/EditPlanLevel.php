<?php

namespace App\Filament\Resources\PlanLevelResource\Pages;

use App\Filament\Resources\PlanLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPlanLevel extends EditRecord
{
    protected static string $resource = PlanLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
