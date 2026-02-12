<?php

namespace App\Filament\Resources\RechargeResource\Pages;

use App\Filament\Resources\RechargeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRecharges extends ListRecords
{
    protected static string $resource = RechargeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
