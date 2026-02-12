<?php

namespace App\Filament\Resources\HeadlineResource\Pages;

use App\Filament\Resources\HeadlineResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHeadlines extends ListRecords
{
    protected static string $resource = HeadlineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
