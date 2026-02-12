<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

class MatchPassword implements ValidationRule
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

        // Retrieve the user by username
        //$user = User::where('username', $username)->first();
         
        // Check if the user exists and the provided password matches
        if(!($user && Hash::check($value, $user->password))){
            $fail("The provided password is incorrect!");

        }
    }
}
