<?php

namespace App\Filament\Resources\UserFundRequestResource\Pages;

use App\Filament\Resources\UserFundRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\UserFundRequest;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Model;


class EditUserFundRequest extends EditRecord
{
    protected static string $resource = UserFundRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function handleRecordUpdate(Model $record, array $data): Model
    {   
        $pre_req = UserFundRequest::where('id',$record->id)->first();
        //Wallet::where('user_id',$data['user_id'])->increment('fund_wallet',$data['amount']); 
        // if($pre_req->status!=1 && $data['status']==1){
        //     Wallet::where('user_id',$data['user_id'])->increment('fund_wallet',$data['amount']); 
        // }
        // if($pre_req->status!=0 && $data['status']==0 ){
        //     Wallet::where('user_id',$data['user_id'])->decrement('fund_wallet',$data['amount']);
        // }         
        
        if ($pre_req->status != 1 && $data['status'] == 1) {
            // Status changed to Approved -> Increase wallet
            Wallet::where('user_id', $data['user_id'])->increment($data['wallet'], $data['amount']); 
        }
        
        if ($pre_req->status != 2 && $data['status'] == 2) {
            // Status changed to Cancelled -> Decrease wallet
           // Wallet::where('user_id', $data['user_id'])->decrement('fund_wallet', $data['amount']);
        }
        if ($pre_req->status != 4 && $data['status'] == 4) {
            // Status changed to Cancelled -> Decrease wallet
            Wallet::where('user_id', $data['user_id'])->decrement($data['wallet'], $data['amount']);
        }
         
        $record->update($data);
    
        return $record;
    }

    protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}
}
