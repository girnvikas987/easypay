<?php

namespace App\Filament\Resources\WalletTypeResource\Pages;

use App\Filament\Resources\WalletTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWalletTypes extends ListRecords
{
    protected static string $resource = WalletTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
