<?php

namespace App\Rules;

use App\Models\EbikeInvestment;
use App\Models\TourInvestment;
use App\Models\TourPackage;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class ValidateTourPackage implements ValidationRule
{
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
        $request = $this->request;
        $check_exists = TourPackage::where('id',$this->request->package)->first();
        $wallettype = $request->wallet_type;
        if($check_exists){
            $minReq=$check_exists->min;
            $maxReq=$check_exists->max; 
                # code...
                if($this->request->amount!=''){
                    if($this->request->amount<$minReq){
                        $fail("Amount Should Be minimum $minReq .");
                    } 
                    if($this->request->amount>$maxReq){
                        $fail("Amount Should Be maximum $maxReq .");
                    }
                    if ($request->user()->wallet->$wallettype<$this->request->amount) {
                        $fail('Insufficient Fund in wallet');
                    }
                    
                }else{
                    $fail('Invalid Amount!');
                }
             
            

            if($check_exists->no_of_time=="once")
            {                
                if($request->mobile){
                    $tx_user = User::where('mobile',$request->mobile)->first();
                    if($tx_user){
                        $chkinvestmentExists = TourInvestment::where('package_id',$check_exists->id)->where('user_id',$tx_user->id)->where('status',1)->first();
                        if($chkinvestmentExists){
                            $fail('You can buy this package only once.');
                        }
                    }else{
                        $fail('User not Exists.');
                    }
                }else{
                    $fail('Enter User.');

                }
                

            }

            if($check_exists->pre_reqired==1)
            {   
                $oid = $check_exists->id;
                $pre_id = $oid-1;
                if($pre_id>=1){

                    $userInvestments = $request->user()->investments;
                    
                    if($request->mobile){
                        $tx_user = User::where('mobile',$request->mobile)->first();
                        if($tx_user){
                            $chkinvestmentExists = EbikeInvestment::where('package_id',$pre_id)->where('user_id',$tx_user->id)->where('status',1)->first();
                            if(!$chkinvestmentExists){
                                $fail('Buy Previous Package first');
                            }
                        }else{
                            $fail('User not Exists.');
                        }
                    }else{
                        $fail('Enter User.');
    
                    }
                    
                }
                
            }

        }else{
            $fail('Invalid Package!');
        }
    }
}
