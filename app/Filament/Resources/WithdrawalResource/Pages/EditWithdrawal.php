<?php

namespace App\Filament\Resources\WithdrawalResource\Pages;

use App\Filament\Resources\WithdrawalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Wallet;
use App\Models\Withdrawal;
use Illuminate\Database\Eloquent\Model;

class EditWithdrawal extends EditRecord
{
    protected static string $resource = WithdrawalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
    
    protected function handleRecordUpdate(Model $record, array $data): Model
    {   
        $pre_req = Withdrawal::where('id',$record->id)->first();
                $payableAmnt = $pre_req->amount + $pre_req->tx_charge + $pre_req->tds_charge;
                if($pre_req->status!=4 && $data['status']==4){
                    Wallet::where('user_id',$record->user_id)->increment('main_wallet',$payableAmnt); 
                }
                     
        $record->update($data);
    
        return $record;
    }
}
