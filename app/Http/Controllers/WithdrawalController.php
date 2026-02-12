<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Investment;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\Bank;
use App\Models\Withdrawal;
use App\Models\Test;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use App\Rules\Checkbalance;
use Illuminate\Support\Facades\DB;
use App\Listeners\TopupListener;
use App\Models\UserPaymentMethod;
use App\Models\WithdrawRequest;
use App\Rules\WithdrawCheck;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon; 
use Illuminate\Support\Facades\Auth;
use Validator;

class WithdrawalController extends Controller
{
    public function withdraw(Request $request)
    {   
        $userId=$request->user()->id;
        $dir = User::find($userId);
        return view('pages.withdrawal.withdrawal', [
            'user' => $dir,
        ]);
    }
    public function store(Request $request): RedirectResponse
    {
        $userId = Auth::user()->id;
        $wallet_type='main_wallet';
        $request->validate([
            //'receive_in' => ['required','integer'],
            'amount' => ['required', 'integer', 'min:2' ,new Checkbalance('fund_wallet'),new WithdrawCheck($request)],            
        ]); 
        //$usera = User::where('username',$request->username)->first();
        //$receiveing_data=UserPaymentMethod::find($request->receive_in)->first();
        
        
        DB::beginTransaction();
        try {
            //code...
            $amnt = $request->amount;
            $x_charge = $amnt*10/100;
            $payable = $amnt-$x_charge;

            $transaction = Withdrawal::create([
                'user_id' => Auth::user()->id,
                'user_details' =>Auth::user()->eth_address,
                'amount' => $payable,
                'tx_charge' => $x_charge,
                'status'  => 0,
            ]);

            Wallet::where('user_id',Auth::user()->id)->decrement('main_wallet',$request->amount);
            DB::commit();
            $request->session()->flash('status', 'Withdrawal Request successful!');

        } catch (\Exception $e) {
            //throw $th;
            DB::rollBack();
            $request->session()->flash('error', 'Something wrong!');

        }
        

        

        //Auth::login($user);

        return redirect('./withdraw');
    }

    public function history(Request $request)
    {   
        
        $query=$request->user()->withdrawals();
        $a=$query->paginate(10)->withQueryString();
        return view('pages.withdrawal.history', [
            'user' => $request->user(),
        ])->with('transactions',$a);
        
    }
    
    
    public function WithdrawAmntcyrus(Request $request){

        $res = [
            'success' => false,
            'message' => "Payout service down! Please try again after some time."
        ];
        return response()->json($res, 200);
        
        $wallet_type='fund_wallet';
        
         // $input = $request->all();
        $jsonSerializedData = json_encode($request);
         
       //  $resdsult  = base64_decode($input); 
        
      
        $userId=$request->user()->id;
            $validator = Validator::make($request->all(),[
             'amount' => ['required', 'integer', 'min:100' ,new Checkbalance('main_wallet')],   
            ]);
            
            if ($validator->fails()) {
                $res = [
                    'success' => false,
                      'data' =>  '',
                    'message' => $validator->errors()
                ];
                return response()->json($res, 200);
    
            }
            
            
            $bankDetails = Bank::where('user_id',$userId)->first();
             
            
            if($bankDetails->account != '' && $bankDetails->ifsc_code != '' && $bankDetails->bank_name != ''){
                
                $accountNum =$bankDetails->account;
                $bank_name =$bankDetails->bank_name;
                $ifsc =$bankDetails->ifsc_code;
                $mobile =$request->user()->mobile;
                $email =$request->user()->email;
                $name =$request->user()->name;
                
                
                if (strlen($name) > 4) {
                     
                        //code...
                        $amnt = $request->amount;
                        
                        
                        $tx_charge = $amnt*1.8/100; 
                        $payable = $amnt-$tx_charge;
                        $timestamp = now()->format('YmdHis'); // Current timestamp
                        $clientid = Str::random(10); // Random string (adjust the length as needed
                        // $clientid = Str::random(10);
                             
   
                        
                        
                        
                      
                        
                      
                        
                      
                        
                       $response = json_decode(curl_exec($ch),true);
                        
                        curl_close($ch);
                        
                        
                     $test = Test::create([
                                'remark' => json_encode($response),
                                'updated_at' => now(),
                                'created_at' => now(),
                            ]);
                             
                        
                           
                            
                        //////////////////////////////////////////////////new end ////////////////////////////////////////////////////////
             
                              $stus=$response['status']; 
                              
                        //  $stus = "SUCCESS";
                            if($stus=='SUCCESS' || $stus=='PENDING' || $stus=='pending' || $stus=='PROCESSING'){
                                if($stus=='SUCCESS'){
                                   $status = 1; 
                                }else{
                                     $status = 0; 
                                }
                         
                                $transaction = Withdrawal::create([
                                    'user_id' => Auth::user()->id,
                                    'amount' => $payable,
                                    'tx_charge' => $tx_charge, 
                                    'user_details' => $clientid, 
                                    'status'  => $status,
                                ]);
                    
                                Wallet::where('user_id',Auth::user()->id)->decrement('main_wallet',$request->amount);
                                 
                                
                                $responses = [
                                            'success' => true,
                                            'data' =>  $bankDetails,
                                            'message' => "Withdrawal Request successful."
                                ];
                             }else{ 
                                    $msg=$ress['msg'];
                                    $responses = [
                                        'success' => false,
                                        'data' =>  '',
                                        'message' => $msg
                                    ];
                             }
                         
                        //  }else{
                        //         $responses = [
                        //                     'success' => false,
                        //                     'data' =>  '',
                        //                     'message' => "Token Expired!Please try some time."
                        //         ];
                        //  }
                        //$request->session()->flash('status', 'Withdrawal Request successful!');
            
                     
                
                }else{
                    $responses = [
                        'success' => false,
                        'data' =>  '',
                        'message' => "The length of Name must be 20 characters or fewer."
                    ];
                }
                
                
            }else{
                $responses = [
                    'success' => false,
                    'data' =>  '',
                    'message' => "Invalid Account details!Please Fill Correct Account Details."
                ];
            }
            return response()->json($responses, 200);
    }
    
    public function WithdrawAmnt(Request $request){

        
        
        // if($request->user()->pan_number != '' && $request->user()->kyc_status == '1'){
            $wallet_type = $request->wallet_type;
     
           $userId=$request->user()->id;
            $validator = Validator::make($request->all(),[
             'wallet_type' => ['required', 'string', 'max:255'],   
             'amount' => ['required', 'integer', 'min:100' ,new Checkbalance($wallet_type)],   
            ]);
            
            if ($validator->fails()) {
                $res = [
                    'success' => false,
                      'data' =>  '',
                    'message' => $validator->errors()
                ];
                return response()->json($res, 200);
    
            }
           
           
                     
                        
                   
                        $bankDetails = Bank::where('user_id',$userId)->first();
                         
                        
                        if($bankDetails->account != '' && $bankDetails->ifsc_code != '' && $bankDetails->bank_name != ''){
                            
                            $accountNum =$bankDetails->account;
                            $bank_name =$bankDetails->bank_name;
                            $ifsc =$bankDetails->ifsc_code;
                            $mobile =$request->user()->mobile;
                            $email =$request->user()->email;
                            $name =Str::lower($request->user()->name);
 
                            
                            
                            if (strlen($name) > 4) {
                                 
                                    //code...
                                            $amnt = $request->amount;
                                            if($amnt <= 500){
                                                $tx_charge = 7;
                                            }else{
                                                $tx_charge = $amnt*1.5/100;
                                            }
                                            
                                             $fin = $amnt-$tx_charge;
                                            $payable = round($fin);
                                            $timestamp = now()->format('YmdHis'); // Current timestamp
                                 
                                         
                               
                                   
                                                      
                                                        $transaction = Withdrawal::create([
                                                            'user_id' => Auth::user()->id,
                                                            'amount' => $payable,
                                                            'tx_charge' => $tx_charge,  
                                                            'wallet_type' => $wallet_type, 
                                                            'status'  => 0,
                                                        ]);
                                                      
                                                        Wallet::where('user_id',Auth::user()->id)->decrement($wallet_type,$request->amount);
                                                 
                                                        $success['date'] = Carbon::parse($transaction->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s');
                                                        $success['bank_details'] = $bankDetails;
                                                        $responses = [
                                                            'success' => true,
                                                            'data' =>  $success,
                                                            'message' => "Withdrawal Request Generated successful."
                                                        ];
                                          
                                       
                            
                            }else{
                                $responses = [
                                    'success' => false,
                                    'data' =>  '',
                                    'message' => "The length of Name must be 20 characters or fewer."
                                ];
                            }
                            
                            
                        }else{
                            $responses = [
                                'success' => false,
                                'data' =>  '',
                                'message' => "Invalid Account details!Please Fill Correct Account Details."
                            ];
                        }
                     
          
        // }else{
        //         $responses = [
        //             'success' => false,
        //             'data' =>  '',
        //             'message' => "Withdrawals are not allowed at this time! Please complete your kyc."
        //         ];
        // }
            
            return response()->json($responses, 200);
    }
    public function WithdrawAmntNew(Request $request){


        $res = [
            'success' => false,
            'message' => "Payout service down! Please try again after some time."
        ];
        return response()->json($res, 200);
        
        if($request->user()->pan_number != '' && $request->user()->kyc_status == '1'){
        $wallet_type = $request->wallet_type;
     
           $userId=$request->user()->id;
            $validator = Validator::make($request->all(),[
             'wallet_type' => ['required', 'string', 'max:255'],   
             'amount' => ['required', 'integer', 'min:100' ,new Checkbalance($wallet_type)],   
             'bank_id' => ['required','exists:banks,id'],   
            ]);
            
            if ($validator->fails()) {
                $res = [
                    'success' => false,
                      'data' =>  '',
                    'message' => $validator->errors()
                ];
                return response()->json($res, 200);
    
            }
           
         $defultWithdrawalStatus = Setting::where('type','withdrawal_on_off')->value('status');
        // $WithdrawalStartDate = Setting::where('type','withdrawal_start_time')->value('value');
        // $WithdrawalStartEnd = Setting::where('type','withdrawal_end_time')->value('value');
         
        // $startTime = Carbon::createFromTimeString($WithdrawalStartDate);
        // $endTime = Carbon::createFromTimeString($WithdrawalStartEnd);
        // $currentTime = Carbon::now();
      
       // if ($currentTime->between($startTime, $endTime)) {
            
            
             
            
            if($defultWithdrawalStatus =='1'){
                    
                    if($request->user()->withdrawal_status == '1'){

                        if($request->wallet_type == 'main_wallet' && $request->user()->ewallet_status == '0'){
                            $res = [
                                'success' => false,
                                'message' => "Payout service down! Please try again after some time."
                            ];
                            return response()->json($res, 200);
                
                        }
                        if($request->wallet_type == 'gold_membership_wallet' && $request->user()->gwallet_status == '0'){
                            $res = [
                                'success' => false,
                                'message' => "Payout service down! Please try again after some time."
                            ];
                            return response()->json($res, 200);
                
                        }
                        
                   
                        $bankDetails = Bank::where('user_id',$userId)->where('id',$request->bank_id)->first();
                         
                        
                        if($bankDetails->account != '' && $bankDetails->ifsc_code != '' && $bankDetails->bank_name != ''){
                            
                            $accountNum =$bankDetails->account;
                            $bank_name =$bankDetails->bank_name;
                            $ifsc =$bankDetails->ifsc_code;
                            $mobile =$request->user()->mobile;
                            $email =$request->user()->email;
                            $name =Str::lower($request->user()->name);
 
                            
                            
                            if (strlen($name) > 4) {
                                 
                                    //code...
                                            $amnt = $request->amount;
                                            if($amnt <= 500){
                                                $tx_charge = 7;
                                            }else{
                                                $tx_charge = $amnt*1.5/100;
                                            }
                                            
                                             $fin = $amnt-$tx_charge;
                                            $payable = round($fin);
                                            $timestamp = now()->format('YmdHis'); // Current timestamp
                                            $clientid = Str::random(10); // Random string (adjust the length as needed
                                         
                                            $tokenData  = $this->generateToken();
                                
                                        if($tokenData['error'] == false){
                                            
                                
                                            //////////////////////////////////////////////////new end ////////////////////////////////////////////////////////
                                   
                                             $stus=$ress['status']; 
                                             if($stus=='SUCCESS' || $stus=='PENDING' || $stus=='pending' || $stus=='PROCESSING'){ 
                                   
                                                        if($stus=='SUCCESS'){
                                                           $status = 1; 
                                                        }else{
                                                             $status = 0; 
                                                        }
                                     
                                                        $transaction = Withdrawal::create([
                                                            'user_id' => Auth::user()->id,
                                                            'amount' => $payable,
                                                            'tx_charge' => $tx_charge, 
                                                            'account_number' => $accountNum, 
                                                            'ifsc_code' => $ifsc, 
                                                            'user_details' => $clientid, 
                                                            'wallet_type' => $wallet_type, 
                                                            'status'  => $status,
                                                        ]);
                                                        Wallet::where('user_id',Auth::user()->id)->decrement($wallet_type,$request->amount);
                                                        $wlt = $request->user()->wallet;
                                                        $closeAmnt = $wlt->$wallet_type;
                                                        $transactions = Transaction::create([
                                                            'user_id' => Auth::user()->id,
                                                            'tx_user' => Auth::user()->id,
                                                            'amount' => $payable,
                                                            'charges' => $tx_charge,
                                                            'type' => 'debit',
                                                            'tx_type' => 'withdraw',
                                                            'status'  => $status,
                                                            'wallet'  => $wallet_type,
                                                            'close_amount'  => $closeAmnt,
                                                            'tx_id'  => $clientid,
                                                            'remark'  => 'withdraw  of  '.$payable.' amount',
                                                        
                                                        ]);
                                                        // $directs  = Auth::user()->directs;
                                                        // foreach($directs as $direct){
                                                        //     $direct_name = $direct->name;
                                                        //     $direct_mobile = $direct->mobile; 
                                                        //     $decoded_msg = urlencode($name);
                                                        //     $direct_names = urlencode($direct_name);
                                                        //     $msg = "Dear%20Mr%20$direct_names%20your%20s2paylife%20sponsor%20Mr%20$decoded_msg%20Today%20Withdrawal%20Rs%20$payable%20Gold%20Membership%20plan%20Today%20income%20Thank%20s2paylife";
                                                        //     $this->sendActiveMsg($msg,$direct_mobile);
                                    

                                                        // }

                                                        // $msg = "Congratulations%20Dear%20$decoded_msg%20($mobi)Your%20successfully%20Completed%20Activation%20prime%20pakage%20Rs%20$amnt%20Thank%20S2PAY";
                                                       
                                                        
                                                        $success['transaction_no'] = $clientid;
                                                        $success['date'] = Carbon::parse($transaction->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s');
                                                        $success['bank_details'] = $bankDetails;
                                                        $responses = [
                                                            'success' => true,
                                                            'data' =>  $success,
                                                            'message' => "Withdrawal Request Generated successful."
                                                        ];
                                             }else{ 
                                                    $msg=$ress['msg'];

                                                    if($msg == "Account doest not have sufficient balance."){
                                                        $msg = "Payout service down! Please try again after some time.";  

                                                    }
                                                    $responses = [
                                                        'success' => false,
                                                        'data' =>  '',
                                                        'message' => $msg
                                                    ];
                                             }
                                         
                                        }else{
                                                $responses = [
                                                            'success' => false,
                                                            'data' =>  '',
                                                            'message' => "Token Expired!Please try some time."
                                                ];
                                        }
                                     
                        
                                 
                            
                            }else{
                                $responses = [
                                    'success' => false,
                                    'data' =>  '',
                                    'message' => "The length of Name must be 20 characters or fewer."
                                ];
                            }
                            
                            
                        }else{
                            $responses = [
                                'success' => false,
                                'data' =>  '',
                                'message' => "Invalid Account details!Please Fill Correct Account Details."
                            ];
                        }
                    }else{
                        $responses = [
                            'success' => false,
                            'data' =>  '',
                            'message' => "Service temporarily closed!withdrawal are currently unavailable. We apologize for the inconvenience."
                        ];
                    }
            }else{
                    $responses = [
                        'success' => false,
                        'data' =>  '',
                        'message' => "Service temporarily closed!withdrawal are currently unavailable. We apologize for the inconvenience."
                    ];
            }
        }else{
                $responses = [
                    'success' => false,
                    'data' =>  '',
                    'message' => "Withdrawals are not allowed at this time! Please complete your kyc."
                ];
        }
            
            return response()->json($responses, 200);
    }
    public function WithdrawAmntClickNew(Request $request){

        $userId = Auth::user()->id;
        DB::transaction(function () use ($userId, $request) {
            // Acquire a lock and check for the last withdrawal request
            $lastRequest = WithdrawRequest::where('user_id', $userId)
                ->latest('created_at')
                ->lockForUpdate()
                ->first();

        if ($lastRequest && $lastRequest->created_at->diffInMinutes(now()) < 5) {
                $remainingTime = 5 - $lastRequest->created_at->diffInMinutes(now());
                
                $responses = [
                    'success' => false,
                    'data' => '',
                    'message' => "Please wait {$remainingTime} minutes before making another withdrawal request."
                ];
                return response()->json($responses, 200);
            }
        });

        

        WithdrawRequest::create([
            'user_id' => $userId,
        ]);
        
        if($request->user()->kyc_status == '1'){
        $wallet_type = $request->wallet_type;
     
           $userId=$request->user()->id;
            $validator = Validator::make($request->all(),[
             'wallet_type' => ['required', 'string', 'max:255'],   
             'amount' => ['required', 'integer', 'min:100' ,new Checkbalance($wallet_type)],   
             'bank_id' => ['required','exists:banks,id'],   
            ]);
            
            if ($validator->fails()) {
                $res = [
                    'success' => false,
                      'data' =>  '',
                    'message' => $validator->errors()
                ];
                return response()->json($res, 200);
    
            }
           
         $defultWithdrawalStatus = Setting::where('type','withdrawal_on_off')->value('status');
        // $WithdrawalStartDate = Setting::where('type','withdrawal_start_time')->value('value');
        // $WithdrawalStartEnd = Setting::where('type','withdrawal_end_time')->value('value');
         
        // $startTime = Carbon::createFromTimeString($WithdrawalStartDate);
        // $endTime = Carbon::createFromTimeString($WithdrawalStartEnd);
        // $currentTime = Carbon::now();
      
       // if ($currentTime->between($startTime, $endTime)) {
            
            
             
            
            if($defultWithdrawalStatus =='1'){
                    

                
                        if($request->wallet_type == 'main_wallet' && $request->user()->wallet->withdraw_ewallet == '0'){
                            $res = [
                                'success' => false,
                                'message' => "Payout service down! Please try again after some time."
                            ];
                            return response()->json($res, 200);
                
                        }
                        if($request->wallet_type == 'gold_membership_wallet' && $request->user()->wallet->withdraw_gwallet == '0'){
                            $res = [
                                'success' => false,
                                'message' => "Payout service down! Please try again after some time."
                            ];
                            return response()->json($res, 200);
                
                        }
                        if($request->wallet_type == 'fund_wallet' && $request->user()->wallet->withdraw_bwallet == '0'){
                            $res = [
                                'success' => false,
                                'message' => "Payout service down! Please try again after some time."
                            ];
                            return response()->json($res, 200);
                
                        }
                        if($request->wallet_type == 'bouns_wallet' && $request->user()->wallet->withdraw_twallet == '0'){
                            $res = [
                                'success' => false,
                                'message' => "Payout service down! Please try again after some time."
                            ];
                            return response()->json($res, 200);
                
                        }

                        if($request->wallet_type == 'elite_wallet' && $request->user()->wallet->withdraw_lwallet == '0'){
                            $res = [
                                'success' => false,
                                'message' => "Payout service down! Please try again after some time."
                            ];
                            return response()->json($res, 200);
                
                        }

                     
                   
                        $bankDetails = Bank::where('user_id',$userId)->where('id',$request->bank_id)->first();
                         
                        
                        if($bankDetails->account != '' && $bankDetails->ifsc_code != '' && $bankDetails->bank_name != ''){
                            
                            $accountNum =$bankDetails->account;
                            $bank_name =$bankDetails->bank_name;
                            $ifsc =$bankDetails->ifsc_code;
                            $mobile =$request->user()->mobile;
                            $email =$request->user()->email;
                            $name =Str::lower($request->user()->name);
 
                        
                            
                            if (strlen($name) > 4) {
                                 
                                    //code...
                                            $amnt = $request->amount;
                                            if($amnt <= 500){
                                                $tx_charge = 7;
                                            }else{
                                                $tx_charge = $amnt*1.5/100;
                                            }
                                            
                                            $fin = $amnt-$tx_charge;
                                            $payable = round($fin);
                                            $timestamp = now()->format('YmdHis'); // Current timestamp
                                            $clientid = Str::random(20); // Random string (adjust the length as needed


                                            $today = Carbon::today();
                                            $dailyTotal = Withdrawal::where('user_id', $userId)
                                            ->whereDate('created_at', $today)
                                            ->sum('amount');
                            
                                                // Check if the daily withdrawal limit has been exceeded
                                                $dailyLimit = 110;
                                          
                                        if (($dailyTotal + $payable) > $dailyLimit) {


                                            $defultAutoWithdrawalStatus = Setting::where('type','auto_withdraw')->value('status');
                                         if($defultAutoWithdrawalStatus == '1'){

                                            $tokenData  = $this->generateToken();
                                
                                            if($tokenData['error'] == false){
                                                
                                                ///////////////////////////////////////////////////////new //////////////////////////////////////////////////////
                                        
                                                //////////////////////////////////////////////////new end ////////////////////////////////////////////////////////
                                       
                                                 $stus=$ress['status']; 
                                                 if($stus=='SUCCESS' || $stus=='PENDING' || $stus=='pending' || $stus=='PROCESSING'){ 
                                       
                                                            if($stus=='SUCCESS'){
                                                               $status = 1; 
                                                            }else{
                                                                 $status = 0; 
                                                            }
                                         
                                                            $transaction = Withdrawal::create([
                                                                'user_id' => Auth::user()->id,
                                                                'amount' => $payable,
                                                                'tx_charge' => $tx_charge, 
                                                                'account_number' => $accountNum, 
                                                                'ifsc_code' => $ifsc, 
                                                                'user_details' => $clientid, 
                                                                'wallet_type' => $wallet_type, 
                                                                'status'  => $status,
                                                            ]);
                                                            Wallet::where('user_id',Auth::user()->id)->decrement($wallet_type,$request->amount);
                                                            $wlt = $request->user()->wallet;
                                                            $closeAmnt = $wlt->$wallet_type;
                                                            $transactions = Transaction::create([
                                                                'user_id' => Auth::user()->id,
                                                                'tx_user' => Auth::user()->id,
                                                                'amount' => $payable,
                                                                'charges' => $tx_charge,
                                                                'type' => 'debit',
                                                                'tx_type' => 'withdraw',
                                                                'status'  => $status,
                                                                'wallet'  => $wallet_type,
                                                                'close_amount'  => $closeAmnt,
                                                                'tx_id'  => $clientid,
                                                                'remark'  => 'withdraw  of  '.$payable.' amount',
                                                            
                                                            ]);
                                                            // $directs  = Auth::user()->directs;
                                                            // foreach($directs as $direct){
                                                            //     $direct_name = $direct->name;
                                                            //     $direct_mobile = $direct->mobile; 
                                                            //     $decoded_msg = urlencode($name);
                                                            //     $direct_names = urlencode($direct_name);
                                                            //     $msg = "Dear%20Mr%20$direct_names%20your%20s2paylife%20sponsor%20Mr%20$decoded_msg%20Today%20Withdrawal%20Rs%20$payable%20Gold%20Membership%20plan%20Today%20income%20Thank%20s2paylife";
                                                            //     $this->sendActiveMsg($msg,$direct_mobile);
                                        
    

                                                            // }
    
                                                            // $msg = "Congratulations%20Dear%20$decoded_msg%20($mobi)Your%20successfully%20Completed%20Activation%20prime%20pakage%20Rs%20$amnt%20Thank%20S2PAY";
                                                           
                                                            
                                                            $success['transaction_no'] = $clientid;
                                                            $success['date'] = Carbon::parse($transaction->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s');
                                                            $success['bank_details'] = $bankDetails;
                                                            $responses = [
                                                                'success' => true,
                                                                'data' =>  $success,
                                                                'message' => "Withdrawal Request Generated successful."
                                                            ];
                                                 }else{ 
                                                        $msg=$ress['msg'];
    
                                                        if($msg == "Account doest not have sufficient balance."){
                                                            $msg = "Payout service down! Please try again after some time.";  
    
                                                        }
                                                        $responses = [
                                                            'success' => false,
                                                            'data' =>  '',
                                                            'message' => $msg
                                                        ];
                                                 }
                                             
                                            }else{
                                                    $responses = [
                                                                'success' => false,
                                                                'data' =>  '',
                                                                'message' => "Token Expired!Please try some time."
                                                    ];
                                            }
                                         

                                         }else{


                                            $status = 3;
                                            $transaction = Withdrawal::create([
                                                'user_id' => Auth::user()->id,
                                                'amount' => $payable,
                                                'tx_charge' => $tx_charge, 
                                                'account_number' => $accountNum, 
                                                'ifsc_code' => $ifsc,   
                                                'wallet_type' => $wallet_type, 
                                                'status'  => $status,
                                            ]);
                                            Wallet::where('user_id',Auth::user()->id)->decrement($wallet_type,$request->amount);
                                            $wlt = $request->user()->wallet;
                                            $closeAmnt = $wlt->$wallet_type;
                                            $transactions = Transaction::create([
                                                'user_id' => Auth::user()->id,
                                                'tx_user' => Auth::user()->id,
                                                'amount' => $payable,
                                                'charges' => $tx_charge,
                                                'type' => 'debit',
                                                'tx_type' => 'withdraw',
                                                'status'  => $status,
                                                'wallet'  => $wallet_type,
                                                'close_amount'  => $closeAmnt, 
                                                'remark'  => 'withdraw  of  '.$payable.' amount',
                                            
                                            ]);
                                            // $directs  = Auth::user()->directs;
                                            // foreach($directs as $direct){
                                            //     $direct_name = $direct->name;
                                            //     $direct_mobile = $direct->mobile; 
                                            //     $decoded_msg = urlencode($name);
                                            //     $direct_names = urlencode($direct_name);
                                            //     $msg = "Dear%20Mr%20$direct_names%20your%20s2paylife%20sponsor%20Mr%20$decoded_msg%20Today%20Withdrawal%20Rs%20$payable%20Gold%20Membership%20plan%20Today%20income%20Thank%20s2paylife";
                                            //     $this->sendActiveMsg($msg,$direct_mobile);
                        

                                            // }

                                            // $msg = "Congratulations%20Dear%20$decoded_msg%20($mobi)Your%20successfully%20Completed%20Activation%20prime%20pakage%20Rs%20$amnt%20Thank%20S2PAY";
                                           
                                             
                                            $success['date'] = Carbon::parse($transaction->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s');
                                            $success['bank_details'] = $bankDetails;
                                            $responses = [
                                                'success' => true,
                                                'data' =>  $success,
                                                'message' => "Successfully Raised a Request for amount $payable. Amount will be Credited to your Bank account with in 24 hours."
                                            ];

                                         } 

                                        }else{

                                            $responses = [
                                                'success' => false,
                                                'data' =>  '',
                                                'message' => "Daily withdrawal limit of 110 exceeded."
                                            ];

                                        }


                                   
                            }else{
                                $responses = [
                                    'success' => false,
                                    'data' =>  '',
                                    'message' => "The length of Name must be 20 characters or fewer."
                                ];
                            }

                            
                            
                        }else{
                            $responses = [
                                'success' => false,
                                'data' =>  '',
                                'message' => "Invalid Account details!Please Fill Correct Account Details."
                            ];
                        }

                    
                   
            }else{
                    $responses = [
                        'success' => false,
                        'data' =>  '',
                        'message' => "Service temporarily closed!withdrawal are currently unavailable. We apologize for the inconvenience."
                    ];
            }
        }else{
                $responses = [
                    'success' => false,
                    'data' =>  '',
                    'message' => "Withdrawals are not allowed at this time! Please complete your kyc."
                ];
        }
            
            return response()->json($responses, 200);
    }
    public function WithdrawAmntGateway(Request $request){

        $userId = Auth::user()->id;
        return DB::transaction(function () use ($userId, $request) {
            // Acquire a lock and check for the last withdrawal request
            $lastRequest = WithdrawRequest::where('user_id', $userId)
                ->latest('created_at')
                ->lockForUpdate()
                ->first();
        
            if ($lastRequest && $lastRequest->created_at->diffInMinutes(now()) < 10) {
                $remainingTime = 10 - $lastRequest->created_at->diffInMinutes(now());
        
                $responses = [
                    'success' => false,
                    'data' => '',
                    'message' => "Please wait {$remainingTime} minutes before making another withdrawal request."
                ];
        
                return response()->json($responses, 200);
            }
        
            // If everything is fine, continue with your logic for processing the withdrawal request
            // ...
        
        

        WithdrawRequest::create([
            'user_id' => $userId,
        ]);
        
        if($request->user()->kyc_status == '1'){
        $wallet_type = $request->wallet_type;
        $userId=$request->user()->id;
        $slug = 'withdraw_percentage';
        $withdraw_max = wallet::where('user_id',$userId)->value('withdraw_max');
        $withdraw_min = wallet::where('user_id',$userId)->value('withdraw_min');
        $withdraw_percentage = Setting::where('type',$slug)->value('status');
        $withdraw_fly_percentage = Setting::where('type','withdraw_fly_percentage')->value('status');
        $withdraw_elite_percentage = Setting::where('type','withdraw_elite_percentage')->value('status');
        $withdraw_prime_percentage = Setting::where('type','withdraw_prime_percentage')->value('status');
        
        if($withdraw_percentage == '1' || $withdraw_fly_percentage == '1' || $withdraw_elite_percentage == '1' || $withdraw_prime_percentage == '1'){
            $percentage = wallet::where('user_id',$userId)->value('withdraw_percentage');
            if($wallet_type =='fly_wallet'){
                $percentage = wallet::where('user_id',$userId)->value('withdraw_fly_percentage');

            }elseif($wallet_type =='elite_wallet'){
                $percentage = wallet::where('user_id',$userId)->value('withdraw_elite_percentage');
            }elseif($wallet_type =='main_wallet'){
                $percentage = wallet::where('user_id',$userId)->value('withdraw_prime_percentage');
            }
            
            $perAmnt = intval($request->amount * $percentage / 100);
            // $withdraw_max = $perAmnt;
            // $withdraw_min = $perAmnt;
            $request->merge(['amount' => $perAmnt]);
            $rules = [
                'wallet_type' => ['required', 'string', 'max:255'],
                'amount' => ['required', 'integer', new Checkbalance($wallet_type)],
                'bank_id' => ['required', 'exists:banks,id'],
            ];
        }else{
            $rules = [
                'wallet_type' => ['required', 'string', 'max:255'],
                'amount' => ['required', 'integer', new Checkbalance($wallet_type)],
                'bank_id' => ['required', 'exists:banks,id'],
            ];
           
            $wallet_type = $request->wallet_type;

            if($wallet_type =='fly_wallet'){
                $withdraw_max = wallet::where('user_id',$userId)->value('withdraw_fly_max');
                $withdraw_min = wallet::where('user_id',$userId)->value('withdraw_fly_min');
   
                $rules['amount'][] = 'min:' . $withdraw_min;
                $rules['amount'][] = 'max:' . $withdraw_max;

            }elseif($wallet_type =='elite_wallet'){
                $withdraw_max = wallet::where('user_id',$userId)->value('withdraw_elite_max');
                $withdraw_min = wallet::where('user_id',$userId)->value('withdraw_elite_min');
   
                $rules['amount'][] = 'min:' . $withdraw_min;
                $rules['amount'][] = 'max:' . $withdraw_max;

            }elseif($wallet_type =='main_wallet'){
                $withdraw_max = wallet::where('user_id',$userId)->value('withdraw_prime_max');
                $withdraw_min = wallet::where('user_id',$userId)->value('withdraw_prime_min');
   
                $rules['amount'][] = 'min:' . $withdraw_min;
                $rules['amount'][] = 'max:' . $withdraw_max;


            }else{
                
                
            $rules['amount'][] = 'min:' . $withdraw_min;
            $rules['amount'][] = 'max:' . $withdraw_max;

            }
           

          
        }

       
       
           
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {


            //   if($validator->errors()->first('amount')){
            
            //     $res = [
            //         'success' => false,
            //           'data' =>  '',
            //         'message' => 'The amount field must not be less than '. $withdraw_max . ' and greater than ' . $withdraw_min
            //     ];
            //     return response()->json($res, 200);
                 
            //   }else{
                $res = [
                    'success' => false,
                      'data' =>  '',
                    'message' => $validator->errors()
                ];
                return response()->json($res, 200);
                } 
            // }
                
         $defultWithdrawalStatus = Setting::where('type','withdrawal_on_off')->value('status');
        // $WithdrawalStartDate = Setting::where('type','withdrawal_start_time')->value('value');
        // $WithdrawalStartEnd = Setting::where('type','withdrawal_end_time')->value('value');
         
        // $startTime = Carbon::createFromTimeString($WithdrawalStartDate);
        // $endTime = Carbon::createFromTimeString($WithdrawalStartEnd);
        // $currentTime = Carbon::now();
      
       // if ($currentTime->between($startTime, $endTime)) {
            
            
             
            
            if($defultWithdrawalStatus =='1'){
                    

                
                        if($request->wallet_type == 'main_wallet' && $request->user()->wallet->withdraw_ewallet == '0'){
                            $res = [
                                'success' => false,
                                'message' => "Payout service down! Please try again after some time."
                            ];
                            return response()->json($res, 200);
                
                        }
                        if($request->wallet_type == 'gold_membership_wallet' && $request->user()->wallet->withdraw_gwallet == '0'){
                            $res = [
                                'success' => false,
                                'message' => "Payout service down! Please try again after some time."
                            ];
                            return response()->json($res, 200);
                
                        }
                        if($request->wallet_type == 'fund_wallet' && $request->user()->wallet->withdraw_bwallet == '0'){
                            $res = [
                                'success' => false,
                                'message' => "Payout service down! Please try again after some time."
                            ];
                            return response()->json($res, 200);
                
                        }
                        if($request->wallet_type == 'bouns_wallet' && $request->user()->wallet->withdraw_twallet == '0'){
                            $res = [
                                'success' => false,
                                'message' => "Payout service down! Please try again after some time."
                            ];
                            return response()->json($res, 200);
                
                        }

                        if($request->wallet_type == 'elite_wallet' && $request->user()->wallet->withdraw_lwallet == '0'){
                            $res = [
                                'success' => false,
                                'message' => "Payout service down! Please try again after some time."
                            ];
                            return response()->json($res, 200);
                
                        }
                        if($request->wallet_type == 'fly_wallet' && $request->user()->wallet->withdraw_fwallet == '0'){
                            $res = [
                                'success' => false,
                                'message' => "Payout service down! Please try again after some time."
                            ];
                            return response()->json($res, 200);
                
                        }

                      
                        
                   
                        $bankDetails = Bank::where('user_id',$userId)->where('id',$request->bank_id)->first();
                         
                        
                        if($bankDetails->account != '' && $bankDetails->ifsc_code != '' && $bankDetails->bank_name != ''){
                            
                            $accountNum =$bankDetails->account;
                            $bank_name =$bankDetails->bank_name;
                            $ifsc =$bankDetails->ifsc_code;
                            $mobile =$request->user()->mobile;
                            $email =$request->user()->email;
                            $name =Str::lower($request->user()->name);
 
                            
                            
                            if (strlen($name) > 4) {
                                 
                                    //code...
                                            $amnt = $request->amount;
                                            if($amnt <= 500){
                                                $tx_charge = 7;
                                            }else{
                                                $tx_charge = $amnt*1.5/100;
                                            }
                                            
                                             $fin = $amnt-$tx_charge;
                                            $payable = round($fin);
                                            $timestamp = now()->format('YmdHis'); // Current timestamp
                                            $clientid = Str::random(20); // Random string (adjust the length as needed


                                            $todayStart = Carbon::today('Asia/Kolkata'); // Start of today
                                            $todayEnd = Carbon::tomorrow('Asia/Kolkata'); // Start of the next day (i.e., end of today)
                                            
                                            $dailyTotal = Withdrawal::where('user_id', $userId)
                                                ->whereIn('status', [0, 1]) // Filter for status 0 or 1
                                                ->whereBetween('created_at', [$todayStart, $todayEnd]) // Filter for transactions created today
                                                ->count();
                                                $dailyLimit = Wallet::where('user_id',$userId)->value('withdraw_tx_limit');
                                                // Check if the daily withdrawal limit has been exceeded
                                                if($wallet_type == 'fly_wallet'){
                                                    $dailyLimit = Wallet::where('user_id',$userId)->value('withdraw_fly_tx_limit'); 
                                                }elseif($wallet_type == 'elite_wallet'){
                                                    $dailyLimit = Wallet::where('user_id',$userId)->value('withdraw_elite_tx_limit');
                                                }elseif($wallet_type == 'main_wallet'){
                                                    $dailyLimit = Wallet::where('user_id',$userId)->value('withdraw_prime_tx_limit');
                                                }
                                               

                                         
                                        if ($dailyTotal < $dailyLimit) {
                                            


                                        $defultAutoWithdrawalStatus = Setting::where('type','auto_withdraw')->value('status');
                                            
                                         if($defultAutoWithdrawalStatus == '1'){

                                            $tokenData  = $this->generateToken();
                                           
                                            if($tokenData['error'] == false){
                                                
                                                ///////////////////////////////////////////////////////new //////////////////////////////////////////////////////
                                                
                                          
                                               
                                                //////////////////////////////////////////////////new end ////////////////////////////////////////////////////////
                                                if($ress['isError']=='1'){
                                                    $res = [
                                                        'success' => false,
                                                        'message' => $ress['msg']
                                                    ];
                                                    return response()->json($res, 200);
                                                }
                                                 $stus=$ress['dataCompleteGet']; 
                                                 $staus=$ress['status']; 
                                                 if($stus=='1'||$staus=='PROCESSING'){ 
                                                            $status = 0;
                                                            if($staus=='SUCCESS'){
                                                               $status = 1; 
                                                            }else{
                                                                 $status = 0; 
                                                            }
                                                            $txId=$ress['txnId']; 
                                                            $transaction = Withdrawal::create([
                                                                'user_id' => Auth::user()->id,
                                                                'amount' => $payable,
                                                                'tx_charge' => $tx_charge, 
                                                                'account_number' => $accountNum, 
                                                                'ifsc_code' => $ifsc, 
                                                                'user_details' => $txId, 
                                                                'wallet_type' => $wallet_type, 
                                                                'status'  => $status,
                                                            ]);
                                                            Wallet::where('user_id',Auth::user()->id)->decrement($wallet_type,$request->amount);
                                                            $wlt = $request->user()->wallet;
                                                            $closeAmnt = $wlt->$wallet_type;
                                                            $transactions = Transaction::create([
                                                                'user_id' => Auth::user()->id,
                                                                'tx_user' => Auth::user()->id,
                                                                'amount' => $payable,
                                                                'charges' => $tx_charge,
                                                                'type' => 'debit',
                                                                'tx_type' => 'withdraw',
                                                                'status'  => $status,
                                                                'wallet'  => $wallet_type,
                                                                'close_amount'  => $closeAmnt,
                                                                'tx_id'  => $txId,
                                                                'remark'  => 'withdraw  of  '.$payable.' amount',
                                                            
                                                            ]);
                                                            // $directs  = Auth::user()->directs;
                                                            // foreach($directs as $direct){
                                                            //     $direct_name = $direct->name;
                                                            //     $direct_mobile = $direct->mobile; 
                                                            //     $decoded_msg = urlencode($name);
                                                            //     $direct_names = urlencode($direct_name);
                                                            //     $msg = "Dear%20Mr%20$direct_names%20your%20s2paylife%20sponsor%20Mr%20$decoded_msg%20Today%20Withdrawal%20Rs%20$payable%20Gold%20Membership%20plan%20Today%20income%20Thank%20s2paylife";
                                                            //     $this->sendActiveMsg($msg,$direct_mobile);
                                        
    
                                                            // }
    
                                                            // $msg = "Congratulations%20Dear%20$decoded_msg%20($mobi)Your%20successfully%20Completed%20Activation%20prime%20pakage%20Rs%20$amnt%20Thank%20S2PAY";
                                                           
                                                            
                                                            $success['transaction_no'] = $txId;
                                                            $success['date'] = Carbon::parse($transaction->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s');
                                                            $success['bank_details'] = $bankDetails;
                                                            $responses = [
                                                                'success' => true,
                                                                'data' =>  $success,
                                                                'message' => "Withdrawal Request Generated successful."
                                                            ];
                                                 }else{ 
                                                        $msg=$ress['msg'];
    
                                                        if($msg == "Account doest not have sufficient balance."){
                                                            $msg = "Payout service down! Please try again after some time.";  
    
                                                        }
                                                        $responses = [
                                                            'success' => false,
                                                            'data' =>  '',
                                                            'message' => $msg
                                                        ];
                                                 }
                                             
                                            }else{
                                                    $responses = [
                                                                'success' => false,
                                                                'data' =>  '',
                                                                'message' => "Token Expired!Please try some time."
                                                    ];
                                            }
                                         

                                         }else{


                                            $status = 3;
                                            $transaction = Withdrawal::create([
                                                'user_id' => Auth::user()->id,
                                                'amount' => $payable,
                                                'tx_charge' => $tx_charge, 
                                                'account_number' => $accountNum, 
                                                'ifsc_code' => $ifsc,   
                                                'wallet_type' => $wallet_type, 
                                                'status'  => $status,
                                            ]);
                                            Wallet::where('user_id',Auth::user()->id)->decrement($wallet_type,$request->amount);
                                            $wlt = $request->user()->wallet;
                                            $closeAmnt = $wlt->$wallet_type;
                                            $transactions = Transaction::create([
                                                'user_id' => Auth::user()->id,
                                                'tx_user' => Auth::user()->id,
                                                'amount' => $payable,
                                                'charges' => $tx_charge,
                                                'type' => 'debit',
                                                'tx_type' => 'withdraw',
                                                'status'  => $status,
                                                'wallet'  => $wallet_type,
                                                'close_amount'  => $closeAmnt, 
                                                'remark'  => 'withdraw  of  '.$payable.' amount',
                                            
                                            ]);
                                            // $directs  = Auth::user()->directs;
                                            // foreach($directs as $direct){
                                            //     $direct_name = $direct->name;
                                            //     $direct_mobile = $direct->mobile; 
                                            //     $decoded_msg = urlencode($name);
                                            //     $direct_names = urlencode($direct_name);
                                            //     $msg = "Dear%20Mr%20$direct_names%20your%20s2paylife%20sponsor%20Mr%20$decoded_msg%20Today%20Withdrawal%20Rs%20$payable%20Gold%20Membership%20plan%20Today%20income%20Thank%20s2paylife";
                                            //     $this->sendActiveMsg($msg,$direct_mobile);
                        

                                            // }

                                            // $msg = "Congratulations%20Dear%20$decoded_msg%20($mobi)Your%20successfully%20Completed%20Activation%20prime%20pakage%20Rs%20$amnt%20Thank%20S2PAY";
                                           
                                             
                                            $success['date'] = Carbon::parse($transaction->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s');
                                            $success['bank_details'] = $bankDetails;
                                            $responses = [
                                                'success' => true,
                                                'data' =>  $success,
                                                'message' => "Successfully Raised a Request for amount $payable. Amount will be Credited to your Bank account with in 24 hours."
                                            ];

                                         } 
                                        }else{
                                            $responses = [
                                                'success' => false,
                                                'data' =>  '',
                                                'message' => "Daily withdrawal limit of 110 exceeded."
                                            ];
                                        }



                            }else{
                                $responses = [
                                    'success' => false,
                                    'data' =>  '',
                                    'message' => "The length of Name must be 20 characters or fewer."
                                ];
                            }
                            
                            
                        }else{
                            $responses = [
                                'success' => false,
                                'data' =>  '',
                                'message' => "Invalid Account details!Please Fill Correct Account Details."
                            ];
                        }

                    
                   
            }else{
                    $responses = [
                        'success' => false,
                        'data' =>  '',
                        'message' => "Service temporarily closed!withdrawal are currently unavailable. We apologize for the inconvenience."
                    ];
            }
        }else{
                $responses = [
                    'success' => false,
                    'data' =>  '',
                    'message' => "Withdrawals are not allowed at this time! Please complete your kyc."
                ];
        }
            
            return response()->json($responses, 200);
        });
    }


    //////////////////////////////////////////scan and pay////////////////////////////////////////////////////////////////////////////////////////////////////
    public function scanAndPay(Request $request){
        $userId = Auth::user()->id;
        DB::transaction(function () use ($userId, $request) {
            // Acquire a lock and check for the last withdrawal request
            $lastRequest = WithdrawRequest::where('user_id', $userId)
                ->latest('created_at')
                ->lockForUpdate()
                ->first();

        if ($lastRequest && $lastRequest->created_at->diffInMinutes(now()) < 5) {
                $remainingTime = 5 - $lastRequest->created_at->diffInMinutes(now());
                
                $responses = [
                    'success' => false,
                    'data' => '',
                    'message' => "Please wait {$remainingTime} minutes before making another withdrawal request."
                ];
                return response()->json($responses, 200);
            }
        });

        

        WithdrawRequest::create([
            'user_id' => $userId,
        ]);
        
        $scanpayStatus = Setting::where('type','scan_pay')->value('status');
        if($scanpayStatus =='1'){
        if($request->user()->kyc_status == '1'){
        $wallet_type = $request->wallet_type;
     
           $userId=$request->user()->id;
            $validator = Validator::make($request->all(),[
             'wallet_type' => ['required', 'string', 'max:255'],   
             'amount' => ['required', 'integer', 'min:10' ,new Checkbalance($wallet_type)],   
             'upi_id' => ['required','string','max:255'],   
            ]);
            
            if ($validator->fails()) {
                $res = [
                    'success' => false,
                      'data' =>  '',
                    'message' => $validator->errors()
                ];
                return response()->json($res, 200);
    
            }
           
         $defultWithdrawalStatus = Setting::where('type','scan_pay')->value('status');
        // $WithdrawalStartDate = Setting::where('type','withdrawal_start_time')->value('value');
        // $WithdrawalStartEnd = Setting::where('type','withdrawal_end_time')->value('value');
         
        // $startTime = Carbon::createFromTimeString($WithdrawalStartDate);
        // $endTime = Carbon::createFromTimeString($WithdrawalStartEnd);
        // $currentTime = Carbon::now();
      
       // if ($currentTime->between($startTime, $endTime)) {
            
            
             
            
            if($defultWithdrawalStatus =='1'){
                    
                     
                        if($request->wallet_type == 'main_wallet' && $request->user()->wallet->scan_ewallet == '0'){
                            $res = [
                                'success' => false,
                                'message' => "Payout service down! Please try again after some time."
                            ];
                            return response()->json($res, 200);
                
                        }
                        if($request->wallet_type == 'gold_membership_wallet' && $request->user()->wallet->scan_gwallet == '0'){
                            $res = [
                                'success' => false,
                                'message' => "Payout service down! Please try again after some time."
                            ];
                            return response()->json($res, 200);
                
                        }

                        if($request->wallet_type == 'fund_wallet' && $request->user()->wallet->scan_bwallet == '0'){
                            $res = [
                                'success' => false,
                                'message' => "Payout service down! Please try again after some time."
                            ];
                            return response()->json($res, 200);
                
                        }
                        if($request->wallet_type == 'bouns_wallet' && $request->user()->wallet->scan_twallet == '0'){
                            $res = [
                                'success' => false,
                                'message' => "Payout service down! Please try again after some time."
                            ];
                            return response()->json($res, 200);
                
                        }
                        
                        if($request->wallet_type == 'elite_wallet' && $request->user()->wallet->scan_lwallet == '0'){
                            $res = [
                                'success' => false,
                                'message' => "Payout service down! Please try again after some time."
                            ];
                            return response()->json($res, 200);
                
                        }

                        if($request->wallet_type == 'fly_wallet' && $request->user()->wallet->scan_fwallet == '0'){
                            $res = [
                                'success' => false,
                                'message' => "Payout service down! Please try again after some time."
                            ];
                            return response()->json($res, 200);
                
                        }



                      
                        
                   
                        $bankDetails = Bank::where('user_id',$userId)->latest()->first();
                         
                        if($bankDetails){

                         
                        if($bankDetails->account != '' && $bankDetails->ifsc_code != '' && $bankDetails->bank_name != ''){
                            
                            $accountNum =$bankDetails->account;
                            $bank_name =$bankDetails->bank_name;
                            $ifsc =$bankDetails->ifsc_code;
                            $mobile =$request->user()->mobile;
                            $email =$request->user()->email;
                            $name =Str::lower($request->user()->name);
 
                            
                            $upiId = $request->upi_id;
                            if (strlen($name) > 4) {
                                 
                                    //code...
                                            $amnt = $request->amount;
                                            if($amnt <= 400){
                                                $tx_charge = 5;
                                            }else{
                                                $tx_charge = $amnt*1.5/100;
                                            }
                                            
                                             $fin = $amnt-$tx_charge;
                                            $payable = round($fin);
                                            $timestamp = now()->format('YmdHis'); // Current timestamp
                                            $clientid = Str::random(10); // Random string (adjust the length as needed
                                         
                                           // $tokenData  = $this->generateToken();
                                
                                        // if($tokenData['error'] == false){
                                            
                                            ///////////////////////////////////////////////////////new //////////////////////////////////////////////////////
                                            
                                    
                                            //////////////////////////////////////////////////new end ////////////////////////////////////////////////////////
                                   
                                             $stus=$ress['status']; 
                                             if($stus=='SUCCESS' || $stus=='PENDING' || $stus=='pending' || $stus=='PROCESSING'){ 
                                   
                                                        if($stus=='SUCCESS'){
                                                           $status = 1; 
                                                        }else{
                                                             $status = 0; 
                                                        }
                                     
                                                        $transaction = Withdrawal::create([
                                                            'user_id' => Auth::user()->id,
                                                            'amount' => $payable,
                                                            'tx_charge' => $tx_charge, 
                                                            'account_number' => $upiId, 
                                                            'ifsc_code' => '', 
                                                            'user_details' => $clientid, 
                                                            'wallet_type' => $wallet_type, 
                                                            'status'  => $status,
                                                        ]);
                                                        Wallet::where('user_id',Auth::user()->id)->decrement($wallet_type,$request->amount);
                                                        $wlt = $request->user()->wallet;
                                                        $closeAmnt = $wlt->$wallet_type;
                                                        $transactions = Transaction::create([
                                                            'user_id' => Auth::user()->id,
                                                            'tx_user' => Auth::user()->id,
                                                            'amount' => $payable,
                                                            'charges' => $tx_charge,
                                                            'type' => 'debit',
                                                            'tx_type' => 'withdraw',
                                                            'status'  => $status,
                                                            'wallet'  => $wallet_type,
                                                            'close_amount'  => $closeAmnt,
                                                            'tx_id'  => $clientid,
                                                            'remark'  => 'withdraw  of  '.$payable.' amount',
                                                        
                                                        ]);
                                                        // $directs  = Auth::user()->directs;
                                                        // foreach($directs as $direct){
                                                        //     $direct_name = $direct->name;
                                                        //     $direct_mobile = $direct->mobile; 
                                                        //     $decoded_msg = urlencode($name);
                                                        //     $direct_names = urlencode($direct_name);
                                                        //     $msg = "Dear%20Mr%20$direct_names%20your%20s2paylife%20sponsor%20Mr%20$decoded_msg%20Today%20Withdrawal%20Rs%20$payable%20Gold%20Membership%20plan%20Today%20income%20Thank%20s2paylife";
                                                        //     $this->sendActiveMsg($msg,$direct_mobile);
                                    

                                                        // }

                                                        // $msg = "Congratulations%20Dear%20$decoded_msg%20($mobi)Your%20successfully%20Completed%20Activation%20prime%20pakage%20Rs%20$amnt%20Thank%20S2PAY";
                                                       
                                                       
                                                        $success['transaction_no'] = $clientid;
                                                        $success['date'] = Carbon::parse($transaction->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s');
                                                        $success['bank_details'] = $bankDetails;
                                                        $responses = [
                                                            'success' => true,
                                                            'data' =>  $success,
                                                            'message' => "Withdrawal Request Generated successful."
                                                        ];
                                             }else{ 
                                                    $msg=$ress['msg'];

                                                    if($msg == "Account doest not have sufficient balance."){
                                                        $msg = "Payout service down! Please try again after some time.";  

                                                    }
                                                    $responses = [
                                                        'success' => false,
                                                        'data' =>  '',
                                                        'message' => $msg
                                                    ];
                                             }
                                         
                                        // }else{
                                        //         $responses = [
                                        //                     'success' => false,
                                        //                     'data' =>  '',
                                        //                     'message' => "Token Expired!Please try some time."
                                        //         ];
                                        // }
                                     
                        
                                 
                            
                            }else{
                                $responses = [
                                    'success' => false,
                                    'data' =>  '',
                                    'message' => "The length of Name must be 20 characters or fewer."
                                ];
                            }
                            
                            
                        }else{
                            $responses = [
                                'success' => false,
                                'data' =>  '',
                                'message' => "Invalid Account details!Please Fill Correct Account Details."
                            ];
                        }
                        }else{
                            $responses = [
                                'success' => false,
                                'data' =>  '',
                                'message' => "Invalid Account details!Please Fill Correct Account Details."
                            ];
                        }

                  
                   
            }else{
                    $responses = [
                        'success' => false,
                        'data' =>  '',
                        'message' => "Service temporarily closed!withdrawal are currently unavailable. We apologize for the inconvenience."
                    ];
            }
        }else{
                $responses = [
                    'success' => false,
                    'data' =>  '',
                    'message' => "Withdrawals are not allowed at this time! Please complete your kyc."
                ];
        }

        }else{

            $responses = [
                'success' => false,
                'data' =>  '',
                'message' => "server down try again later."
            ];

         
        }
            return response()->json($responses, 200);
    }








    public function checkStatus(Request $request){

        $validator = Validator::make($request->all(),[ 
        'trx_id' => ['required','string','max:255','exists:withdrawals,user_details'],   
        ]);
        
        if ($validator->fails()) {
            $res = [
                'success' => false,
                  'data' =>  '',
                'message' => $validator->errors()
            ];
            return response()->json($res, 200);

        }

        $stus=$ress['status']; 

        if($stus == 'SUCCESS'){

            $res = [
                'success' => true,
                  'data' =>  '',
                'message' => 'withdraw amount transfer user wallet successfully.'
            ];

            $exists = Withdrawal::where('user_details',$trx_id)->where('status',0)->first();
            if($exists){ 
               
                    $exists->status = 1;
                    $exists->save();
                    $existsTrx = Transaction::where('tx_id',$trx_id)->where('status',0)->first();
                        if($existsTrx){
                            $existsTrx->status = 1;
                            $existsTrx->save();
                        }
            }

            // $exists->status = 1;
            // $exists->save();
            // $existsTrx = Transaction::where('tx_id',$agent_id)->where('status',0)->first();
            // if($existsTrx){
            //     $existsTrx->status = 1;
            //     $existsTrx->save();
            // }

        }elseif($stus == 'FAILED'){ 
            $res = [
                'success' => false,
                  'data' =>  '',
                'message' => 'trnsaction failed!'
            ];

            $exists = Withdrawal::where('user_details',$trx_id)->where('status',0)->first();
            if($exists){ 
                    $amount = $exists->amount + $exists->tx_charge;
                    $userId = $exists->user_id;
                    $wallet_type = $exists->wallet_type;


                    $exists->status = 2; 
                    $exists->save();
                    Wallet::where('user_id',$userId)->increment($wallet_type,$amount);
                    $existsTrx = Transaction::where('tx_id',$trx_id)->where('status',0)->first();
                    if($existsTrx){
                        $existsTrx->status = 2;
                        $existsTrx->save();
                    }

                }
        }else{
            $res = [
                'success' => false,
                  'data' =>  '',
                'message' => 'trnsaction pending! please wait.......'
            ];
        }

        return response()->json($res, 200);


    }


    public function callBack_ScanPay(Request $request){
        $agent_id = $request->orderId;
     
        $exists = Withdrawal::where('user_details',$agent_id)->where('status',0)->first();
        if($exists){ 
            if($request->status == 'SUCCESS'){
                $exists->status = 1;
                $exists->save();
                $existsTrx = Transaction::where('tx_id',$agent_id)->where('status',0)->first();
                    if($existsTrx){
                        $existsTrx->status = 2;
                        $existsTrx->save();
                    }
            }else{
                if($request->status == 'FAILED'){
                    $amount = $exists->amount + $exists->tx_charge;
                    $userId = $exists->user_id;
                    $wallet_type = $exists->wallet_type;


                    $exists->status = 2; 
                    $exists->save();
                    Wallet::where('user_id',$userId)->increment($wallet_type,$amount);
                    $existsTrx = Transaction::where('tx_id',$agent_id)->where('status',0)->first();
                    if($existsTrx){
                        $existsTrx->status = 2;
                        $existsTrx->save();
                    }
                }
                
            }
        }
        
        
   }
    
    //////////////////////////////////////////scan and pay////////////////////////////////////////////////////////////////////////////////////////////////////

    public function sendActiveMsg($msg,$mobi){
     
       
        $apiKey = "71a9b0fbe3cb414583372e7c5664a5b4";
         
          
        $ch = curl_init();
        
   
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
    
    
    public function withdrawHistory(Request $request)
    {
        $perPage = 20;
        $currentPage = 1;
        
        // Retrieve the status from the request
        $status = $request->input('status');  // Assuming 'status' is sent via request
    
        // Build the base query
        $withdrawalQuery = $request->user()->withdrawals()->orderBy('created_at', 'desc');
    
        // Apply status filter if status is provided
        if (!is_null($status)) {
            $withdrawalQuery->where('status', $status);
        }
    
        // Paginate the results
        $filteredTransactions = $withdrawalQuery->paginate($perPage, ['*'], 'page', $currentPage);
    
        // Manually convert the created_at to IST if needed
        $filteredTransactions->getCollection()->transform(function ($transaction) {
            $transaction->created_at = \Carbon\Carbon::parse($transaction->created_at)
                ->timezone('Asia/Kolkata')  // Convert to IST
                ->format('Y-m-d H:i:s');    // Format as desired
            return $transaction;
        });
    
        // Prepare the response
        if ($filteredTransactions->count() > 0) {
            $response = [
                'success' => true,
                'data' => $filteredTransactions->items(),
                'pagination' => [
                    'current_page' => $filteredTransactions->currentPage(),
                    'last_page' => $filteredTransactions->lastPage(),
                    'total_items' => $filteredTransactions->total(),
                ],
                'message' => 'Withdrawal history fetched successfully.',
            ];
        } else {
            $response = [
                'success' => true,
                'data' => [],
                'pagination' => [
                    'current_page' => 0,
                    'last_page' => 0,
                    'total_items' => 0,
                ],
                'message' => 'No withdrawal history found!',
            ];
        }
    
        return response()->json($response, 200);
    }
    
    
    public function generateToken(){
             $res['error'] = true;
              $curl = curl_init();
    
            curl_close($curl);
            
             if(empty($response['errors'])){
                 
                 $res['error'] = false;
                 $res['token'] = $response['payload']['token'];
             }
             
             return $res;
    }
    
    public function callBack_Payout(Request $request){
         $agent_id = $request->orderId;
      
         $exists = Withdrawal::where('user_details',$agent_id)->where('status',0)->first();
         if($exists){ 
             if($request->status == 'SUCCESS'){
                 $exists->status = 1;
                 $exists->save();
                 $existsTrx = Transaction::where('tx_id',$agent_id)->where('status',0)->first();
                 if($existsTrx){
                     $existsTrx->status = 1;
                     $existsTrx->save();
                 }
             }else{
                 if($request->status == 'FAILED'){
                     $amount = $exists->amount;
                     $userId = $exists->user_id;
                     $wallet_type = $exists->wallet_type;
                     $exists->status = 2; 
                     $exists->save();
                     $existsTrx = Transaction::where('tx_id',$agent_id)->where('status',0)->first();
                     if($existsTrx){
                         $existsTrx->status = 2;
                         $existsTrx->save();
                     }
                     Wallet::where('user_id',$userId)->increment($wallet_type,$amount);
                 }
                 
             }
             
         }
         
         
    }
    public function callBack_Click_Payout(Request $request){
         $agent_id = $request->agent_id;
      
         $exists = Withdrawal::where('user_details',$agent_id)->where('status',0)->first();
         if($exists){ 
             if($request->status == 'SUCCESS'){
                 $exists->status = 1;
                 $exists->save();
                 $existsTrx = Transaction::where('tx_id',$agent_id)->where('status',0)->first();
                 if($existsTrx){
                     $existsTrx->status = 1;
                     $existsTrx->save();
                 }
             }else{
                 if($request->status == 'FAILED' || $request->status == 'Failed'){
                     $amount = $exists->amount;
                     $userId = $exists->user_id;
                     $wallet_type = $exists->wallet_type;
                     $exists->status = 2; 
                     $exists->save();
                     $existsTrx = Transaction::where('tx_id',$agent_id)->where('status',0)->first();
                     if($existsTrx){
                         $existsTrx->status = 2;
                         $existsTrx->save();
                     }
                     Wallet::where('user_id',$userId)->increment($wallet_type,$amount);
                 }
                 
             }
             
         }
         
         
    }
    public function callBack_New_Click_Payout(Request $request){
         $agent_id = $request->txnId;
      
         $exists = Withdrawal::where('user_details',$agent_id)->where('status',0)->first();
         if($exists){ 
             if($request->status == 'SUCCESS'){
                 $exists->status = 1;
                 $exists->save();
                 $existsTrx = Transaction::where('tx_id',$agent_id)->where('status',0)->first();
                 if($existsTrx){
                     $existsTrx->status = 1;
                     $existsTrx->save();
                 }
             }else{
                 if($request->status == 'FAILED'){
                     $amount = $exists->amount;
                     $charge = $exists->tx_charge;
                     $pay = $charge + $amount;
                     $userId = $exists->user_id;
                     $wallet_type = $exists->wallet_type;
                     $exists->status = 2; 
                     $exists->save();
                     $existsTrx = Transaction::where('tx_id',$agent_id)->where('status',0)->first();
                     if($existsTrx){
                         $existsTrx->status = 2;
                         $existsTrx->save();
                     }
                     Wallet::where('user_id',$userId)->increment($wallet_type,$pay);
                 }
                 
             }
             
         }
         
         
    }
    
    
}
