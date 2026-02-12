<?php

namespace App\Rules;

use App\Models\Investment;
use App\Models\Package;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class ValidatePackage implements ValidationRule
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
        $wallet_type = $request->wallet_type;
        $check_exists = Package::where('id', $this->request->package)->first();
    
        if ($check_exists) {
            $minReq = $check_exists->min;
            $maxReq = $check_exists->max;
    
            // --- Funds validation ---
            if ($check_exists->type == 'manual') {
                if ($this->request->amount != '') {
                    if ($this->request->amount < $minReq) {
                        $fail("Amount Should Be minimum $minReq .");
                    }
                    if ($this->request->amount > $maxReq) {
                        $fail("Amount Should Be maximum $maxReq .");
                    }
                    if ($request->user()->wallet->$wallet_type < $this->request->amount) {
                        $fail('Insufficient Fund in wallet');
                    }
                } else {
                    $fail('Invalid Amount!');
                }
            } else {
                if ($request->user()->wallet->$wallet_type < $check_exists->amount) {
                    $fail('Insufficient Fund in wallet');
                }
            }
    
            // --- User check ---
            if ($request->mobile) {
                $tx_user = User::where('mobile', $request->mobile)->first();
                if (!$tx_user) {
                    $fail('User not Exists.');
                    return;
                }
            } else {
                $fail('Enter User.');
                return;
            }
    
            $userId = $tx_user->id;
    
            // --- Special package rules ---
            $has700 = Investment::where('package_id', 1)->where('user_id', $userId)->where('status', 1)->exists();
            $has1200 = Investment::where('package_id', 2)->where('user_id', $userId)->where('status', 1)->exists();
            $has500 = Investment::where('package_id', 3)->where('user_id', $userId)->where('status', 1)->exists();
    
            if ($check_exists->id == '3') {
                if (!$has700) {
                    $fail('You must first purchase 700 Prime before upgrading.');
                }
                if ($has1200) {
                    $fail('You cannot purchase 500 Upgrade after buying 1200 Super Prime.');
                }
            }
    
            if ($check_exists->id == '1') {
                if ($has1200) {
                    $fail('You already have 1200 Super Prime. Cannot buy 700.');
                }
            }
    
            if ($check_exists->id == '2') {
                if ($has700 || $has500) {
                    $fail('You cannot buy 1200 after purchasing 700 or 500.');
                }
            }
    
            // --- Only once packages ---
            if ($check_exists->no_of_time == "once") {
                $chkinvestmentExists = Investment::where('package_id', $check_exists->id)
                    ->where('user_id', $userId)
                    ->where('status', 1)
                    ->first();
    
                if ($chkinvestmentExists) {
                    $fail('You can buy this package only once.');
                }
            }
    
            // --- Pre-required package logic ---
            if ($check_exists->pre_reqired == 1) {
                $oid = $check_exists->id;
                $pre_id = $oid - 1;
                if ($pre_id >= 1) {
                    $chkinvestmentExists = Investment::where('package_id', $pre_id)
                        ->where('user_id', $userId)
                        ->where('status', 1)
                        ->first();
                    if (!$chkinvestmentExists) {
                        $fail('Buy Previous Package first');
                    }
                }
            }
        } else {
            $fail('Invalid Package!');
        }
    }

}
