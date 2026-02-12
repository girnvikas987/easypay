<?php

namespace App\Http\Controllers;

use App\Helper\Distribute;
use App\Models\Setting;
use App\Models\TourInvestment;
use App\Models\TourPackage;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletType;
use App\Rules\ValidateTourPackage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;
class TourController extends Controller
{
    public function  getPackage(){
        $wallets = WalletType::whereIn('id',['1','2','3','8'])->get();
         $lists =  TourPackage::where('status',1)->get();
             if($lists){
                $response = [
                  'success' => true, 
                  'data' => $lists, 
                  'wallets'=>   $wallets,
                  'message' => "Tour Package fetch Successfully."
                ];
             }else{
                 
                 $response = [
                  'success' => false, 
                  'data' => '', 
                  'wallets'=>   $wallets,
                  'message' => "Tour Package data not found!"
                ];
                 
             }       
            return response()->json($response, 200);
      } 
    
        public function buyPackage(Request $request){
    
    
            $defultregisterStatus = Setting::where('type','tour_status')->value('status');
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
                    'package' => ['required', 'integer', new ValidateTourPackage($request)],   
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
            $alreadyActive =TourInvestment::where('user_id',$usera->id)->first();
            $mobile =  $request->mobile;
                if(!$alreadyActive){
                    
                    $pkgDetails = TourPackage::where('id',$request->package)->first();
                if($pkgDetails->type=='fix'){
                    $amnt = $pkgDetails->amount;
                }else{
                    $amnt = $request->amount;
                }
                DB::beginTransaction();
                try {
            
                    $invest = TourInvestment::create([
        
                        'user_id' => $usera->id,
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
                        'tx_type' => 'tour_topup',
                        'status'  => 1,
                        'wallet'  => $wallet_type,
                        'tx_id'  => $invest->id,
                        'close_amount'  => $closed_amount,
                        'remark'  => 'Tour package of  '.$mobile.'activeted',
                    
                    ]);
    
       
                     
                     Distribute::DitrubteTourIdAdd($transaction);
                     Distribute::ReferralTourIncome($transaction);
                    // Distribute::EbikeBoosterIncome($transaction);
                    
                   
                    // Wallet::where('user_id',$usera->id)->increment('bouns_wallet',$amnt);
                    DB::commit();
                    $user = $usera;
                    $name = $user->name;
                    $mobnile = $user->mobile;
                    $decoded_msg = urlencode($name);
                    $msg = "Congratulations%20Dear%20$decoded_msg%20($mobnile)Your%20successfully%20Completed%20Activation%20Ebike%20pakage%20Rs%206000%20Thank%20S2PAY";
                    $user->tour_pkg = $amnt;
                    $user->active_status = 1;
                    $user->save();
                    // ActivationMail::dispatch($user);
                    // $this->sendActiveEbikeMsg($msg,$mobnile);
                    $response = [
                        'success' => true,
                        'data' => $amnt,
                        'active_datetime' => Carbon::parse($invest->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
                        'message' => "Tour Package Active successfull."
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
    
}
