<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admin;
use App\Models\Team;
use App\Models\Wallet;
use App\Models\Income;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\DailyIncome;
use App\Models\PlanRefferalIncome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Validator; 
use Illuminate\Support\Str;
use App\Models\Otp as ModelOtp;
use Ichtrojan\Otp\Otp; 
use Spatie\Permission\Models\Role;
use App\Helper\Distribute;
use Illuminate\Support\Facades\Crypt;
use App\Jobs\SendSignUpMail;
use App\Models\Bank;
use App\Models\Wotp;
use Illuminate\Support\Carbon; 

// use Tzsk\Otp\Facades\Otp as wOtp;
class AuthController extends Controller
{
    public function register_new(Request $request)
    {

        $response = [
            'success' => false,
            'data' => '',
            'message' => "Please update your app from Play store!"
        ];

        return response()->json($response, 200);
        
        $validator = Validator::make($request->all(),[
            'sponsor' => ['required', 'string', 'max:255' , 'exists:users,mobile'],
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'min:10','max:10'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            // 'transaction_pin' => ['required', 'string', 'max:255'],
            'password' => ['required'],
            'c_password' => 'required|same:password',
        ]);
        
       

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 200);

        }

        $user = User::create([

            'name' => $request->name,
            'username' => 'MVP'.fake()->unique()->numberBetween(10000,99999),//$request->username,
            'mobile' => $request->mobile,
            // 'transaction_pin' => $request->transaction_pin,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $teamId = $user->id;

         

        $team = Team::create([
            'user_id' => $teamId,
             'sponsor' => $user->byMobile($request->sponsor)->id,
            //'sponsor' => $user->directs(),
        ]);
        $wallet = Wallet::create([
            'user_id' => $teamId,
              
            //'sponsor' => $user->directs(),
        ]);
        $wallet = Income::create([
            'user_id' => $teamId,
              
            //'sponsor' => $user->directs(),
        ]);
        $wallet = DailyIncome::create([
            'user_id' => $teamId,
              
            //'sponsor' => $user->directs(),
        ]);
        $this->addgen($teamId);
        //SendSignUpMail::dispatch($user);
        $success['token']=$user->createToken('MyApp')->plainTextToken;
        $success['username']=$user->username;
        $response = [
            'success' => true,
            'data' => $success,
            'message' => "User Register Successfully."
        ];
        return response()->json($response,200);

    }
    public function register(Request $request)
    {
        $defultregisterStatus = Setting::where('type','registration')->value('status');
        if($defultregisterStatus == '1'){
            
       
        $response = [
            'success' => false,
            'data' => '',
            'message' => "Please update your app from Play store!"
        ];

        return response()->json($response, 200);

        
        $validator = Validator::make($request->all(),[
            'sponsor' => ['required', 'numeric','exists:users,mobile'],
            'name' => ['required', 'string', 'regex:/^[A-Za-z\s]+$/', 'min:5', 'max:20'], 
            'mobile' => ['required', 'string', 'min:10','max:10','unique:'.User::class],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'pin_code' => ['required', 'string', 'max:20'],
            'transaction_pin' => ['required', 'string', 'min:4','max:4'],
            'password' => ['required'],
            'c_password' => 'required|same:password',
        ]);
        
       

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 200);

        }
        $mobile = $request->mobile;
        $otp = $request->otp;
        
        $exists = ModelOtp::where('identifier', $mobile)->where('token',$otp)->where('valid','1')->latest()->first();
          
         
        if (!$exists) {
             
             $response = [
                'success' => false,
                'message' => "OTP is not valid!"
            ];
            return response()->json($response, 200);
            
        }

        $user = User::create([ 
            'name' => $request->name,
            'username' => 'SPY'.fake()->unique()->numberBetween(10000,99999),//$request->username,
            'mobile' => $request->mobile,
            'transaction_pin' => $request->transaction_pin,
            'email' => $request->email,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postcode' => $request->pin_code,
            'password' => Hash::make($request->password),
        ]);
         
        $teamId = $user->id;
        $pass = $request->password;
         

        $team = Team::create([
            'user_id' => $teamId,
             'sponsor' => $user->byMobile($request->sponsor)->id,
            //'sponsor' => $user->directs(),
        ]);
        $wallet = Wallet::create([
            'user_id' => $teamId,
              
            //'sponsor' => $user->directs(),
        ]);
        $wallet = Income::create([
            'user_id' => $teamId,
              
            //'sponsor' => $user->directs(),
        ]);
        $wallet = DailyIncome::create([
            'user_id' => $teamId,
              
            //'sponsor' => $user->directs(),
        ]);
        
        
        // $transactions = new \stdClass();
        // $transactions->user_id = $teamId;
        // $transactions->amount = 11;
         $exists->delete();         
        
        $name = $user->name;
           
        $decoded_msg = urlencode($name);
        $this->addgen($teamId);


        
        $count =  Setting::where('type','register_app')->value('value');
        $count =$count +1;
        Setting::where('type', 'register_app')->update(['value' => $count]);
        Distribute::RefferalIncome($user);
         
        Distribute::GoldReferralIncome($team);
        //SendSignUpMail::dispatch($user);
        $msg = "Dear%20$decoded_msg%20Successfully%20completed%20Your%20Registration%20Your%20Register%20Mobile%20Number%20$mobile%20And%20Password%20$pass%0AContinue%20With%20S2PAY%20APP%20Thank";
        $this->sendRegisterMsg($msg,$mobile);
        $success['token']=$user->createToken('MyApp')->plainTextToken;
        $success['username']=$user->mobile;
        $success['date']=Carbon::parse($user->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s');
        $response = [
            'success' => true,
            'data' => $success,
            'message' => "User Register Successfully."
        ];


    }else{
        $response = [
            'success' => false,
            'data' => '',
            'message' => "Something Went wrong!"
        ];
    }


        return response()->json($response,200);

    }


    ////////////////////////////////////new and final regusrer /////////////////////////////////////////////////////////////////
    
    public function simpleRegister(Request $request)
    {
  
            
            $request->position = 1;
        // $response = [
        //     'success' => false,
        //     'data' => '',
        //     'message' => "Something Went wrong!"
        // ];

        // return response()->json($response, 200);

        $validator = Validator::make($request->all(),[
            'sponsor' => ['required', 'numeric','exists:users,mobile'],
            'name' => ['required', 'string'], 
            'email' => ['required', 'string', 'email', 'max:255','unique:'.User::class],
            'mobile' => ['required', 'string','unique:'.User::class], 
            'transaction_pin' => ['required', 'string', 'min:4','max:4'],
           /// 'position' => ['required', 'in:1,2'],
            // 'otp' => ['required', 'string', 'max:255'],
            'password' => ['required'], 
        ]);
        
      

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 200);

        }
        // $otp = $request->otp;
        $mobile = $request->mobile;

        
        // $exists = ModelOtp::where('identifier', $mobile)->where('token',$otp)->where('valid','1')->latest()->first();
          
         
        // if (!$exists) {
             
        //      $response = [
        //         'success' => false,
        //         'message' => "OTP is not valid!"
        //     ];
        //     return response()->json($response, 200);
            
        // }
    
        $user = User::create([ 
            'name' => $request->name,
            //'sponsor' => $request->sponsor,
            'username' => 'EDP'.fake()->unique()->numberBetween(100000,999999),//$request->username,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'kyc_status' => '1',
            'transaction_pin' => $request->transaction_pin, 
            'password' => Hash::make($request->password),
        ]);
     

        $teamId = $user->id;
        $pass = $request->password;
         
        $position = $request->position;

        $team = Team::create([
            'user_id' => $teamId,
            'sponsor' => $user->byMobile($request->sponsor)->id,
            'position' => $position,
        ]);
        
        $wallet = Wallet::create([
            'user_id' => $teamId,
              
            //'sponsor' => $user->directs(),
        ]);
        $wallet = Income::create([
            'user_id' => $teamId,
              
            //'sponsor' => $user->directs(),
        ]);
        $wallet = DailyIncome::create([
            'user_id' => $teamId,
              
            //'sponsor' => $user->directs(),
        ]);
       
        // $transactions = new \stdClass();
        // $transactions->user_id = $teamId;
        // $transactions->amount = 11;
        //  $exists->delete();         
        
        $name = $user->name;
      
        $decoded_msg = urlencode($name);
        $this->addgen($teamId);
      
           
        
        // $transactions = new \stdClass();
        // $transactions->user_id = $teamId;
        // $transactions->amount = 11;
         //$exists->delete();         
        
        $name = $user->name;
        
         
        // Distribute::GoldReferralIncomeDirect($team);

   
        // $count =  Setting::where('type','register_app')->value('value');
        // $count =$count +1;
        // $comm = 599; 
        // Transaction::create([
        //     'user_id' => $user->id,  
        //     'tx_user' => $user->id,
        //     'type' => 'credit',
        //     'tx_type' => 'income',
        //     'wallet' => 'bouns_wallet',
        //     'income' => 'bouns',
        //     'status' => 1,                        
        //     'amount' => $comm, 
        //     'charge' => $comm, 
        //     'tx_id'  => $user->id,
        //     'remark' => "Receive SIGNUP bouns  of amount Rs $comm from registration $name $mobile."
        // ]);

        
        
      
     
        // wallet::where('user_id', $user->id)->increment('bouns_wallet', $comm);
        // $decoded_msg = urlencode($name);
        $success['token']=$user->createToken('MyApp')->plainTextToken;
        $success['username']=$user->mobile;
        $success['date']=Carbon::parse($user->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s');
        // $this->voiceCall($user->mobile);
        $response = [
            'success' => true,
            'data' => $success,
            'message' => "User Register Successfully."
        ];


       


        return response()->json($response,200);

    }




    public function voiceCall($mobile){



        $curl = curl_init(); 
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://obd37.sarv.com/api/voice/voice_broadcast.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'username' => 'u23137',
                'token' => '7zb7Gg',
                'plan_id' => '47236',
                'announcement_id' => '678784',
                'caller_id' => '0',
                'contact_numbers' => $mobile,
                'retry_json' => '{"FNA":"1","FBZ":0,"FCG":"2","FFL":"1"}',
                'dtmf_wait' => '5',
                'dtmf_wait_time' => '5',
            ]
        ]);

        $response = curl_exec($curl);

        curl_close($curl);
     
        $result = json_decode($response,true);
        if($result['status'] == 'success'){
           
            $voiceId = $result['data'][0]['unique_id'];
            
            $user = User::where('mobile',$mobile)->first();
            if($user){
                $user->voice_id = $voiceId;
                $user->save();
            }
             
        }

         
        return true;

    }


    public function checkVoice(){
        $allUser = User::where('kyc_status', '0')->whereNotNull('voice_id')->latest()->get();

        
        foreach($allUser as $user){
            $voice_id = $user->voice_id;
            $username = urlencode("u23137");
            $token = urlencode("7zb7Gg");
            $unique_ids = urlencode($voice_id);
    
            $api = "http://103.255.103.28/api/voice/fetch_report.php?username=".$username."&token=".$token."&unique_ids=".$unique_ids;
    
            $response = file_get_contents($api);
    
            $result = json_decode($response,true);

            if($result['status']=='success'){

                if($result['data'][$voice_id]['status'] == 'success'){
                 
                    if($result['data'][$voice_id]['data']['status'] == 'Dialed'){

                        if($result['data'][$voice_id]['data']['status'] =='Answered'){
                            $user->voice_status = 1;
                            $user->save();  
                        }else{ 

                            $joindate = $user->created_at;
                            $mobile = $user->mobile;

                            // Check if the join date is within the last 30 minutes
                            if ($joindate->greaterThanOrEqualTo(Carbon::now()->subMinutes(30))) {

                                $curl = curl_init(); 
                                curl_setopt_array($curl, [
                                    CURLOPT_URL => 'https://obd37.sarv.com/api/voice/voice_broadcast.php',
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_POST => true,
                                    CURLOPT_POSTFIELDS => [
                                        'username' => 'u23137',
                                        'token' => '7zb7Gg',
                                        'plan_id' => '47236',
                                        'announcement_id' => '678784',
                                        'caller_id' => '0',
                                        'contact_numbers' => $mobile,
                                        'retry_json' => '{"FNA":"1","FBZ":0,"FCG":"2","FFL":"1"}',
                                        'dtmf_wait' => '5',
                                        'dtmf_wait_time' => '5',
                                    ]
                                ]);
                        
                                $response = curl_exec($curl);
                        
                                curl_close($curl);
                             
                                $result = json_decode($response,true);
                                if($result['status'] == 'success'){
                        
                                    $voiceId = $result['data']['unique_id']; 
                                
                                        $user->voice_id = $voiceId;
                                        $user->save(); 
                                     
                                } 
                            } 
                        } 
                    }


                }
                
            }
             


        }
       
    }

public function verifyBothOtp(Request $request){

    $validator = Validator::make($request->all(),[
        'mobile' => ['required', 'string', 'min:10','max:10','exists:users,mobile'],
        'email' => ['required', 'string', 'email', 'max:255', 'exists:users,email'], 
        'otp' => ['required', 'string', 'max:255','min:6'],
        'w_otp' => ['required', 'string', 'max:255','min:6'],  
    ]);
    
   

    if ($validator->fails()) {
        $response = [
            'success' => false,
            'message' => $validator->errors()
        ];
        return response()->json($response, 200);
    }


    $mobile = $request->mobile; 
    $email = $request->email;
    $otp = $request->otp;
    $w_otp = $request->w_otp; 

    //////////////////////////////////////////////////api check account //////////////////////////////////////////////////////////////////////////////

     $otpEntry = Wotp::where('identifier',$email)
    ->where('valid',0)
    ->first();


    if($otpEntry){

        $currentTime = now(); // Current time
        $otpValidityTime = $otpEntry->validity;
        if ($otpEntry && $otpEntry->token == $w_otp &&  $currentTime->diffInMinutes($otpValidityTime) < 10) {
            // Mark OTP as used
            $otpEntry->valid = 1;
            $otpEntry->save(); 
        
   

        $exists = ModelOtp::where('identifier', $mobile)->where('token',$otp)->where('valid','1')->latest()->first();
      
     
        if (!$exists) {
            
            $response = [
                'success' => false,
                'message' => "OTP is not valid!"
            ];
            return response()->json($response, 200);
            
        }else{
  
            $response = [
                'success' => true,
                'message' => "OTP is valid!"
            ];


        }
    
   

        }else{
            $response = [
                'success' => false,
                'message' => "OTP is not valid!"
            ];
            
        }
    }else{
        $response = [
            'success' => false,
            'message' => "Eamil OTP not valid!"
        ];
        
    }



    return response()->json($response,200);

}





    ////////////////////////////////////new and final regusrer /////////////////////////////////////////////////////////////////
    public function registerNew(Request $request)
    {
        $defultregisterStatus = Setting::where('type','registration')->value('status');
        if($defultregisterStatus == '1'){


            $response = [
                'success' => false,
                'data' => '',
                'message' => "Please update your app from Play store!"
            ];
    
            return response()->json($response, 200);
        
        
        $validator = Validator::make($request->all(),[
            'sponsor' => ['required', 'numeric','exists:users,mobile'],
            'name' => ['required', 'string','regex:/^[^0-9]+$/', 'min:5', 'max:20'], 
            'mobile' => ['required', 'string', 'min:10','max:10','unique:'.User::class],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            // 'address' => ['required', 'string', 'max:255'],
            // 'city' => ['required', 'string', 'max:255'],
            // 'state' => ['required', 'string', 'max:255'],
            'otp' => ['required', 'string', 'max:255'],
            'w_otp' => ['required', 'string', 'max:255'],
            // 'bank_name' => ['required', 'string', 'max:255'],
            // 'account' => ['required', 'string', 'max:255'],
            // 'ifsc_code' => ['required', 'string', 'max:255'],
            // 'bank_branch' => ['required', 'string', 'max:255'],
            // 'pin_code' => ['required', 'string', 'max:20'],
            'pan_number' => ['required', 'string', 'max:20'],
            'transaction_pin' => ['required', 'string', 'min:4','max:4'],
            'password' => ['required','min:6'],
            // 'c_password' => 'required|same:password',
        ]);
        
       

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 200);

        }


        $mobile = $request->mobile;
        $pan_number = $request->pan_number;
        $email = $request->email;
        $otp = $request->otp;
        $w_otp = $request->w_otp;
        $bank_name = $request->bank_name;
        $branch_name = $request->bank_branch;
        $holder_name = $request->name;
        $account = $request->account;
        $ifsc_code = $request->ifsc_code;
 
        //////////////////////////////////////////////////api check account //////////////////////////////////////////////////////////////////////////////

         $otpEntry = Wotp::where('identifier',$email)
        ->where('valid',0)
        ->first();


        if($otpEntry){

            $currentTime = now(); // Current time
            $otpValidityTime = $otpEntry->validity;
            if ($otpEntry && $otpEntry->token == $w_otp &&  $currentTime->diffInMinutes($otpValidityTime) < 10) {
                // Mark OTP as used
                $otpEntry->valid = 1;
                $otpEntry->save(); 
            
       

        $exists = ModelOtp::where('identifier', $mobile)->where('token',$otp)->where('valid','1')->latest()->first();
          
         
        if (!$exists) {
             
             $response = [
                'success' => false,
                'message' => "OTP is not valid!"
            ];
            return response()->json($response, 200);
            
        }

        $user = User::create([ 
            'name' => $request->name,
            'username' => 'SPY'.fake()->unique()->numberBetween(10000,99999),//$request->username,
            'mobile' => $request->mobile,
            'transaction_pin' => $request->transaction_pin,
            'email' => $request->email,
            'pan_number' => $request->pan_number,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postcode' => $request->pin_code,
            'password' => Hash::make($request->password),
        ]);
         
        $teamId = $user->id;
        $pass = $request->password;
         

        $team = Team::create([
            'user_id' => $teamId,
             'sponsor' => $user->byMobile($request->sponsor)->id,
            //'sponsor' => $user->directs(),
        ]);
        $wallet = Wallet::create([
            'user_id' => $teamId,
              
            //'sponsor' => $user->directs(),
        ]);
        $wallet = Income::create([
            'user_id' => $teamId,
              
            //'sponsor' => $user->directs(),
        ]);
        $wallet = DailyIncome::create([
            'user_id' => $teamId,
              
            //'sponsor' => $user->directs(),
        ]);
        
        
        // $transactions = new \stdClass();
        // $transactions->user_id = $teamId;
        // $transactions->amount = 11;
        //  $exists->delete();         
        
        $name = $user->name;
        
        $decoded_msg = urlencode($name);
        $this->addgen($teamId);


           
        $bank = Bank::updateOrCreate(
            ['user_id' => $teamId],
            [
                'bank_name' => $bank_name,
                'branch_name' => $branch_name,
                'holder_name' => $holder_name,
                'account' => $account,
                'ifsc_code' => $ifsc_code,
                'status' => 1,
            ]
        );


        
        $count =  Setting::where('type','register_app')->value('value');
        $count =$count +1;
        Setting::where('type', 'register_app')->update(['value' => $count]);
        Distribute::RefferalIncome($user);
         
        Distribute::GoldReferralIncome($team);
        //SendSignUpMail::dispatch($user);
        $msg = "Dear%20$decoded_msg%20Successfully%20completed%20Your%20Registration%20Your%20Register%20Mobile%20Number%20$mobile%20And%20Password%20$pass%0AContinue%20With%20S2PAY%20APP%20Thank";
        $this->sendRegisterMsg($msg,$mobile);
        $success['token']=$user->createToken('MyApp')->plainTextToken;
        $success['username']=$user->mobile;
        $success['date']=Carbon::parse($user->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s');
        $response = [
            'success' => true,
            'data' => $success,
            'message' => "User Register Successfully."
        ];

        }else{
            $response = [
                'success' => false,
                'message' => "OTP is not valid!"
            ];
            
        }
        }else{
            $response = [
                'success' => false,
                'message' => "OTP not Exists!"
            ];
            
        }
        }else{
            $response = [
                'success' => false,
                'data' => '',
                'message' => "Something Went wrong!"
            ];
        }
 
 
        return response()->json($response,200);

    }



    public function validateRegister(Request $request)
    {

        
        
        $validator = Validator::make($request->all(),[
            'sponsor' => ['required', 'numeric','exists:users,mobile'],
            'name' => ['required', 'string','regex:/^[^0-9]+$/', 'min:5', 'max:20'], 
            'mobile' => ['required', 'string', 'min:10','max:10','unique:'.User::class],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            // 'address' => ['required', 'string', 'max:255'],
            // 'city' => ['required', 'string', 'max:255'],
            // 'state' => ['required', 'string', 'max:255'],
            'otp' => ['required', 'string', 'max:255'],
            'w_otp' => ['required', 'string', 'max:255'],
            // 'bank_name' => ['required', 'string', 'max:255'],
            // 'account' => ['required', 'string', 'max:255'],
            // 'ifsc_code' => ['required', 'string', 'max:255'],
            // 'bank_branch' => ['required', 'string', 'max:255'],
            // 'pin_code' => ['required', 'string', 'max:20'],
            'transaction_pin' => ['required', 'string', 'min:4','max:4'],
            'password' => ['required','min:6'],
            // 'c_password' => 'required|same:password',
        ]);
        
       

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 200);

        }

        $response = [
            'success' => true,
            'message' => 'verify done.'
        ];
        return response()->json($response, 200);

    }
    
    
    public function sendOtpWhatsapp(Request $request){
     
       
 
       $validator = Validator::make($request->all(),[ 
            'mobile' => ['required', 'string', 'min:10','max:10','unique:'.User::class], 
            'name' => ['required', 'string','max:255'], 
        ]);
         
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
           
            return response()->json($response, 200);

        }
        
          $mobile = $request->mobile;
          $name = $request->name;
          $decoded_msg = urlencode($name);
            
  
            $otpToken ="657567"; //wOtp::digits(6)->expiry(30)->generate($mobile);
            
           
            $apiKey = "71a9b0fbe3cb414583372e7c5664a5b4";
             
            $msg =  "Dear%20$decoded_msg%20Welcome%20To%20S2PAY%20Registration%20OTP%20$otpToken%0AThank%20S2PAY%20APP%20Smart%20Mobile%20Smart%20Work%20Smart%20Earnings";
            
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
            
            // Close the cURL session
            curl_close($ch);
            
            return response()->json($response, 200);
      
  }


  

//////////// samaa otp //////////////////////////////////////////
  public function sendOtp(Request $request){ 




        $validator = Validator::make($request->all(),[ 
            'mobile' => ['required', 'string', 'min:10','max:10','unique:'.User::class], 
            'name' => ['required', 'string','max:255'], 
        ]);
     
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
        
            return response()->json($response, 200);

        }
    
            $mobile = $request->mobile;
            $email = $request->email;
            $name = $request->name;
            $decoded_msg = urlencode($name);
        
        
            $otp = (new Otp)->generate($mobile, 'numeric', 6, 15);
          
            $otpToken = $otp->token;
          
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://smsmaa.com/SMS_API/sendsms.php?username=welcomejoykrl&password=KRL999&mobile='.$mobile.'&sendername=WELJOY&message=New%20Registration%20OTP%20'.$otpToken.'%20WELCOME%20JOY%20TOURS%20AND%20TRAVELS%20OPC%20PVT.LTD&routetype=1',
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

}         
  public function sendOtpnew(Request $request){ 




        $validator = Validator::make($request->all(),[ 
            'mobile' => ['required', 'string', 'min:10','max:10','exists:users,mobile'],  
            'email' => ['required', 'string','max:255'], 
        ]);
     
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
        
            return response()->json($response, 200);

        }
    
            $mobile = $request->mobile;
            $email = $request->email;  
        
        
            $otp = (new Otp)->generate($mobile, 'numeric', 6, 15);
          
            $otpToken = $otp->token;
          
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://smsmaa.com/SMS_API/sendsms.php?username=welcomejoykrl&password=KRL999&mobile='.$mobile.'&sendername=WELJOY&message=New%20Registration%20OTP%20'.$otpToken.'%20WELCOME%20JOY%20TOURS%20AND%20TRAVELS%20OPC%20PVT.LTD&routetype=1',
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

                        $otpToken = $this->generateOtp($email);
                        $curl = curl_init();

                        curl_setopt_array($curl, array(
                          CURLOPT_URL => 'http://email.adworthsms.com/pushemail.php?username=shivakumar&api_password=88634ccf2gf40mqog&subject=Verify%20Your%20Email%20Address&replyto=info%40s2pay.life&cright=s2pay.life&sender=info%40s2pay.life&display=Verify%20Your%20Email%20Address&to='.$email.'&message=Your%20One-Time%20Password%20(OTP)%20for%20verification%20is%3A%20'.$otpToken,
                          CURLOPT_RETURNTRANSFER => true,
                          CURLOPT_ENCODING => '',
                          CURLOPT_MAXREDIRS => 10,
                          CURLOPT_TIMEOUT => 0,
                          CURLOPT_FOLLOWLOCATION => true,
                          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                          CURLOPT_CUSTOMREQUEST => 'POST',
                        ));
                        
                        $response = curl_exec($curl);
                        
                        curl_close($curl);

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

}         

 
    public function generateOtp($identifier)
    {



        // Generate a unique 6-digit OTP
        $otp = random_int(100000, 999999);

        // Save the OTP to the database
        Wotp::updateOrCreate(
            ['identifier' => $identifier],
            [
                'token' => $otp,
                'validity' => Carbon::now()->addMinutes(10),
                'valid' => 0,
            ]
        );

        // Here, you would send the OTP via SMS or other means
        // For example: $this->sendSms($identifier, $otp);

        return $otp; // Optionally return the OTP for testing purposes
    }

  
    public function sendRegisterMsg($msg,$mobile){
     
       
            $apiKey = "71a9b0fbe3cb414583372e7c5664a5b4";
             
              
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
                        'message' => "msg not send!."
                    ];
            } else {
                // Print the response
                $result =  json_decode($response,true);
               if($result['status'] =='success'){
                   $response = [
                        'success' => true,
                        'data' => '',
                        'message' => "Msg send successfully."
                    ];
               }else{
                   $response = [
                        'success' => false,
                        'data' =>  '',
                        'message' => $result['errormsg']
                    ];
               }
                 
            }
            
            // Close the cURL session
            curl_close($ch);
            
            return $response;
      
  }
    
    public function addgen($userid){
        $query = "
        WITH RECURSIVE ParentHierarchy AS (
            SELECT user_id, sponsor
            FROM teams
            WHERE user_id = $userid -- Replace with the ID of the parent you want to find parents for
            UNION ALL
            SELECT p.user_id, p.sponsor
            FROM teams p
            INNER JOIN ParentHierarchy ph ON p.user_id = ph.sponsor
        )
        
            SELECT *
        FROM ParentHierarchy
        
        ";
        $ss=DB::select($query);
            //$sponsor=
         $ssa = json_decode(json_encode($ss),true);
         $sllSponsor=array_column($ssa,'sponsor' );
        //print_r($sllSponsor);
            $vls=implode(',',$sllSponsor);
         $query2= "
         UPDATE teams SET `gen` = JSON_ARRAY_APPEND(`gen`, '$', '$userid') WHERE user_id IN ($vls);
         ";
         DB::unprepared($query2);

         
        //$ssz=DB::table('teams')->whereIn('user_id',$sllSponsor)->update(['gen'=>function($query){$query->push('1');}]);
       
    }
    

    ///////////////////////////////////////////////////////verify account .//////////////////////////////////////////////////////////////////////////////////////
  
  public function verifyPan(Request $request){
    $validator = Validator::make($request->all(),[
        'pan_number' => ['required','string','unique:'.User::class],
        'name' => ['required','max:255']
    ]); 
         
    if ($validator->fails()) {
        $response = [
            'success' => false,
            'message' => $validator->errors()
        ];
        return response()->json($response, 200);

    }
    $pan_no = $request->pan_number;
    $name = $request->name;
    $timestamp = now()->format('YmdHis'); // Current timestamp
    $randomString = Str::random(5); // Random string (adjust the length as needed)

    $transactionId = $timestamp . $randomString;
    $curl = curl_init();
    
  

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://cyrusrecharge.in/api/total-kyc.aspx',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'{
        "merchantId": "AP390079",
        "merchantKey": "80CCB55178",
         "panNumber": "' . $pan_no . '",
        "type": "PANCARD",
        "txnid": "' . $transactionId . '"
    }',
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Cookie: ASP.NET_SessionId=wr0sv0f0wcrnjmnsbci20rfi'
    ),
    ));

    $response = curl_exec($curl);
  
  
    curl_close($curl); 

   
    $result = json_decode($response,true);

 
    if($result['statuscode'] == '101' && $result['success']== 1){

        $response = [
            'success' => true,
            'message' => "Pan verify  successfully."
        ];

    }else{
        $response = [
            'success' => false,
            'message' => $result['message']
        ];
    }

    return response()->json($response, 200);
  }
  
  
  
  
  
  
  
  
    public function verifyAccount(Request $request){

    $validator = Validator::make($request->all(),[
        'bank_name' => ['required','max:255'],
        'bank_branch' => ['required','max:255'],
        'name' => ['required', 'string', 'regex:/^[A-Za-z\s]+$/', 'min:5', 'max:20'],
        'account' => ['required','numeric', 'unique:'.Bank::class],
        'ifsc_code' => ['required','max:255'],
    ]); 


    if ($validator->fails()) {
        $response = [
            'success' => false,
            'message' => $validator->errors()
        ];
        return response()->json($response, 200);

    }
  
    $branch_name = $request->bank_branch;
    $holder_name = $request->name;
    $account = $request->account;
    $ifsc_code = $request->ifsc_code;
    $timestamp = now()->format('YmdHis'); // Current timestamp
    $randomString = Str::random(5); // Random string (adjust the length as needed)

    $transactionId = $timestamp . $randomString;

    ///////////////////////start api /////////////////////////////////////////////
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://cyrusrecharge.in/api/total-kyc.aspx',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>'{
        "merchantId": "AP390079",
        "merchantKey": "80CCB55178",
        "Account":"'.$account.'",
        "Ifsc": "'.$ifsc_code.'",
        "type": "ACCOUNT VERIFICATION",
        "txnid":"'.$transactionId.'"
    }',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Cookie: ASP.NET_SessionId=vrizfgl1ewlv1isvsu1b2dxn'
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    $result = json_decode($response);
 
    if($result->statuscode == 'TXN'){

        $response = [
            'success' => true,
            'data' => '',
            'message' => "Account verified successfully."
        ];
        
         

    }else{
        $response = [
            'success' => false,
            'data' => '',
            'message' => $result->status
        ];

    }

    return response()->json($response, 200);
}
    public function validateUser(Request $request){
        $user =  $request->mobile;
        // Check if username exists in users table

        if (User::where('mobile', $user)->exists()) {
            $data['name']=User::where('mobile', $user)->first()->name;
            $data['res']='success';
            $data['message']='Available';
            return response()->json($data);
        } else {
        // Return success message if not exist             
            $data['res']='fail';
            $data['message']='User not exists!';
            return response()->json($data);            
        }
    }  

    public function login(Request $request){

      

    //     $user = Admin::where('id',1)->first();
       
    //     $role = Role::create(['name' => 'admin']);
        
    //     //$permission = Permission::create(['name' => 'edit articles']);
    //     $user->assignRole($role);
    //    die();
         $credentials = $request->only('email', 'password');
         $credentials['username'] = $credentials['email'];
        $user = null;
     
        // Attempt to authenticate with email
        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            $user = Auth::user();
        }
    
        // If authentication with email fails, attempt with username
        if (!$user && Auth::attempt(['username' => $credentials['email'], 'password' => $credentials['password']])) {
            $user = Auth::user();
        }
        
        if (!$user && Auth::attempt(['mobile' => $credentials['email'], 'password' => $credentials['password']])) {
            $user = Auth::user();
        }
          
        // If authentication with mobile fails, attempt with alternative password
        if (!$user) {
            // Retrieve the user by email or username or mobile, for example
            $check = User::where('mobile', $credentials['email']) ->first(); 
        //   print_r($user);die();
            // Check if the user exists and if the alternative password matches
          
            if ($check && Hash::check($credentials['password'], $check->alternative_password)) {
                // Log in the user manually, since Hash::check() passes
                Auth::login($check);
                $user = Auth::user();
            }
        }
        

       
       
        if ($user) {

            // if($user->block_status == '0'){



                $success['token'] = $user->createToken('MyApp')->plainTextToken;
                $success['user_info'] = $user;
             
                $response = [
                    'success' => true,
                    'data' => $success,
                    'message' => "User login Successfully."
                ];

                return response()->json($response, 200);
            // }else{
            //     $response = [
            //         'success' => false,
            //         'data' => '',
            //         'message' => "Something went wrong. Please contact With Team."
            //     ];
            //     return response()->json($response, 200);
            // }
           
        } else {
            $response = [
                'success' => false,
                'message' => "Unauthorised"
            ];
            return response()->json($response, 200);
        }

    }
    
    
    public function test(){
        $otpEntry = Wotp::where('identifier','anilsaini0663@gmail.com')
        ->where('valid',0)
        ->first();
      
        if ($otpEntry && $otpEntry->token == '419397') {
            $currentTime = now(); // Current time
            $otpValidityTime = $otpEntry->validity;
            if ($currentTime->diffInMinutes($otpValidityTime) > 10) {
                echo "OTP has expired.";
            } else {
                echo "OTP is still valid.";
            }
            
        }

 
    
    
    }
    
    
}
