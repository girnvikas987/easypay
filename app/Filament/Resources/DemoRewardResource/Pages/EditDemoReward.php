<?php

namespace App\Filament\Resources\DemoRewardResource\Pages;

use App\Filament\Resources\DemoRewardResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDemoReward extends EditRecord
{
    protected static string $resource = DemoRewardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
