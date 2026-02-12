<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Withdrawal;


class WithdrawCheck implements ValidationRule
{

    public $request;
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

     public function __construct($request)
    {
        $this->request = $request;
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        $user = $this->request->user();
          
        //$kyc = $user->kyc();
        $bank = $user->bank();
        //$pan_status = $kyc->pan_status;
        $bank_status = $kyc->bank_status;
       
       
        if($bank_status == 0){
            $fail("Please Add Account First!");
     
        }
     
        // if($pan_status == 0){
             
        //         $fail("Please Complete Your Kyc!");
            
        // }elseif($bank_status == 0){
        //     $fail("Please Add Account First!");
     
        // }
                
    }
}
