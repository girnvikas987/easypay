<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Team;
use App\Models\PlanEbikeRoyalty;
use App\Models\Transaction;
use App\Models\EbikePackage;
use App\Models\EbikeInvestment;
use Illuminate\Validation\Rule;
use App\Helper\Distribute;
use App\Models\Setting;
use App\Models\WalletType;
use App\Rules\ValidateEbikePackage; 
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;
 
class EbikeController extends Controller
{
    public function  getPackage(){
    $wallets = WalletType::whereIn('id',['1','2','3','8'])->get();
     $lists =  EbikePackage::where('status',1)->get();
         if($lists){
            $response = [
              'success' => true, 
              'data' => $lists, 
              'wallets'=>   $wallets,
              'message' => "Ebike Package fetch Successfully."
            ];
         }else{
             
             $response = [
              'success' => false, 
              'data' => '', 
              'wallets'=>   $wallets,
              'message' => "Ebike Package data not found!"
            ];
             
         }       
        return response()->json($response, 200);
  } 

    public function buyPackage(Request $request){


        $defultregisterStatus = Setting::where('type','ebike_status')->value('status');
        if($defultregisterStatus == '0'){
            $resp = [
                'success' => false,
                'data' => '',
                'message' => "Payout service down! Please try again after some time."
            ]; 
        }



        $mobile = $request->mobile;
        $user = User::where('mobile',$mobile)->first();
        if($user->kyc_status == '0'){
            $res = [
                'success' => false,
                'message' => "User KYC Incomplete. Try again after KYC verification!"
            ];
            return response()->json($res, 200); 
        }
        if($request->user()->kyc_status == '0'){
            $res = [
                'success' => false,
                'message' => "Please complete your kyc!"
            ];
            return response()->json($res, 200);


        }

       
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
 
        if($request->wallet_type == 'fund_wallet' && $request->user()->bwallet_status == '0'){
            $res = [
                'success' => false,
                'message' => "Payout service down! Please try again after some time."
            ];
            return response()->json($res, 200);

        }
        if($request->wallet_type == 'bouns_wallet' && $request->user()->twallet_status == '0'){
            $res = [
                'success' => false,
                'message' => "Payout service down! Please try again after some time."
            ];
            return response()->json($res, 200);

        }
 
            $validator = Validator::make($request->all(),[
                'mobile' => ['required', 'string', 'max:255' , 'exists:users,mobile'],
                'package' => ['required', 'integer', new ValidateEbikePackage($request)],   
            ]); 
        if ($validator->fails()) {
            $res = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($res, 200);

        }
        
        $resp=$this->make_topup($request);

     
         
        return response()->json($resp, 200);
          
        
        
    }
    
    public function make_topup(Request $request){
        $wallet_type=$request->wallet_type;
        $usera = User::where('mobile',$request->mobile)->first();
        $alreadyActive =EbikeInvestment::where('user_id',$usera->id)->first();
        $mobile =  $request->mobile;
            if(!$alreadyActive){
                
                $pkgDetails = EbikePackage::where('id',$request->package)->first();
            if($pkgDetails->type=='fix'){
                $amnt = $pkgDetails->amount;
            }else{
                $amnt = $request->amount;
            }
            DB::beginTransaction();
            try {
        
                $invest = EbikeInvestment::create([
    
                    'user_id' => $usera->id,
                    'tx_user' => Auth::user()->id,
                    'package_id' => $request->package,
                    'days' => 0,
                    'pair_days' => 0,
                    'amount' => $amnt,
                    'status'  => 1
                ]);
                
                // $usera->active_status = 1;
                // $usera->active_date = now();
                // $usera->save();
    
                Wallet::where('user_id',Auth::user()->id)->decrement($wallet_type,$amnt);
                // $team = Team::where('user_id',$usera->id)->first();
                // $team->active_status = 1;
                // $team->save();
                $closed_amount = $request->user()->wallet->$wallet_type;
                $transaction = Transaction::create([
                    'user_id' => Auth::user()->id,
                    'tx_user' => $usera->id,
                    'amount' => $amnt,
                    'type' => 'debit',
                    'tx_type' => 'ebike_topup',
                    'status'  => 1,
                    'wallet'  => $wallet_type,
                    'tx_id'  => $invest->id,
                    'close_amount'  => $closed_amount,
                    'remark'  => 'E-Bike package of  '.$mobile.'activeted',
                
                ]);


    
                 
                 Distribute::DitrubteBinaryIdAdd($transaction);
                // Distribute::EbikeReferralIncome($transaction);
                // Distribute::EbikeBoosterIncome($transaction);
                
               
                // Wallet::where('user_id',$usera->id)->increment('bouns_wallet',$amnt);
                DB::commit();
                $user = $usera;
                $name = $user->name;
                $mobnile = $user->mobile;
                $decoded_msg = urlencode($name);
                $msg = "Congratulations%20Dear%20$decoded_msg%20($mobnile)Your%20successfully%20Completed%20Activation%20Ebike%20pakage%20Rs%206000%20Thank%20S2PAY";
                $user->ebike_pkg = $amnt;
                $user->active_status = 1;
                $user->save();
                // ActivationMail::dispatch($user);
                $this->sendActiveEbikeMsg($msg,$mobnile);
                $response = [
                    'success' => true,
                    'data' => $amnt,
                    'active_datetime' => Carbon::parse($invest->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
                    'message' => "Ebike Package Active successfull."
                ]; 
            } catch (\Exception $e) {
                //throw $th;
                DB::rollBack();
                
                 $response = [
                    'success' => false,
                    'data' => $e,
                    'message' => "Something Wrong."
                ]; 
            }
                
            }else{
                $response = [
                    'success' => false,
                    'data' => '',
                    'message' => "This Package Already Activeted.."
                ]; 
            }
            
            return $response;
    }
    
    public function sendActiveEbikeMsg($msg,$mobnile){
     
       
            $apiKey = "71a9b0fbe3cb414583372e7c5664a5b4";
             
              
            $ch = curl_init();
            
            // Set the URL and other options
            curl_setopt($ch, CURLOPT_URL, "http://whatsapp.click4bulksms.in/wapp/api/send?apikey=$apiKey&mobile=$mobnile&msg=$msg");
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
  
  
  public function getRoyalty(Request $request){
       $id = $request->user()->id;
       $gen = $request->user()->team->gen;
       $pkgAmnt = $request->user()->ebike_pkg;
       $activeDate = $request->user()->active_date; 
        // Calculate days from active_date to today
        $activeDate = Carbon::parse($activeDate);
        $today = Carbon::now();
        $teams = array($id);
        $lvlteam = array();
        $daysCount = $activeDate->diffInDays($today);
        $getRoyalty = PlanEbikeRoyalty::where('status',1)->where('id','!=','1')->get();
      if($getRoyalty){
          foreach($getRoyalty as $royalty){
              $teamRequired = $royalty->team_required;
              $level = 5;// $royalty->level;
               
               if($pkgAmnt > 0){
                             $daysRequired = $royalty->Commission_limit;
                            if($daysCount <= $daysRequired){
                   
                   
                                    for ($i = 0; $i < $level; $i++) {
                                        if ($teams) {
                                            $teams = Team::whereIn('sponsor', $teams)->pluck('user_id')->toArray(); // Convert to array
                                    
                                            if (!empty($teams)) {
                                                // Filter users who have active investments in EbikeInvestment
                                                $activeUsersInEbikeInvestment = array_filter($teams, function ($user_id) {
                                                    $checkInvestment = EbikeInvestment::where('user_id', $user_id)
                                                        ->where('status', '1')
                                                        ->sum('amount');
                                                    
                                                    return $checkInvestment > 0; // User exists in EbikeInvestment if the sum is greater than 0
                                                });
                                    
                                                $lvlteam = $activeUsersInEbikeInvestment;
                                                $lvlTeam = count($lvlteam); // Count the active users
                                            }
                                        }
                                    }
                                    $royalty->activeTeamCount = $lvlTeam;
                                    $royalty->daysCount = $daysCount;
                                    if($lvlTeam >= $teamRequired){
                                        $royalty->royalty_status = 1; 
                                    }else{
                                        $royalty->royalty_status = 2;
                                    }
                    
                            }else{
                                 $royalty->royalty_status = 0;
                                     $royalty->activeTeamCount = 0;
                                    $royalty->daysCount = 0;
                            }
                    
               }else{
                   $royalty->royalty_status = 2;
                       $royalty->activeTeamCount = 0;
                                    $royalty->daysCount = 0;
               }
               
                               
                
          }
          
           $res = [
                'success' => false,
                'data'  => $getRoyalty,
                'message' => "data fetch successfully."
            ];
           
      } 
      
      return response()->json($res, 200);
       
  } 
  
  
  public function EbikeCommissionHistory(Request $request){
    if($request->user()->kyc_status == '0'){
        $res = [
            'success' => false,
            'message' => "Please complete your kyc!"
        ];
        return response()->json($res, 200);


    }
         
            $userId = $request->user()->id;
            $transactions = $request->user()
                                    ->transactions()
                                    ->where('income', 'referral_ebike')
                                    ->with('tx_user_details') // Eager load the tx_user related details
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(10);
            
            // Check if any transactions are found
            if ($transactions->count() > 0) {
                // Map transactions to include the tx_user's mobile number
                $transactionsData = $transactions->map(function ($transaction) {
                    return [
                        'id' => $transaction->id,
                        'user_id' => $transaction->user_id,
                        'tx_user' => $transaction->tx_user_details ? $transaction->tx_user_details->mobile : null, // Fetch the user's mobile number
                        'amount' => $transaction->amount,
                        'charges' => $transaction->charges,
                        'tx_type' => $transaction->tx_type,
                        'type' => $transaction->type,
                        'wallet' => $transaction->wallet,
                        'income' => $transaction->income,
                        'tx_id' => $transaction->tx_id,
                        'level' => $transaction->level,
                        'remark' => $transaction->remark,
                        'status' => $transaction->status,
                        'created_at' => $transaction->created_at,
                        'updated_at' => $transaction->updated_at,
                    ];
                });
            
                $response = [
                    'success' => true,
                    'data' => $transactionsData,
                    'pagination' => [
                        'current_page' => $transactions->currentPage(),
                        'last_page' => $transactions->lastPage(),
                        'total_items' => $transactions->total(),
                    ],
                    'message' => 'Transaction History Fetch Successfully.',
                ];
            } else {
                $response = [
                    'success' => false,
                    'data' => [],
                    'pagination' => [
                        'current_page' => 0,
                        'last_page' => 0,
                        'total_items' => 0,
                    ],
                    'message' => 'No transactions found.',
                ];
            }

        
        return response()->json($response, 200);
    }
  
  
}
