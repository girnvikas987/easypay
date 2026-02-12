<?php

namespace App\Filament\Resources\UserFundRequestResource\Pages;

use App\Filament\Resources\UserFundRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Closure;
use Doctrine\DBAL\Schema\Table;
use Filament\Tables\Table as TablesTable;

class ListUserFundRequests extends ListRecords
{
    protected static string $resource = UserFundRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    protected function makeTable():  TablesTable
{
    return parent::makeTable()->recordUrl(null);
}

}
