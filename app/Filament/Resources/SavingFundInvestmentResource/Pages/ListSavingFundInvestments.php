<?php

namespace App\Filament\Resources\SavingFundInvestmentResource\Pages;

use App\Filament\Resources\SavingFundInvestmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSavingFundInvestments extends ListRecords
{
    protected static string $resource = SavingFundInvestmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
