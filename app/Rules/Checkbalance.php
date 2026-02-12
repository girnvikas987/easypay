<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;

class Checkbalance implements ValidationRule
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
            $fail('Insufficient Fund in wallet');
        }
    }
}
