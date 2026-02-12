<?php

namespace App\Filament\Resources\WalletTypeResource\Pages;

use App\Filament\Resources\WalletTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditWalletType extends EditRecord
{
    protected static string $resource = WalletTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {   
        if($data['slug']!=$record->slug){    
            if($data['type']=='income'){                
                if (!Schema::hasColumn('incomes', $data['slug']))
                {
                    Schema::table('incomes', function (Blueprint $table) use ($data) {
                        $table->double($data['slug'], 15, 8)->nullable()->default(0);
                    });
                }
            }else{
                if (!Schema::hasColumn('wallets', $data['slug']))
                {
                    Schema::table('wallets', function (Blueprint $table) use ($data) {
                        $table->double($data['slug'], 15, 8)->nullable()->default(0);
                    });
                }
            } 
        }

        $record->update($data);
    
        return $record;
    }
}
