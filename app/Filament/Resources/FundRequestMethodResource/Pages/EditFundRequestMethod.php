<?php

namespace App\Filament\Resources\FundRequestMethodResource\Pages;

use App\Filament\Resources\FundRequestMethodResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFundRequestMethod extends EditRecord
{
    protected static string $resource = FundRequestMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
