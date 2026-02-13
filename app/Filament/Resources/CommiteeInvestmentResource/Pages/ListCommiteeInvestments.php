<?php

namespace App\Filament\Resources\CommiteeInvestmentResource\Pages;

use App\Filament\Resources\CommiteeInvestmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCommiteeInvestments extends ListRecords
{
    protected static string $resource = CommiteeInvestmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
