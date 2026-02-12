<?php

namespace App\Filament\Resources\PlanRewardResource\Pages;

use App\Filament\Resources\PlanRewardResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPlanReward extends EditRecord
{
    protected static string $resource = PlanRewardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
