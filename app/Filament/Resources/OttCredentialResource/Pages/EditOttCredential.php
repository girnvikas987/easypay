<?php

namespace App\Filament\Resources\OttCredentialResource\Pages;

use App\Filament\Resources\OttCredentialResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOttCredential extends EditRecord
{
    protected static string $resource = OttCredentialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
