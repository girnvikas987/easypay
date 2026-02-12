<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\Listeners\TopupListener;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Validator;
use App\Rules\ValidateOtp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
class PasswordController extends Controller
{
    ////////////////////////////////////////////////// Api Start ///////////////////////////////////////////////////////////  
    public function updatePassword(Request $request)
    {   
      
        $userId=$request->user()->id;
        $dir = User::find($userId);
        if($dir){
            
            $validator = Validator::make($request->all(),[
                'current_password' => ['required', 'current_password'],
                'password' => ['required', Password::defaults(), 'confirmed']
            ]);
            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->errors()
                ];
                return response()->json($response, 200);

            }
    
            $request->user()->update([
                'password' => Hash::make($request->password),
            ]);
    
            $response = [
                'success' => true,
                'message' => 'Password updated Successfully.',
            ];
        }else{
            $response = [
                'success' => false,
                'message' => 'User Not Exists!',
            ];
        }
        
        return response()->json($response, 200);
        
    }
    
    
    public function generateNewPassword(Request $request){
        
          $mobile=$request->mobile;
        
            $valida['otp']=['required',];
            $validator = Validator::make($request->all(),[
                'mobile' => ['required', 'string', 'max:255', 'exists:users,mobile'],
                'otp' => ['required', 'numeric', 'min:6', new ValidateOtp($mobile)],
                'password' => ['required', 'min:6', 'confirmed']
            ]);
            
            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->errors()
                ];
                return response()->json($response, 200);

            }
            
            $userExists = User::where('mobile',$mobile)->first();
            if($userExists){
                $userExists->password = Hash::make($request->password);
                $userExists->save();
                $response = [
                    'success' => true,
                    'message' => "Password Change successfully."
                ];
                return response()->json($response, 200);
            
        }else{
            $response = [
                'success' => false,
                'message' => "Unauthorised Mobile!"
            ];
            return response()->json($response, 200);
        }
        
    }
    
    public function generateNewMpin(Request $request){
        
          $mobile=$request->mobile;
        
            $valida['otp']=['required',];
            $validator = Validator::make($request->all(),[
                'mobile' => ['required', 'string', 'max:255', 'exists:users,mobile'],
                'otp' => ['required', 'numeric', 'min:6', new ValidateOtp($mobile)],
                'mpin' => ['required', 'numeric','min:4'],
                'password_confirmation' => ['required', 'numeric','min:4','same:mpin']
            ]);
            
            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->errors()
                ];
                return response()->json($response, 200);

            }
            
            $userExists = User::where('mobile',$mobile)->first();
            if($userExists){
                $userExists->transaction_pin = $request->mpin;
                $userExists->save();
                $response = [
                    'success' => true,
                    'message' => "M-Pin Change successfully."
                ];
                return response()->json($response, 200);
            
        }else{
            $response = [
                'success' => false,
                'message' => "Unauthorised Mobile!"
            ];
            return response()->json($response, 200);
        }
        
    }
  
   
}
