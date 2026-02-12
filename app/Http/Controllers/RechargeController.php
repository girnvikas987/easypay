<?php

namespace App\Http\Controllers;


use App\Helper\Distribute;
use App\Models\Circle;
use App\Models\DailyIncome;
use App\Models\Income;
use App\Models\Operator;
use App\Models\PlanRechargeReferralIncome;
use App\Models\Slab;
use App\Models\Recharge;
use App\Models\Team;
use App\Models\PlanRechargeRoyalty;
use App\Models\PlanTrip;
use App\Models\Provider;
use App\Models\Test;
use App\Models\Transaction; 
use App\Models\User;
use App\Models\RechargeInvestment;
use App\Models\RechargeTripAchiver;
use App\Rules\validateRechargePackage;
use App\Models\RechargePackage;
use App\Models\Setting;
use App\Models\Support;
use Illuminate\Validation\Rule;
use App\Models\Wallet;
use App\Models\WalletType;
use App\Rules\Checkbalance;
use App\Rules\CheckRechargeBalance;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stringable;
use Validator;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class RechargeController extends Controller
{

  
 public function getOperatorData(Request $request){
    
      $validator = Validator::make($request->all(),[
            'mobile' => ['required', 'numeric'], 
        ]);
        
       
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 200);

        }
               $mobile = $request->mobile;
    
     
     $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://www.kwikapi.com/api/v2/operator_fetch_v2.php',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => array('api_key' => '89ef91-7f1fb4-929d67-514b34-14f28c','number' => $mobile),
      CURLOPT_HTTPHEADER => array(
        'Cookie: PHPSESSID=5aee25cc19bc9808829f233baeb5a16c'
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
  
    $result = json_decode($response,true);
    
    $re_status =Str::lower($result['success']);
        // $re_status = 'success';
        ///////////////////////////////////////////////// Recharge Api End //////////////////////////////////////////////////////////
        if($re_status == true){
            
            $_msg = "Recharge successfully.";
            $responses = [
                'success' => true,
                'data' => $result['details'], 
                'message' => $_msg
            ];

        }else{
            $responses = [
                'success' => false,
                'data' => '',
                'message' => "Utilities service down! Please try again after some time."
            ]; 
        }
        
        return response()->json($responses);
     
 }
 
 public function viewPlan(Request $request){
    
      $validator = Validator::make($request->all(),[
            'operator' => ['required', 'string','max:255'],
            'circle' => ['required', 'string','max:255']
        ]);
        
       

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 200);

        }
               $state_code = $request->circle;
               $opid = $request->operator;
    
              $curl = curl_init();
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://www.kwikapi.com/api/v2/recharge_plans.php',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array('api_key' => '89ef91-7f1fb4-929d67-514b34-14f28c','state_code' => $state_code,'opid' => $opid),
              CURLOPT_HTTPHEADER => array(
            
              ),
            ));
            
            $response = curl_exec($curl);
            
            curl_close($curl);
            
            $result = json_decode($response,true);
            
            $re_status =Str::lower($result['success']);
                // $re_status = 'success';
               
                ///////////////////////////////////////////////// Recharge Api End //////////////////////////////////////////////////////////
                if($re_status == true){
                    
                    $_msg = "Recharge successfully.";
                    $responses = [
                        'success' => true,
                        'data' => $result['plans'], 
                        'message' => $_msg
                    ];
        
                }else{
                    $responses = [
                        'success' => false,
                        'data' => '',
                        'message' => $result['message']
                    ]; 
                }
                
                return response()->json($responses);
     
 }
 
  public function rechargeRequest(Request $request){
      
      
        $wallet = $request->wallet_type;
   
    $validator = Validator::make($request->all(),[
            'mobile' => ['required', 'numeric'],
            'amount' => ['required', 'numeric','min:10',new CheckRechargeBalance($wallet)],
            'operator' => ['required', 'string','max:255'],
            'circle' => ['required', 'string','max:255'],
            'recharge_type' => ['required', 'string','max:255']
        ]);
        
       
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 200);

        }
        
        $mobile = $request->mobile;
        $amount = $request->amount;
        $operator = $request->operator;
        $circle = $request->circle;
        $recharge_type = $request->recharge_type;
        $timestamp = now()->format('His'); // Current timestamp
        //$randomString = Str::random(2); // Random string (adjust the length as needed)

        $transactionId = $timestamp;
      
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://www.kwikapi.com/api/v2/recharge.php?api_key=89ef91-7f1fb4-929d67-514b34-14f28c&number='.$mobile.'&amount='.$amount.'&opid='.$operator.'&state_code='.$circle.'&order_id='.$transactionId,
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
        $result = json_decode($response,true);
        
        $re_status =Str::lower($result['status']);
        // $re_status = 'success';
        ///////////////////////////////////////////////// Recharge Api End //////////////////////////////////////////////////////////
        if($re_status == 'failure' || $re_status == 'failed'){
            $recharge_status = $re_status;

            $recharge_msg = $result['message'];
            if($recharge_msg != " "){
                $_msg = $recharge_msg;
            }else{
                $_msg = "Recharge Failed!";
            }
            $responses = [
                'success' => false,
                'data' => $result,
                'recharge_status' => $recharge_status,
                'message' => $_msg
            ];

        }else{
            
                $api_tansasction_id = $result['order_id'];
                $recharge_status = $re_status;
                $status = 0;
                $userId = Auth::user()->id;
                $username = Auth::user()->username;
                
                DB::beginTransaction();
                try {
                     
                    $transaction = Recharge::create([
                            'user_id' => $userId,
                            'api_tansasction_id' => $api_tansasction_id,
                            'mobile' => $mobile,
                            'amount' => $amount,
                            'tx_id'=> $transactionId,
                            'api_status' => $recharge_status,
                            'wallet_type' => $wallet,
                            'recharge_type' => $recharge_type,
                            'remark' => "Recharge successful for $mobile by $username.",
                            'status'  => $status
                    ]);
                    
                    $wlts=Wallet::where('user_id',$userId)->first();
                    
                    $detectwallet = $wlts->$wallet;

                    /////////////////////////////////////////////////////////////////////////////////////////// both wallet ///////////////////////////////////////////////
                  
                    Wallet::where('user_id',Auth::user()->id)->decrement($wallet,$amount);
 
                    $wlt = $request->user()->wallet;
                    $closeAmnt = $wlt->$wallet;
                    $transactionse = Transaction::create([
                        'user_id' => $userId,
                        'tx_user' => $userId,
                        'amount' => $amount,
                        'charges' => 0,
                        'type' => 'debit',
                        'tx_type' => 'recharge',
                        'status'  => $status,
                        'wallet'  => $wallet,
                        'close_amount'  => $closeAmnt,
                        'tx_id'  => $transactionId,
                        'remark'  => 'Recharge  of  '.$amount.' amount',
                    
                    ]);
                    
                    
                  

                    /////////////////////////////////////////////////////////////////////////////////////////// both wallet ///////////////////////////////////////////////
                    
                    $usera = User::where('id',$userId)->first();
   
                    $active_user = Auth::user()->active_status;
                     $cashbackAmnt = 0;
                    
                        
                        
                    if($recharge_status == 'success'){ 
                          
                                 $package  = $usera->package;
                            $name  = $usera->name;
                            $mobi  = $usera->mobile;
                           
                             
                        /////////////////////////////////////////first distrbute self income //////////////////////////////////////

                        if($package != null){

                             if($package =='prime'){
                                $self_incomes = PlanRechargeReferralIncome::where('status',1)->where('source','self_recharge')->where('package',1)->first();

                            }else{
                                $self_incomes = PlanRechargeReferralIncome::where('status',1)->where('source','self_recharge')->where('package',2)->first();
                            }
                            if($self_incomes){ 
                                 
                                    $comm = $amount * $self_incomes->value/100;
                                    $wallet = 'main_wallet';
                               
                                    Transaction::create([
                                        'user_id' => $userId, 
                                        'tx_user' => $userId, 
                                        'type' => 'credit',
                                        'tx_type' => 'income',
                                        'wallet' => 'main_wallet',
                                        'income' => 'self_recharge',
                                        'status' => 1,                        
                                        'amount' => $comm,
                                        'remark' => "Received Self of amount Rs $comm from Recharge of $mobile.",
                                    ]);
                                    $cashbackAmnt = $comm;
                                    Wallet::where('user_id',$userId)->increment($wallet,$comm);
                                    Income::where('user_id',$userId)->increment($self_incomes->source,$comm);                 
                                    DailyIncome::where('user_id',$userId)->increment($self_incomes->source,$comm);    

                            }
                           

                            
    
    
    
                        /////////////////////////////////////////first distrbute self income //////////////////////////////////////
                          
                            // Distribute::DirectIncome($transaction);
                            Distribute::RechargeReferralIncome($transaction);
                            

                        }
                            
                       $transaction->status = 1;
                       $transaction->save();
                       
                            $_msg = "Recharge successfully.";
                            $responses = [
                                'success' => true,
                                'data' => $cashbackAmnt,
                                'recharge_status' => $recharge_status,
                                'message' => $_msg
                            ];
                    }else{
                        
                        $_msg =  'Recharge Pending.';
                        $responses = [
                            'success' => true,
                            'data' => '',
                            'recharge_status' => $recharge_status,
                            'message' => $_msg
                        ];
                    }
                    DB::commit();     
                    
        
                } catch (\Exception $e) {
                    //throw $th;
                    DB::rollBack();
                    $responses = [
                        'success' => false,
                        'data' =>  $e,
                        'recharge_status' => $recharge_status,
                        'message' => "Something wrong!."
                    ];
                } 
        }
        
     return response()->json($responses, 200);

 
 
  }
 
 /////////////////////////////hotel APi ///////////////////////////////////////////////////////////////////////////////////////////////////////
 public function callback_recharge(Request $request){
        
                       $status = $request->query('status');
                       $transId = $request->query('payid');
                       
                       $re_status = Str::lower($status);
                       
                      
                      
                        $exists = Recharge::where('tx_id',$transId)->first();
                        if($exists){
                            
                                if(($re_status == 'failure' || $re_status == 'failed') && $exists->api_status != 'success'){
                                        $exists->api_status = $re_status; 
                                        $amount = $exists->amount;
                                        $userId = $exists->user_id;
                                        $wallet = $exists->wallet;
                                        $active_status = User::where('id',$userId)->value('active_status'); 
                                        Wallet::where('user_id',$userId)->increment($wallet,$amount);
                                        $exists->status = 0;
                                        $exists->save();
                                }else{


                                    
                                    if($exists->api_status != 'success' && $re_status =='success'){
                                                $usera = User::where('id',$exists->user_id)->first();
                                                $package  = $usera->package;
                                                $name  = $usera->name;
                                                $mobi  = $usera->mobile;
                                            
                             
                        /////////////////////////////////////////first distrbute self income //////////////////////////////////////

                                     if($package != null){

                                                if($package =='prime'){
                                                    $self_incomes = PlanRechargeReferralIncome::where('status',1)->where('source','self_recharge')->where('package',1)->first();

                                                }else{
                                                    $self_incomes = PlanRechargeReferralIncome::where('status',1)->where('source','self_recharge')->where('package',2)->first();
                                                }
                                                if($self_incomes){ 
                                                        $amount = $exists->amount;
                                                        $comm = $amount * $self_incomes->value/100;
                                                        $wallet = 'main_wallet';
                                                       $userId = $exists->user_id;
                                                       $transaction =  Transaction::create([
                                                            'user_id' => $userId, 
                                                            'tx_user' => $userId, 
                                                            'type' => 'credit',
                                                            'tx_type' => 'income',
                                                            'wallet' => 'main_wallet',
                                                            'income' => 'self_recharge',
                                                            'status' => 1,                        
                                                            'amount' => $comm,
                                                            'remark' => "Received Self of amount Rs $comm from Recharge of $mobi.",
                                                        ]);
                                                        Wallet::where('user_id',$userId)->increment($wallet,$comm);
                                                        Income::where('user_id',$userId)->increment($self_incomes->source,$comm);                 
                                                        DailyIncome::where('user_id',$userId)->increment($self_incomes->source,$comm);    

                                                }
                                            

                                                
                        
                        
                        
                                            /////////////////////////////////////////first distrbute self income //////////////////////////////////////
                                            
                                                // Distribute::DirectIncome($transaction);
                                                Distribute::RechargeReferralIncome($transaction);

                                            }
                            
                                        $exists->api_status = $re_status;
                                        $exists->status = 1;
                                        $exists->save();
                                        
                                    }else{
                                        $exists->api_status = $re_status;
                                        $exists->status = 0;
                                        $exists->save();
                                    }
                                    
                                   
                               }
                             
                        //     $test = Test::create([
                        //         'remark' => $re_status,
                        //         'updated_at' => now(),
                        //         'created_at' => now(),
                        //   ]);
                    }
        
            }    
          
 
 
 

}

 