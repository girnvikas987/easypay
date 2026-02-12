<?php

namespace App\Filament\Resources\RechargeResource\Pages;

use App\Filament\Resources\RechargeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRecharge extends EditRecord
{
    protected static string $resource = RechargeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
