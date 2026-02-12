<?php

namespace App\Http\Controllers;

use App\Helper\Distribute;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Bank;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Validator;
use Stringable;
use Illuminate\View\View;
use Illuminate\Support\Str;
use App\Models\Otp as ModelOtp;
use App\Models\Setting;
use App\Models\Team;
use Ichtrojan\Otp\Otp; 
use App\Models\Wotp;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function validateUser(Request $request) 
    {
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
    
    
    
    //* -------- API -----------------//
    public function data(Request $request) {
        $bank = Bank::where('user_id', $request->user()->id)->first();

        $response = [
            'success' => true,
            'data' => array_merge($request->user()->toArray(), [
                'pan_status' => $request->user()->pan_number != ''?true:false,
                'bank_status' => $bank ? $bank : false,
            ])
        ];
        return response()->json($response, 200);
    }
    
    public function updateProfile(Request $request){
        $userId=$request->user()->id;
        $dir = User::find($userId);
        if($dir){
            
            $validator = Validator::make($request->all(),[
                // 'name' => ['required', 'string','regex:/^[A-Za-z]+$/','max:255'],
                'name' => ['required', 'string', 'regex:/^[A-Za-z\s]+$/', 'min:5', 'max:20'],
                'email' => ['required', 'string'],
               // 'mobile' => ['required', 'string', 'min:10','max:10']
            ]);
            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->errors()
                ];
                return response()->json($response, 200);

            }
            
             $request->user()->update([
                'name' => $request->name,
                'email' => $request->email,
                //'mobile' => $request->mobile,
            ]);
    
            $response = [
                'success' => true,
                'message' => 'Profile updated Successfully.',
            ];
            
    }else{
         $response = [
            'success' => false,
            'message' => 'User Not Exists!',
        ];
    }
        
         return response()->json($response, 200);
    }
    
    public function updateImage(Request $request){
         $validator = Validator::make($request->all(),[ 
            'user_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif','max:2048'],
            
        ]);
            
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 200);

        }
        $userId = $request->user()->id;
            
        $imageName = time().'.'.$request->user_image->extension();
        
        $request->user_image->storeAs('user', $imageName);
        
        $user = User::find($userId);
        if($user){
            $user->image = "app/user/".$imageName;
            $user->save();
             $response = [
                'success' => true,
                'message' => 'User image update Successfully.',
            ];
        }else{
            $response = [
                'success' => false,
                'message' => 'User not exists!',
            ];
        }
          return response()->json($response, 200);
    }




    ////////////////////////////////////////register user /////////////////////////////////////////////////////////////////////////////////////////////////////


    public function verifyBothOtp(Request $request){
        if($request->user()->kyc_status == '1'){
            $validator = Validator::make($request->all(),[
                'mobile' => ['required', 'string', 'min:10','max:10','exists:users,mobile'],
                'email' => ['required', 'string', 'max:255'], 
                'otp' => ['required', 'string', 'max:255','min:6'],
                'w_otp' => ['required', 'string', 'max:255','min:6'],  
            ]);

        }else{
            $validator = Validator::make($request->all(),[
                'mobile' => ['required', 'string', 'min:10','max:10','exists:users,mobile'],
                'email' => ['required', 'string','max:255'], 
                'otp' => ['required', 'string', 'max:255','min:6'],
                'w_otp' => ['required', 'string', 'max:255','min:6'],  
            ]);
        }
       
        
       
    
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

                
                $user = $request->user();
                $user->otp_status = 1;
                $user->save();
      
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



    public function verifyPan(Request $request){



        if($request->user()->pan_number != ''){
            $validator = Validator::make($request->all(),[
                'pan_number' => ['required','string'],
                // 'pan_number' => ['required','string'],
                'name' => ['required','max:255']
            ]); 
        }else{
            $validator = Validator::make($request->all(),[
                'pan_number' => ['required','string','unique:'.User::class],
                // 'pan_number' => ['required','string'],
                'name' => ['required','max:255']
            ]); 
        }
        
             
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
           $user =  $request->user();

           $user->pan_number = $request->pan_number;
           $user->save();
    
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




    public function updateUser(Request $request){
        $bank = Bank::where('user_id', $request->user()->id)->first();
        if($bank &&  $request->user()->pan_number != '' && $request->user()->kyc_status == '1'){

            
            $res = [
                'success' => false,
                'message' => "already Kyc approved."
            ];
            return response()->json($res, 200);


        }
        $res = [
            'success' => false,
            'message' => "something wrong!"
        ];
        return response()->json($res, 200);
 
        $validator = Validator::make($request->all(),[
            'bank_name' => ['required','max:255'],
            'bank_branch' => ['required','max:255'],
            'name' => ['required', 'string','min:5', 'max:20'], 
            'account' => ['required','numeric', 'unique:'.Bank::class],
            // 'account' => ['required','numeric'],
            'ifsc_code' => ['required','max:255'],
        ]); 
    
    
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 200);
    
        }


        $defultregisterStatus = Setting::where('type','kyc')->value('status');
        if($defultregisterStatus == '1'){
       
      
        $branch_name = $request->bank_branch;
        $bank_name = $request->bank_name;
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
        
        $responses = curl_exec($curl);
        
        curl_close($curl);
        $result = json_decode($responses);
         
        if($result->statuscode == 'TXN'){


           
         
            $user = $request->user();
            $teamId = $request->user()->id;
            $mobile = $request->user()->mobile;


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



            if($request->user()->kyc_status == '0'){

                $sponsorinfo =  User::find($user->byMobile($user->sponsor)->id);
                $tlldirects = $sponsorinfo->ttl_directs;
                if($tlldirects == 0 || $tlldirects == null){
                    $position = 1;
                }else{
                      if($tlldirects % 2 == 0){
                        $position = 1;
                    }else{
                        $position = 2;
                    }
                }
               
                $after = $tlldirects= $tlldirects + 1; 
                $sponsorinfo->ttl_directs =$after;
                $sponsorinfo->save();
                $team = Team::create([
                    'user_id' => $teamId,
                     'sponsor' => $user->byMobile($user->sponsor)->id,
                     'position'=>$position,
                ]);
    
    
    
                $this->addgen($teamId);
    
                $decoded_msg = urlencode($holder_name);
                $count =  Setting::where('type','register_app')->value('value');
                $count =$count +1;
                Setting::where('type', 'register_app')->update(['value' => $count]);
                Distribute::RefferalIncome($user);
                
                
            //SendSignUpMail::dispatch($user);
            // $msg = "Dear%20$decoded_msg%20Successfully%20completed%20Your%20Registration%20Your%20Register%20Mobile%20Number%20$mobile%20And%20Password%20$pass%0AContinue%20With%20S2PAY%20APP%20Thank";
            // $this->sendRegisterMsg($msg,$mobile);
    
                $user->kyc_status = 1;
                $user->pan_number = $request->pan_number;
                $user->save();
                //Distribute::GoldReferralIncome($team);
                Distribute::GoldReferralIncomeNew($team);
                $response = [
                    'success' => true,
                    'data' => '',
                    'message' => "Account verified successfully."
                ];
            }

                $user->pan_number = $request->pan_number;
                $user->save();
                $res = [
                    'success' => true,
                    'message' => "Kyc update successfully."
                ];
                return response()->json($res, 200);
    
    
        }else{

            if($result->status =='insufficient balance in account'){
                $masg = "Technical issue try again after some time!";
            }else{
                $masg = $result->status;
            }
            $response = [
                'success' => false,
                'data' => '',
                'message' => $masg
            ];
    
        }
        }else{
            $response = [
                'success' => false,
                'data' => '',
                'message' => 'somthing went wrong!'
            ];
    
        }
        return response()->json($response, 200); 

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










}
