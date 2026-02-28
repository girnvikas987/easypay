<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;
use Ichtrojan\Otp\Otp;

class ValidateOtp implements ValidationRule
{
    protected $mobile;

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function __construct($mobile)
    {
       $this->mobile = $mobile;
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {   
         
         
        if (empty($this->mobile)) {
            $fail('Mobile number is required for OTP validation');
            return;
        }
        $vll = (new Otp)->validate($this->mobile, $value);
        if ($vll->status == false) {
            $fail('OTP is not valid');
        }
        
    }
}
