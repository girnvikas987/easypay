<?php

namespace App\Http\Controllers;

use App\Helper\Distribute;
use App\Models\GoldInvestment;
use App\Models\GoldPackage;
use App\Models\PlanGoldRoyalty;
use App\Models\PlanRefferalIncome;
use App\Models\Setting;
use App\Models\Team;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletType;
use App\Rules\ValidateGoldPackage;
use Illuminate\Http\Request;
use App\Rules\ValidatePackage;
use Carbon\Carbon;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GoldController extends Controller
{

    public function packages(Request $request) {
        $details = GoldPackage::where('status','1')->get();
        $wallets = WalletType::whereIn('id',['1','2','3','8'])->get();

            if($request->mobile != ''){
              
                $user = User::where('mobile',$request->mobile)->first();
                $chkinvestmentExists = GoldInvestment::where('user_id',$user->id)->latest()->first();
                if($chkinvestmentExists){ 
                    $userId = $request->user()->id;
                    $paid= $chkinvestmentExists->amount*2; 
                    if($chkinvestmentExists->received_amnt >= $paid){
                        if($chkinvestmentExists->package_id  == 9){
                            
                            $chkinvestmentExists = GoldInvestment::where('user_id',$userId)->where('package_id',1)->latest()->first();
                            $chkinvestmentExists->pkg_status = 0;
                            $chkinvestmentExists->save();
                            $response = [
                                'success' => true,
                                'packages'=>   GoldPackage::where('id',1)->get(), 
                                'wallets'=>   $wallets,
                                'allPackages'=>   $details, 
                            ];
                            return response()->json($response, 200);
    
                        }else{
   
                            $nextid = $chkinvestmentExists->package_id + 1;
    
                            $chkinvestmentExists = GoldInvestment::where('user_id',$userId)->where('package_id',$nextid)->latest()->first();
                            if($chkinvestmentExists){
                                $chkinvestmentExists->pkg_status = 0;
                                $chkinvestmentExists->save();
                            }
                        
                            $response = [
                                'success' => true,
                                'packages'=>   GoldPackage::where('id',$nextid)->get(), 
                                'wallets'=>   $wallets, 
                                'allPackages'=>   $details,
                            ];
                            return response()->json($response, 200);
    
                        } 
    
                    }else{ 
                            $response = [
                                'success' => true,
                                'packages'=>   GoldPackage::where('id',$chkinvestmentExists->package_id)->get(), 
                                'wallets'=>   $wallets, 
                                'allPackages'=>   $details,
                            ];
                            return response()->json($response, 200);
                    }
    
                }else{
                    $response = [
                        'success' => true,
                        'packages'=>   GoldPackage::where('id',1)->get(), 
                        'wallets'=>   $wallets, 
                        'allPackages'=>   $details,
                    ];
                    return response()->json($response, 200);
                }

            }else{
 

                $chkinvestmentExists = GoldInvestment::where('user_id',$request->user()->id)->latest()->first();
                if($chkinvestmentExists){ 
                    $userId = $request->user()->id;
                    $paid= $chkinvestmentExists->amount*2; 
                    if($chkinvestmentExists->received_amnt >= $paid){
                        if($chkinvestmentExists->package_id  == 9){
                            
                            $chkinvestmentExists = GoldInvestment::where('user_id',$userId)->where('package_id',1)->latest()->first();
                            $chkinvestmentExists->pkg_status = 0;
                            $chkinvestmentExists->save();
                            $response = [
                                'success' => true,
                                'packages'=>   GoldPackage::where('id',1)->get(), 
                                'wallets'=>   $wallets, 
                                'allPackages'=>   $details,
                            ];
                            return response()->json($response, 200);
    
                        }else{
    
                            $nextid = $chkinvestmentExists->package_id + 1;
    
                            $chkinvestmentExists = GoldInvestment::where('user_id',$userId)->where('package_id',$nextid)->latest()->first();
                            if($chkinvestmentExists){
                                $chkinvestmentExists->pkg_status = 0;
                                $chkinvestmentExists->save();
                                
                            }
                        
                            $response = [
                                'success' => true,
                                'packages'=>   GoldPackage::where('id',$nextid)->get(), 
                                'wallets'=>   $wallets, 
                                'allPackages'=>   $details,
                            ];
                            return response()->json($response, 200);
    
                        } 
    
                    }else{ 
                            $response = [
                                'success' => true,
                                'packages'=>   GoldPackage::where('id',$chkinvestmentExists->package_id)->get(), 
                                'wallets'=>   $wallets, 
                                'allPackages'=>   $details,
                            ];
                            return response()->json($response, 200);
                    }
    
                }else{
                    $response = [
                        'success' => true,
                        'packages'=>   GoldPackage::where('id',1)->get(), 
                        'wallets'=>   $wallets, 
                        'allPackages'=>   $details,
                    ];
                    return response()->json($response, 200);
                }
            

            }





          
        
    }
    public function packagesNew(Request $request) {
           $details = GoldPackage::where('status','1')->get();
     
            $wallets = WalletType::whereIn('id',['1','2','3','8'])->get();
            if($request->mobile != ''){

                $user = User::where('mobile',$request->mobile)->first();
                $chkinvestmentExists = GoldInvestment::where('user_id',$user->id)->latest()->first();
                if($chkinvestmentExists){ 
                    $userId = $request->user()->id;
                    $paid= $chkinvestmentExists->amount*2; 
                    if($chkinvestmentExists->received_amnt >= $paid){
                        if($chkinvestmentExists->package_id  == 9){
                            
                            $chkinvestmentExists = GoldInvestment::where('user_id',$userId)->where('package_id',1)->latest()->first();
                            $chkinvestmentExists->pkg_status = 0;
                            $chkinvestmentExists->save();
                            $response = [
                                'success' => true,
                                'packages'=>   GoldPackage::where('id',1)->get(),
                                'wallets'=>   $wallets,  
                                'allPackages'=>   $details,  
                            ];
                            return response()->json($response, 200);
    
                        }else{
    
                            $nextid = $chkinvestmentExists->package_id + 1;
    
                            $chkinvestmentExists = GoldInvestment::where('user_id',$userId)->where('package_id',$nextid)->latest()->first();
                            if($chkinvestmentExists){
                                $chkinvestmentExists->pkg_status = 0;
                                $chkinvestmentExists->save();
                            }
                        
                            $response = [
                                'success' => true,
                                'packages'=>   GoldPackage::where('id',$nextid)->get(),
                                'wallets'=>   $wallets, 
                                'allPackages'=>   $details,
                            ];
                            return response()->json($response, 200);
    
                        } 
    
                    }else{ 
                            $response = [
                                'success' => true,
                                'packages'=>   GoldPackage::where('id',$chkinvestmentExists->package_id)->get(),
                                'wallets'=>   $wallets, 
                                'allPackages'=>   $details,
                            ];
                            return response()->json($response, 200);
                    }
    
                }else{
                    $response = [
                        'success' => true,
                        'packages'=>   GoldPackage::where('id',1)->get(),
                        'wallets'=>   $wallets, 
                        'allPackages'=>   $details,
                    ];
                    return response()->json($response, 200);
                }

            }else{
 

                $chkinvestmentExists = GoldInvestment::where('user_id',$request->user()->id)->latest()->first();
                if($chkinvestmentExists){ 
                    $userId = $request->user()->id;
                    $paid= $chkinvestmentExists->amount*2; 
                    if($chkinvestmentExists->received_amnt >= $paid){
                        if($chkinvestmentExists->package_id  == 9){
                            
                            $chkinvestmentExists = GoldInvestment::where('user_id',$userId)->where('package_id',1)->latest()->first();
                            $chkinvestmentExists->pkg_status = 0;
                            $chkinvestmentExists->save();
                            $response = [
                                'success' => true,
                                'packages'=>   GoldPackage::where('id',1)->get(),
                                'wallets'=>   $wallets, 
                                'allPackages'=>   $details,
                            ];
                            return response()->json($response, 200);
    
                        }else{
    
                            $nextid = $chkinvestmentExists->package_id + 1;
    
                            $chkinvestmentExists = GoldInvestment::where('user_id',$userId)->where('package_id',$nextid)->latest()->first();
                            if($chkinvestmentExists){
                                $chkinvestmentExists->pkg_status = 0;
                                $chkinvestmentExists->save();
                            }
                        
                            $response = [
                                'success' => true,
                                'packages'=>   GoldPackage::where('id',$nextid)->get(),
                                'wallets'=>   $wallets, 
                                'allPackages'=>   $details,
                            ];
                            return response()->json($response, 200);
    
                        } 
    
                    }else{ 
                            $response = [
                                'success' => true,
                                'packages'=>   GoldPackage::where('id',$chkinvestmentExists->package_id)->get(),
                                'wallets'=>   $wallets, 
                                'allPackages'=>   $details,
                            ];
                            return response()->json($response, 200);
                    }
    
                }else{
                    $response = [
                        'success' => true,
                        'packages'=>   GoldPackage::where('id',1)->get(),
                        'wallets'=>   $wallets, 
                        'allPackages'=>   $details,
                    ];
                    return response()->json($response, 200);
                }
            

            }





          
        
    }


    public function topup_api(Request $request) {

        // $res = [
        //     'success' => false,
        //     'message' => "please check after some time we are work on this !"
        // ];
        // return response()->json($res, 200);
        $defultregisterStatus = Setting::where('type','gold_investment')->value('status');
        if($defultregisterStatus == '1'){

      

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



            // if($request->user()->withdrawal_status == '1'){
            $validator = Validator::make($request->all(),[
                'mobile' => ['required', 'string', 'max:255' , 'exists:users,mobile'],
                'package' => ['required', 'integer', new ValidateGoldPackage($request)],   
            ]);


            // $response = [
            //     'success' => false,
            //     'data' =>'',
            //     'message' => "Something Wrong."
            // ]; 

            // return response()->json($response, 200);
        

            if ($validator->fails()) {
                $res = [
                    'success' => false,
                    'message' => $validator->errors()
                ];
                return response()->json($res, 200);

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
        
            $resp=$this->make_topup($request);
            // }else{ 
            //     $resp = [
            //     'success' => false,
            //     'data' => '',
            //     'message' => "Payout service down! Please try again after some time."
            // ]; 
        // }

    }else{
        $resp = [
            'success' => false,
            'data' => '',
            'message' => "Something Went wrong!."
        ]; 
        
    }
        return response()->json($resp, 200);
     
    }



    public function make_topup(Request $request){
        
            $wallet_type=$request->wallet_type;
            $usera = User::where('mobile',$request->mobile)->first();
            $mobile = $request->mobile;
            $pkgDetails = GoldPackage::where('id',$request->package)->first();
            if($pkgDetails->type=='fix'){
                $amnt = $pkgDetails->amount;
            }else{
                $amnt = $request->amount;
            }
            DB::beginTransaction();
            try {
                $active_sponsor = GoldInvestment::where('user_id',$usera->id)->where('package_status',1)->first();
                if($active_sponsor){
                    $active_sponsor->package_status = 0;
                    $active_sponsor->save();
    
                }
            
              
                //code...
                $invest = GoldInvestment::create([
                    'user_id' => $usera->id,
                    'tx_user' => Auth::user()->id,
                    'package_id' => $request->package,
                    'amount' => $amnt,
                    'status'  => 1
                ]);
                
       
                
                Wallet::where('user_id',Auth::user()->id)->decrement($wallet_type,$amnt);
                $closedAmnt = $request->user()->wallet->$wallet_type;
                $transaction = Transaction::create([
                    'user_id' => Auth::user()->id,
                    'tx_user' => $usera->id,
                    'amount' => $amnt,
                    'type' => 'debit',
                    'tx_type' => 'gold_topup',
                    'status'  => 1,
                    'wallet'  => $wallet_type,
                    'close_amount' =>$closedAmnt,
                    'tx_id'  => $invest->id,
                    'remark'  => 'Gold package of  '.$mobile.'activeted',
                
                ]);
     
                $usera->active_status = 1;
                $usera->save();
              
                // event(new TopupListener($transaction));
                  Distribute::GoldDirectIncome($transaction);
                  Distribute::distrbuteGoldBinaryIncome($transaction);
               //  Distribute::InvestLevelIncome($transaction);
       
                // if(in_array('autopool',$income_aray)){
                //     $check_exists = Autopool::isExists($usera->id,'default','1');
                
                //     if($check_exists->count()==0){
                //         $parent=Pool::getParent(1,'default','1');
                //         Autopool::create([
                //             'user_id'=>$usera->id,
                //             'parent_id' =>  $parent,
                //             'pool' =>  'default',
                //             'pool_num' =>  1,
                //         ]);
                //     }
                // }
                
                //Wallet::where('user_id',$usera->id)->increment('bouns_wallet',$amnt);
                DB::commit();
                $user = $usera;
                $namne =$user->name;
                $mobi = $user->mobile;
                $user->gold_membership_pkg = $amnt;
                // $oldAmnt = $user->ttl_investment;
                // $user->ttl_investment = $amnt +$oldAmnt;
                $user->save();
                $decoded_msg = urlencode($namne);
                // $msg = "Congratulations%20Dear%20$decoded_msg%20($mobi)Your%20successfully%20Completed%20Activation%20prime%20pakage%20Rs%20$amnt%20Thank%20S2PAY";
                $msg = "Congratulations%20Dear%20$decoded_msg%20($mobi)Your%20successfully%20Completed%20Activation%20Gold%20Membership%20package%20Rs%20$amnt%20Thank%20S2PAY";
                $this->sendActiveMsg($msg,$mobi);
              //  ActivationMail::dispatch($user);
                $response = [
                    'success' => true,
                    'data' => $amnt,
                    'active_datetime' => Carbon::parse($invest->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
                    'message' => "Subscription successfull."
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
            return $response;
    }
    

    public function sendActiveMsg($msg,$mobi){
     
       
        $apiKey = "71a9b0fbe3cb414583372e7c5664a5b4";
         
          
        $ch = curl_init();
        
        // Set the URL and other options
        curl_setopt($ch, CURLOPT_URL, "http://whatsapp.click4bulksms.in/wapp/api/send?apikey=$apiKey&mobile=$mobi&msg=$msg");
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




    ///////////////////////////////////////////////////Gold Royalty ///////////////////////////////////////////////////////////////////////////////////

    public function getGoldRoyaltyold(Request $request){
        $id = $request->user()->id;
        $gen = $request->user()->team->gen;
        
        $activeDate = $request->user()->active_date; 
         // Calculate days from active_date to today
         $activeDate = Carbon::parse($activeDate);
         $today = Carbon::now();
         $teams = array($id);
         
         $lvlteam = array();
       
         $getRoyalty = PlanGoldRoyalty::where('status',1)->get();
       if($getRoyalty){
           foreach($getRoyalty as $royalty){
               $teamRequired = $royalty->team_required;
               $level = 10;// $royalty->level;
                
       

                                  if($teams['0'] == $id){

                                    for ($i = 0; $i < $level; $i++) {
                                        if ($teams) {
                                            $teams = Team::whereIn('sponsor', $teams)->pluck('user_id')->toArray(); // Convert to array
                                    
                                            if (!empty($teams)) {
                                                // Filter users who have active investments in EbikeInvestment
                                                $activeUsersInEbikeInvestment = array_filter($teams, function ($user_id) {
                                                    $checkInvestment = GoldInvestment::where('user_id', $user_id)
                                                        ->where('status', '1')
                                                        ->first();
                                                    
                                                    return $checkInvestment ? 1:0; // User exists in EbikeInvestment if the sum is greater than 0
                                                });
                                    
                                                $lvlteam = $activeUsersInEbikeInvestment;
                                                $lvlTeam = count($lvlteam); // Count the active users
                                            }
                                        }
                                       
                                    }

                                  }
                                     
                                     if($lvlTeam >= $teamRequired){
                                         $royalty->royalty_status = 1; 
                                     }else{
                                         $royalty->royalty_status = 2;
                                     }
                     
                                     
                                     $royalty->activeTeamCount = $lvlTeam;
               
                
                                
                 
           }
          
            $res = [
                 'success' => false,
                 'data'  => $getRoyalty,
                 'message' => "data fetch successfully."
             ];
            
       } 
       
       return response()->json($res, 200);
        
   } 

   public function getGoldRoyalty(Request $request){
    $id = $request->user()->id;
    $gen = $request->user()->team->gen;
    
    $activeDate = $request->user()->active_date; 
     // Calculate days from active_date to today
     $activeDate = Carbon::parse($activeDate);
     $today = Carbon::now();
    //  $teams = array($id);
     
     $lvlteam = array();
     $ttlteam = array();
     $teams = Team::where('user_id', $id)->value('gen');
     $getRoyalty = PlanGoldRoyalty::where('status',1)->get();
            if($getRoyalty){
                foreach($getRoyalty as $royalty){
                    $teamRequired = $royalty->team_required;
                    $level = 500;// $royalty->level;
                        
            
                        
                                    // Convert to array
                                  
                                   
                                        // Filter users who have active investments in EbikeInvestment
                                        // $activeUsersInEbikeInvestment = array_filter($teams, function ($user_id) {
                                        //     $checkInvestment = GoldInvestment::where('user_id', $user_id)
                                        //         ->where('status', '1')
                                        //         ->first();
                                            
                                        //     return $checkInvestment ? 1:0; // User exists in EbikeInvestment if the sum is greater than 0
                                        // });
                                        $ttlteam = GoldInvestment::whereIn('user_id',$teams)
                                                ->where('status', '1')
                                                ->count();

                                       
                                       
                                

                        
        
                                        
                                            
                                            if($ttlteam >= $teamRequired){
                                                $royalty->royalty_status = 1; 
                                            }else{
                                                $royalty->royalty_status = 2;
                                            }
                            
                                            
                                            $royalty->activeTeamCount = $ttlteam;
                    
                    }
                                        
                        
                }
      
                        $res = [
                            'success' => false,
                            'data'  => $getRoyalty,
                            'message' => "data fetch successfully."
                        ];
        
   
   
   return response()->json($res, 200);
    
} 

    ///////////////////////////////////////////////////Gold Royalty ///////////////////////////////////////////////////////////////////////////////////


    public function test(){

         $allInvesters = GoldInvestment::all();
         $ttlinactive = 0;
         foreach($allInvesters as $invester){

            $user = User::where('id',$invester->user_id)->first();
               
            if($user->active_status->value == 0){
                $user->active_status = 1;
                $user->save();
                $ttlinactive++;
            }

                   

         }
         print_r($ttlinactive);
         die();

             
    }
}
