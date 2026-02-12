<?php

namespace App\Filament\Resources\WalletTypeResource\Pages;

use App\Filament\Resources\WalletTypeResource;
use App\Models\WalletType;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletType extends CreateRecord
{
    protected static string $resource = WalletTypeResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        //insert the student

        $record =   WalletType::create($data);
        if($data['type']=='income'){
            if (!Schema::hasColumn('incomes', $data['slug']))
            {
                Schema::table('incomes', function (Blueprint $table) use ($data) {
                    $table->double($data['slug'], 15, 8)->nullable()->default(0);
                });
                Schema::table('daily_incomes', function (Blueprint $table) use ($data) {
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


        return $record;
     }
}
