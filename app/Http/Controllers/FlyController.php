<?php

namespace App\Http\Controllers;

use App\Helper\Distribute;
use App\Models\FlyInvestment;
use App\Models\FlyPackage;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletType;
use App\Rules\ValidateFlyPackage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Validator;
use PDF;
use Illuminate\Support\Facades\File; 
class FlyController extends Controller
{

    public function sendEncodedInvestIdToApp(Request $request)
    {
        $flyinvestmentId = $request->invest_id; // The raw invest ID from the request
    
        // Encrypt the invest_id to make it unreadable
        $encryptedInvestId = Crypt::encryptString($flyinvestmentId);
      
        return response()->json(['url' =>$encryptedInvestId]);
        // Generate the URL with the encrypted invest_id
       // $url = route('fly_ticket', ['invest_id' => $encryptedInvestId]);
        
        // Send the URL to the app developer (this will be the URL you share)
        //return response()->json(['url' => $url]);
    }

    public function flyTicket(Request $request)
    {
        // Decrypt the invest_id from the request
        $encryptedInvestId = $request->query('invest_id'); // Get the encrypted invest_id
    
        try {
            // Decrypt the invest_id
            $investId = Crypt::decryptString($encryptedInvestId);
          
            // Fetch the investment data using the decrypted invest_id
            $flyinvestmentData = FlyInvestment::with('user')->where('id', $investId)->first();
    
            // Fetch the main theme setting
            $maintheme = Setting::getSetting('mtheme', 'mtheme1');
          
            // Set the layout view path
            $Maindashborad = 'layouts.' . $maintheme . '.ticket';
             
            // Return the view with the investment data
            return view($Maindashborad, compact('flyinvestmentData'));
           // redirect()->route('/contact');
            
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            // Handle the case where the decryption fails
            return response()->json(['error' => 'Invalid or expired invest_id.'], 400);
        }
    }
    public function  getPackage(){
        $wallets = WalletType::whereIn('id',['17'])->get();
         $lists =  FlyPackage::where('status',1)->get();
             if($lists){
                $response = [
                  'success' => true, 
                  'data' => $lists, 
                  'wallets'=>   $wallets,
                  'message' => "Fly Package fetch Successfully."
                ];
             }else{
                 
                 $response = [
                  'success' => false, 
                  'data' => '', 
                  'wallets'=>   $wallets,
                  'message' => "Fly Package data not found!"
                ];
                 
             }       
            return response()->json($response, 200);
      } 



      public function buyPackage(Request $request){


        $defultregisterStatus = Setting::where('type','fly_status')->value('status');
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
                'package' => ['required', 'integer', new ValidateFlyPackage($request)],   
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
        $alreadyActive =FlyInvestment::where('user_id',$usera->id)->first();
        $mobile =  $request->mobile;
            // if(!$alreadyActive){
                
            $pkgDetails = FlyPackage::where('id',$request->package)->first();
            if($pkgDetails->type=='fix'){
                $amnt = $pkgDetails->amount;
            }else{
                $amnt = $request->amount;
            }
            DB::beginTransaction();
            try {

                FlyInvestment::where('user_id', $usera->id)
                ->update(['package_status' => 0]);

             
        
                $invest = FlyInvestment::create([
    
                    'user_id' => $usera->id,
                    'tx_user' => Auth::user()->id,
                    'package_id' => $request->package,
                    'days' => 0,
                    'amount' => $amnt,
                    'received_amnt' => 0,
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
                    'tx_type' => 'fly_topup',
                    'status'  => 1,
                    'wallet'  => $wallet_type,
                    'tx_id'  => $invest->id,
                    'close_amount'  => $closed_amount,
                    'remark'  => 'Fly package of  '.$mobile.'activeted',
                
                ]);


    
                 
                // Distribute::DitrubteEliteIdAdd($transaction);
                // Distribute::ReferralEliteIncome($transaction);
                Distribute::distrbuteFlyBinaryIncome($transaction);
                // $usera->fly_left = 0;
                // $usera->fly_rigth = 0;
                // $usera->fly_matching = 0;




                $usera->active_status = 1;
                $usera->save();
                // $invest->update([
                //     'invoice' => $fileUrl
                // ]);
                // Wallet::where('user_id',$usera->id)->increment('bouns_wallet',$amnt);
                DB::commit();
                $user = $usera;
                $name = $user->name;
                $mobnile = $user->mobile;
                $decoded_msg = urlencode($name);
                $msg = "Congratulations%20Dear%20$decoded_msg%20($mobnile)Your%20successfully%20Completed%20Activation%20Ebike%20pakage%20Rs%206000%20Thank%20S2PAY";
                $user->fly_pkg = $amnt;
                $user->save();
               
                $response = [
                    'success' => true,
                    'data' => $amnt,
                    'active_datetime' => Carbon::parse($invest->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
                    'message' => "fly Package Active successfull."
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

}
