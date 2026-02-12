<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use App\Models\Wallet;
use App\Models\Transaction;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;
    
    protected function handleRecordCreation(array $data): Model
    {
        //insert the student

        $record =   Transaction::create($data);
        

        if($data['use_wallet']==1){

            if($data['type']=='credit'){           
                Wallet::where('user_id',$data['user_id'])->increment($data['wallet'],$data['amount']);            
            }else{
                Wallet::where('user_id',$data['user_id'])->decrement($data['wallet'],$data['amount']);
            }
        }
       


        return $record;
     }
}
