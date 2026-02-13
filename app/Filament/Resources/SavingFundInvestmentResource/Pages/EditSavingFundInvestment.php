<?php

namespace App\Filament\Resources\SavingFundInvestmentResource\Pages;

use App\Filament\Resources\SavingFundInvestmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSavingFundInvestment extends EditRecord
{
    protected static string $resource = SavingFundInvestmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
