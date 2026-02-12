<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Wallet;
use App\Models\RechargeInvestment;
use Illuminate\Support\Facades\Auth;

class CheckRechargeBalance implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function __construct($wallet)
    {
        $this->wallet = $wallet;
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {   
        $wlt=$this->wallet;
       
        $usrid=Auth::user()->id;

        $wlts=Wallet::where('user_id',$usrid)->first();
            if ($value>$wlts->$wlt) {
                $fail('Insufficient Fund in  wallet');
            }
       // $RechargeExists = RechargeInvestment::where('user_id',$usrid)->where('status','1')->first();


        // if($RechargeExists){
        //     $wlts=Wallet::where('user_id',$usrid)->first();
        //         if($wlts->$wlt < $value){
        //              if ($value > $wlts->main_wallet) {
        //                  $fail('Insufficient Fund in Both wallet');
        //              }
        //         } 
        // }else{
        //     $wlts=Wallet::where('user_id',$usrid)->first();
        //     if ($value>$wlts->main_wallet) {
        //         $fail('Insufficient Fund in E wallet');
        //     }
        // }
    }
}
