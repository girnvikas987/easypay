<?php

namespace App\Filament\Resources\OttCredentialResource\Pages;

use App\Filament\Resources\OttCredentialResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOttCredentials extends ListRecords
{
    protected static string $resource = OttCredentialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
