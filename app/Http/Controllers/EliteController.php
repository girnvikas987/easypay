<?php

namespace App\Http\Controllers;

use App\Helper\Distribute;
use App\Models\EliteInvestment;
use App\Models\ElitePackage;
use App\Models\FlyInvestment;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletType;
use App\Rules\ValidateElitePackage;
use Carbon\Carbon;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EliteController extends Controller
{
    public function  getPackage(){
        $wallets = WalletType::whereIn('id',['1','2','3','8','13'])->get();
         $lists =  ElitePackage::where('status',1)->get();
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


        $defultregisterStatus = Setting::where('type','elite_status')->value('status');
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
        if($request->wallet_type == 'elite_wallet' && $request->user()->lwallet_status == '0'){
            $res = [
                'success' => false,
                'message' => "Payout service down! Please try again after some time."
            ];
            return response()->json($res, 200);

        }
        if($request->wallet_type == 'fly_wallet' && $request->user()->fwallet_status == '0'){
            $res = [
                'success' => false,
                'message' => "Payout service down! Please try again after some time."
            ];
            return response()->json($res, 200);

        }
 
            $validator = Validator::make($request->all(),[
                'mobile' => ['required', 'string', 'max:255' , 'exists:users,mobile'],
                'package' => ['required', 'integer', new ValidateElitePackage($request)],   
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
        $alreadyActive =EliteInvestment::where('user_id',$usera->id)->first();
        $mobile =  $request->mobile;
            // if(!$alreadyActive){
                
            $pkgDetails = ElitePackage::where('id',$request->package)->first();
            if($pkgDetails->type=='fix'){
                $amnt = $pkgDetails->amount;
            }else{
                $amnt = $request->amount;
            }
            DB::beginTransaction();
            try {

                EliteInvestment::where('user_id', $usera->id)
                ->update(['package_status' => 0]);

             
        
                $invest = EliteInvestment::create([
    
                    'user_id' => $usera->id,
                    'tx_user' => Auth::user()->id,
                    'package_id' => $request->package,
                    'days' => 0,
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
                    'tx_type' => 'elite_topup',
                    'status'  => 1,
                    'wallet'  => $wallet_type,
                    'tx_id'  => $invest->id,
                    'close_amount'  => $closed_amount,
                    'remark'  => 'E-Lite package of  '.$mobile.'activeted',
                
                ]);


    
                 
                Distribute::DitrubteEliteIdAdd($transaction);
                Distribute::ReferralEliteIncome($transaction);
                // Distribute::EbikeBoosterIncome($transaction);
                $usera->left_elite = 0;
                $usera->rigth_elite = 0;
                $usera->elite_matching = 0;
                $usera->active_status = 1;
                $usera->save();
               
                // Wallet::where('user_id',$usera->id)->increment('bouns_wallet',$amnt);
                DB::commit();
                $user = $usera;
                $name = $user->name;
                $mobnile = $user->mobile;
                $decoded_msg = urlencode($name);
                $msg = "Congratulations%20Dear%20$decoded_msg%20($mobnile)Your%20successfully%20Completed%20Activation%20Ebike%20pakage%20Rs%206000%20Thank%20S2PAY";
                $user->elite_pkg = $amnt;
                $user->save();
               
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
                
            // }else{
            //     $response = [
            //         'success' => false,
            //         'data' => '',
            //         'message' => "This Package Already Activeted.."
            //     ]; 
            // }
            
            return $response;
    }

    public function leftRigthData(Request $request)
{
    $directs = $request->user()->directs;
    $leftInvesters = [];
    $rigthInvesters = [];
    $rigthamount = 0;
    $leftamount = 0;
    $type = $request->type;
    if(!$type){
        $type = 'elite';
    }

    if ($directs && $directs->count() > 0) {
        foreach ($directs as $direct) {

            // Handle left team (position == '1')
            if ($direct->position == '1') {
                $leftteam = is_array($direct->gen) ? $direct->gen : [];
                $leftteam[] = $direct->user_id;
                if($type =='fly'){

                        $eliteleftInvestors = FlyInvestment::with(['user:id,name,mobile,username,sponsor'])
                        ->whereIn('user_id', $leftteam)
                        ->orderBy('created_at', 'desc')
                        ->get();

                        $leftamount += FlyInvestment::whereIn('user_id', $leftteam)->sum('amount');
                }else{

                    
                        $eliteleftInvestors = EliteInvestment::with(['user:id,name,mobile,username,sponsor'])
                        ->whereIn('user_id', $leftteam)
                        ->orderBy('created_at', 'desc')
                        ->get();
    
                        $leftamount += EliteInvestment::whereIn('user_id', $leftteam)->sum('amount');
                   
                }
               

                $leftInvesters = array_merge($leftInvesters, $eliteleftInvestors->toArray());
            }
            // Handle right team (position != '1')
            else {
                $rigthteam = is_array($direct->gen) ? $direct->gen : [];
                $rigthteam[] = $direct->user_id;

                if($type =='fly'){
                        $eliterigthInvestors = FlyInvestment::with(['user:id,name,mobile,username,sponsor'])
                        ->whereIn('user_id', $rigthteam)
                        ->orderBy('created_at', 'desc')
                        ->get();
                        $rigthamount += FlyInvestment::whereIn('user_id', $rigthteam)->sum('amount');
                }else{

                    $eliterigthInvestors = EliteInvestment::with(['user:id,name,mobile,username,sponsor'])
                    ->whereIn('user_id', $rigthteam)
                    ->orderBy('created_at', 'desc')
                    ->get();
                $rigthamount += EliteInvestment::whereIn('user_id', $rigthteam)->sum('amount');


                    
                } 
                $rigthInvesters = array_merge($rigthInvesters, $eliterigthInvestors->toArray());

              
                
            }
        }

        usort($leftInvesters, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        usort($rigthInvesters, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        // Set the response with success data
        $response = [
            'success' => true,
            'left_data' => $leftInvesters,
            'right_data' => $rigthInvesters,
            'left_amount' => $leftamount,
            'right_amount' => $rigthamount,
            'message' => 'Transaction history fetched successfully!',
        ];
    } else {
        // No directs found
        $response = [
            'success' => false,
            'left_data' => [],
            'right_data' => [],
            'left_amount' => 0,
            'right_amount' => 0,
            'message' => 'No transaction history found!',
        ];
    }

    return response()->json($response, 200);
}

}
