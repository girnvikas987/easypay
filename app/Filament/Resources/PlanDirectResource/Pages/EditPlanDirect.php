<?php

namespace App\Filament\Resources\PlanDirectResource\Pages;

use App\Filament\Resources\PlanDirectResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPlanDirect extends EditRecord
{
    protected static string $resource = PlanDirectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
