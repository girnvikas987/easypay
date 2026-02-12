<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Ichtrojan\Otp\Otp;
use App\Models\User;
use Validator;
use App\Jobs\SendOtpMail;
use App\Mail\SendOTP;
use Illuminate\Support\Facades\Mail;

class OtpController extends Controller
{
     public function generateOtpold(Request $request)
     {
        $mobile = $request->mobile;
        $validator = Validator::make($request->all(),[ 
            'mobile' => ['required', 'string', 'max:255', 'exists:users,mobile'], 
        ]);
        
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 200);

        }
        $mpin = $request->type;
        
        $user = User::where('mobile',$mobile)->first(); // Use Auth::user() to get the authenticated user
        if($user)
        {
            
            // Mail::to('anilsaini0663@gmail.com')->send(new SendOTP('numeric'));
         
            $otp = (new Otp)->generate($user->mobile, 'numeric', 6, 15);
             
            $otpToken = $otp->token;
            
            $apiKey = "71a9b0fbe3cb414583372e7c5664a5b4";
             if($mpin == 'mpin'){
                 $msg =  "OTP%20for%20your%20forgot%20M-Pin%20is%20$otpToken%2CPlease%20%20Enter%20this%20OTP%20to%20reset%20m-pin";
             }else{
                 $msg =  "OTP%20for%20your%20forgot%20password%20is%20$otpToken%2CPlease%20%20Enter%20this%20OTP%20to%20reset%20password";
             }
            
            
            // Initialize cURL session
            $ch = curl_init();
            
            // Set the URL and other options
            curl_setopt($ch, CURLOPT_URL, "http://whatsapp.click4bulksms.in/wapp/api/send?apikey=$apiKey&mobile=$mobile&msg=$msg");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            
            // Execute the request
            $response = curl_exec($ch);
            
            // Check for errors
            if (curl_errno($ch)) {
                 
                 $response = [
                        'success' => false,
                        'data' => curl_error($ch),
                        'message' => "otp Failed!."
                    ];
            } else {
                // Print the response
                $result =  json_decode($response,true);
               if($result['status'] =='success'){
                   $response = [
                        'success' => true,
                        'data' => '',
                        'message' => "otp Generated successfully."
                    ];
               }else{
                   $response = [
                        'success' => false,
                        'data' =>  '',
                        'message' => $result['errormsg']
                    ];
               }
            
            } 
            
            return response()->json($response, 200);
        }
        else
        {
            $response = [
                'success' => false,
                'message' => "Unauthorised Mobile!"
            ];
            return response()->json($response, 200);
        }
     

    }



    public function generateOtp(Request $request){ 

        $mobile = $request->mobile;
        $validator = Validator::make($request->all(),[ 
            'mobile' => ['required', 'string', 'max:255', 'exists:users,mobile'], 
        ]);
    
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 200);

        }
        $mpin = $request->type;
        
        $user = User::where('mobile',$mobile)->first(); // Use Auth::user() to get the authenticated user
        if($user)
        {
            
            // Mail::to('anilsaini0663@gmail.com')->send(new SendOTP('numeric'));
         
            $otp = (new Otp)->generate($user->mobile, 'numeric', 6, 15);
             
            $otpToken = $otp->token;
            
            $apiKey = "71a9b0fbe3cb414583372e7c5664a5b4";
             if($mpin == 'mpin'){
                 $msg =  "Forgot%20mpin%20otp%20$otpToken%20WELCOME%20JOY%20TOURS%20AND%20TRAVELS%20OPC%20PVT.LTD%0A";
             }else{
                 $msg =  "Forgot%20password%20otp%20$otpToken%20WELCOME%20JOY%20TOURS%20AND%20TRAVELS%20OPC%20PVT.LTD";
             }

            $curl = curl_init();
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://smsmaa.com/SMS_API/sendsms.php?username=welcomejoykrl&password=KRL999&mobile='.$mobile.'&sendername=WELJOY&message='.$msg.'&routetype=1',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            
            $response = curl_exec($curl);

            curl_close($curl);


  
            if ($response) {
                    $responseArray = explode(";", $response);
                    $status = "";
                    $remark = "";
                    $guid = ""; // Initialize GUID variable
                    
                    // Iterate through each element of the response array
                    foreach ($responseArray as $element) {
                        // Split each element by colon to separate key and value
                        $pair = explode(":", $element, 2); // Limit the split to 2 parts to handle values containing colons
                
                        // Extract key and value, trim whitespace
                        $key = trim($pair[0]);
                        $value = trim($pair[1]);
                
                        // Check if the key is "Status"
                        if ($key === "Status") {
                            $status = $value;
                        }
                
                        // Check if the key is "Remark"
                        if ($key === "Remark") {
                            $remark = $value;
                        }
                
                        // Check if the key is "GUID"
                        if ($key === "GUID") {
                            $guid = $value;
                        }
                    }

      
                    if($status == 1){
                        
                        $responsed = [
                            'success' => true,
                            'data' => '',
                            'message' => "otp Generated successfully."
                        ];
                    }else{

                        $responsed = [
                            'success' => false,
                            'data' =>  '',
                            'message' => $remark
                        ];
                    
                    }
            }else{

                $responsed = [
                    'success' => false,
                    'data' =>  '',
                    'message' => 'Something Went wrong'
                ];
               
            }

            return response()->json($responsed, 200);

        } else{
            $response = [
                'success' => false,
                'message' => "Unauthorised Mobile!"
            ];
            return response()->json($response, 200);
        }
    }
}
