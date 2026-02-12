<?php

namespace App\Filament\Resources\UserFundRequestResource\Pages;

use App\Filament\Resources\UserFundRequestResource;
use App\Models\UserFundRequest;
use App\Models\Wallet;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateUserFundRequest extends CreateRecord
{
    protected static string $resource = UserFundRequestResource::class;

 

    protected function handleRecordCreation(array $data): Model
    {
        // Check if the fund request already exists using the UTR number
        $pre_req = UserFundRequest::where('utr_number', $data['utr_number'])->first();
        if ($pre_req !== null) {
                return $pre_req;
        }
        // If the record exists, apply wallet logic
        
            // If the previous status is not 'Approved' (1) and the new status is 'Approved', increment the wallet
           $transaction = UserFundRequest::create($data);

            // Then, update the wallet based on status
            if ((int) $data['status'] === 1) {
                Wallet::where('user_id', $data['user_id'])->increment('fund_wallet', $data['amount']);
            }

            if ((int) $data['status'] === 4) {
                Wallet::where('user_id', $data['user_id'])->decrement('fund_wallet', $data['amount']);
            }

            return $transaction;
  

    }
    

}
