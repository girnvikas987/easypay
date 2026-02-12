<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;

class EditTransaction extends EditRecord
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
    protected function handleRecordUpdate(Model $record, array $data): Model
    {   
        $pre_req = Transaction::where('id',$record->id)->first();
        
        if($data['use_wallet']==1){
            if($data['type']=='credit'){   
                if($pre_req->status!=1 && $data['status']==1){
                    Wallet::where('user_id',$data['user_id'])->increment($data['wallet'],$data['amount']); 
                }
                if($pre_req->status==1 && $data['status']!=1 ){
                    Wallet::where('user_id',$data['user_id'])->decrement($data['wallet'],$data['amount']);
                }                        
            }else{
                if($pre_req->status!=1 && $data['status']==1){
                    Wallet::where('user_id',$data['user_id'])->decrement($data['wallet'],$data['amount']);
                }
                if($pre_req->status==1 && $data['status']!=1 ){
                    Wallet::where('user_id',$data['user_id'])->increment($data['wallet'],$data['amount']); 
                }                
            }
        }        
         
        $record->update($data);
    
        return $record;
    }
}
