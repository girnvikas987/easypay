<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Models\User;
use App\Models\Wallet;  
use App\Models\PlanLuckyDraw;  
use App\Models\PlanGiftDraw;  
use App\Models\PlanTourDraw;  
use App\Models\PlanJackpot;  
use Illuminate\Support\Carbon;
use Validator;
use App\Models\Transaction; ;
use App\Models\JackpotDrawParticipate; 
use App\Models\LuckyDraParticipate; 
use App\Models\GiftDrawParticipate; 
use App\Models\SpinnerParticipate; 
use App\Models\TourDrawParticipate;
use App\Models\WalletType;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB; 
class LuckyDrawController extends Controller
{
     public function paidLuckyDraw(Request $request){
        // if($request->user()->kyc_status == '0'){
        //     $res = [
        //         'success' => false,
        //         'message' => "Please complete your kyc!"
        //     ];
        //     return response()->json($res, 200);


        // }
     
        $wallets = WalletType::whereIn('id',['2','8'])->get();
        $PaidLuckyDraw = PlanLuckyDraw::where('status',1)->get();
        if($PaidLuckyDraw){
            $user_id =  $request->user()->id;
            
          

                if ($PaidLuckyDraw->isNotEmpty()) {
                     
                
                    $PaidLuckyDraw = $PaidLuckyDraw->map(function ($draw) use ($user_id) {
                        $alreadyParticipate = LuckyDraParticipate::where('status', 1)
                            ->where('draw_id', $draw->id)
                            ->where('user_id', $user_id)
                            ->first();
                            
                             $ParticipateCount = LuckyDraParticipate::where('status', 1)
                            ->where('draw_id', $draw->id) 
                            ->count();

                
                        $draw->ttlcount = $ParticipateCount;
                
                        $draw->isParticipate = $alreadyParticipate ? true : false;
                
                        return $draw;
                    });
                }
                $response = [
                    'success' => true,
                    'data' => $PaidLuckyDraw, 
                    'wallets' => $wallets,
                    'message' => "Plan Lucky Draw fetch successfully."
                ];
        }else{
                $response = [
                    'success' => false,
                    'data' => [], 
                    'wallets' => $wallets,
                    'message' => "Plan Lucky Draw Not Found."
                ];
        }
          
        return response()->json($response, 200); 
         
         
     }
     
     public function joinLuckyDraw(Request $request){

        // if($request->user()->kyc_status == '0'){
        //     $res = [
        //         'success' => false,
        //         'message' => "Please complete your kyc!"
        //     ];
        //     return response()->json($res, 200);

        // if($request->wallet_type == 'main_wallet' && $request->user()->ewallet_status == '0'){
        //     $res = [
        //         'success' => false,
        //         'message' => "Payout service down! Please try again after some time."
        //     ];
        //     return response()->json($res, 200);

        // }
        // if($request->wallet_type == 'gold_membership_wallet' && $request->user()->ewallet_status == '0'){
        //     $res = [
        //         'success' => false,
        //         'message' => "Payout service down! Please try again after some time."
        //     ];
        //     return response()->json($res, 200);

        // }
        // }
        $id =  $request->id;
        $wallet_type =  $request->wallet_type;
        $user_id =  $request->user()->id;
      
        $alreadyParticipate = LuckyDraParticipate::where('status',1)->where('user_id',$id)->where('user_id',$user_id)->first();
        if($alreadyParticipate){
            $response = [
                'success' => false, 
                'message' => "You are already Participate!"
            ];
            return response()->json($response, 200);
       
        
         //$plan = PlanLuckyDraw::where('status',1)->where('id',$id)->first();
        
         }else{
            $plan = PlanLuckyDraw::where('status',1)->where('id',$id)->first();
            $wlts=Wallet::where('user_id',$user_id)->first();
            
            $detectwallet = $wlts->$wallet_type;
            
            if($detectwallet >= $plan->pay_limit){
        //         if($detectwallet >= $plan->pay_limit){
        //             $wallet = 'one';
        //             $wallet1 = 'bouns_wallet';
                     $payAmnt1 = $plan->pay_limit;
        //         }else{
                    
                    
        //             $payAmnt2 = $plan->pay_limit -  $detectwallet; 
        //             $payAmnt1 = $plan->pay_limit - $payAmnt2;
        //             $wallet = 'two';
        //             $wallet1 = 'bouns_wallet';
        //             $wallet2 = 'main_wallet';
                    
        //               if($main_wallet < $payAmnt2){
        //                   $response = [
        //                         'success' => false, 
        //                         'message' => "Insufficient balance in E Wallet ."
        //                     ];
        //                     return response()->json($response, 200);
        //             }
                    
        //             if($main_wallet < $payAmnt2 && $detectwallet < $payAmnt1){
        //                     $response = [
        //                         'success' => false, 
        //                         'message' => "Insufficient balance in Both Wallet ."
        //                     ];
        //                     return response()->json($response, 200);
        //             }
        //         }
                
        //     }else{
        //         $wallet = 'one';
        //         $wallet1 = 'main_wallet';
        //         $payAmnt1 = $plan->pay_limit;
                
        //         if($main_wallet < $payAmnt1){
        //             $response = [
        //                 'success' => false, 
        //                 'message' => "Insufficient balance in E Wallet ."
        //             ];
        //             return response()->json($response, 200);
        //         }
        //     }
        //    if($wallet =='one'){
        //        Wallet::where('user_id',$user_id)->decrement($wallet1,$payAmnt1);
        //    }else{
        //        Wallet::where('user_id',$user_id)->decrement($wallet1,$payAmnt1);
        //        
        //    }  
        Wallet::where('user_id',$user_id)->decrement($wallet_type,$payAmnt1);
        
            LuckyDraParticipate::create([
                 'user_id'=>$user_id,
                 'draw_id'=>$id,
                 'pay_amount'=>$plan->pay_limit,
                 'amount'=>$plan->value,
                 'status'=>1,
             
            ]);
            
            
             $ttlParticipate = LuckyDraParticipate::where('status',1)->where('draw_id',$id)->get();
             $ttlcountParticipate = LuckyDraParticipate::where('status',1)->where('draw_id',$id)->count();
         if($ttlcountParticipate > ($plan->participate_required - 1)){
          
               $winner = $ttlParticipate->random();
               
               $winnerInfo = User::where('id',$winner->user_id)->first();
                 
               $amount = $plan->value;
               $plan = PlanLuckyDraw::where('status',1)->where('id',$id)->first();
               Wallet::where('user_id',$winner->user_id)->increment('main_wallet',$amount);
               $closedAmnt = $request->user()->wallet->$wallet_type;
                $transaction = Transaction::create([
                    'user_id' => $winner->user_id,
                    'tx_user' => $winner->user_id,
                    'amount' => $amount,
                    'type' => 'credit',
                    'tx_type' => 'income',
                    'income' => 'paid_lucky_draw',
                    'status'  => 1,
                    'wallet'  => $wallet_type, 
                    'close_amount'  => $closedAmnt, 
                    'status' => '1',
                    'remark' => "Receive Paid Lucky Draw income of amount $amount Rs."
                ]);  
                
            
            \DB::table('lucky_dra_participates')->where('draw_id',$id)->delete();
            $data['name'] = $winnerInfo->name;
            $data['mobile'] = $winnerInfo->mobile;
            $data['date'] = Carbon::parse($transaction->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s');
            
            $response = [
                'success' => true, 
                'data' => $data, 
                'message' => "Participate done."
            ];
             
         }else{
                $response = [
                'success' => true, 
                'message' => "Participate done."
                ];
         }
         }else{
                $response = [
                'success' => false, 
                'message' => "insufficient fund in wallet."
                ];
         }
           
         }
            
         return response()->json($response, 200);
         
     }
     
     public function getPaidLuckyParticipate(Request $request){
         
         $id = $request->id;
         $allParticapate = LuckyDraParticipate::where('draw_id',$id)->orderBy('id', 'DESC')->get();
         $allParticapatecount = LuckyDraParticipate::where('draw_id',$id)->count();
     
       if($allParticapate){
            $transactions = $allParticapate->map(function ($request) {
           
              return [
                'id' => $request->id,
                'name' => $request->user->name,  
                'mobile' => $request->user->mobile,  
                'date' => Carbon::parse($request->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),  
              ];
            });
            $response = [
                    'success' => true, 
                    'data' => $transactions, 
                    'count' => $allParticapatecount, 
                    'message' => "Participate fetch successfully."
            ];
        }else{
            $response = [
                    'success' => false,   
                    'message' => "Participate not found!"
            ]; 
        }
         
         return response()->json($response, 200); 
     }
     
     
     ////////////////////////////////////////gift start /////////////////////////////////////////////////
     
      public function giftLuckyDraw(Request $request){

        $wallets = WalletType::whereIn('id',['2','8'])->get();
         
        if($request->user()->kyc_status == '0'){
            $res = [
                'success' => false,
                'wallets' => $wallets,
                'message' => "Please complete your kyc!"
            ];
            return response()->json($res, 200);


        }
         
        $PlanGiftDraw = PlanGiftDraw::where('status',1)->get();
        if($PlanGiftDraw){
            $user_id =  $request->user()->id;
            
          

                if ($PlanGiftDraw->isNotEmpty()) {
                     
                
                    $PlanGiftDraw = $PlanGiftDraw->map(function ($draw) use ($user_id) {
                        $alreadyParticipate = GiftDrawParticipate::where('status', 1)
                            ->where('draw_id', $draw->id)
                            ->where('user_id', $user_id)
                            ->first();
                            
                             $ParticipateCount = GiftDrawParticipate::where('status', 1)
                            ->where('draw_id', $draw->id) 
                            ->count();

                
                        $draw->ttlcount = $ParticipateCount;
                
                        $draw->isParticipate = $alreadyParticipate ? true : false;
                
                        return $draw;
                    });
                }
                $response = [
                    'success' => true,
                    'data' => $PlanGiftDraw, 
                    'wallets' => $wallets,
                    'message' => "Plan Gift Lucky Draw fetch successfully."
                ];
        }else{
                $response = [
                    'success' => false,
                    'data' => [], 
                    'wallets' => $wallets,
                    'message' => "Plan Gift Lucky Draw Not Found."
                ];
        }
          
        return response()->json($response, 200); 
         
         
     }
 
      public function joinGiftDraw(Request $request){


        if($request->user()->kyc_status == '0'){
            $res = [
                'success' => false,
                'message' => "Please complete your kyc!"
            ];
            return response()->json($res, 200);


        }

        // if($request->wallet_type == 'main_wallet' && $request->user()->ewallet_status == '0'){
        //     $res = [
        //         'success' => false,
        //         'message' => "Payout service down! Please try again after some time."
        //     ];
        //     return response()->json($res, 200);

        // }
        // if($request->wallet_type == 'gold_membership_wallet' && $request->user()->ewallet_status == '0'){
        //     $res = [
        //         'success' => false,
        //         'message' => "Payout service down! Please try again after some time."
        //     ];
        //     return response()->json($res, 200);

        // }
        $wallet_type =  $request->wallet_type;
        $id =  $request->id;
        $user_id =  $request->user()->id;
        
        $alreadyParticipate = GiftDrawParticipate::where('status',1)->where('user_id',$id)->where('user_id',$user_id)->first();
        if($alreadyParticipate){
            $response = [
                'success' => false, 
                'message' => "You are already Participate!"
            ];
            return response()->json($response, 200);
        
        
        $plan = PlanGiftDraw::where('status',1)->where('id',$id)->first();
       }else{
            $plan = PlanGiftDraw::where('status',1)->where('id',$id)->first();
            $wlts=Wallet::where('user_id',$user_id)->first();
            $detectwallet = $wlts->$wallet_type;
            // $detectwallet = $wlts->bouns_wallet;
            // $main_wallet = $wlts->main_wallet;
            if($detectwallet >= $plan->pay_limit){
        //         if($detectwallet >= $plan->pay_limit){
        //             $wallet = 'one';
        //             $wallet1 = 'bouns_wallet';
        //             $payAmnt1 = $plan->pay_limit;
        //         }else{
                    
                $payAmnt1 = $plan->pay_limit;
                  
        //             $payAmnt2 = $plan->pay_limit -  $detectwallet; 
        //             $payAmnt1 = $plan->pay_limit - $payAmnt2;
                    
        //             if($main_wallet < $payAmnt2){
        //                   $response = [
        //                         'success' => false, 
        //                         'message' => "Insufficient balance in E Wallet ."
        //                     ];
        //                     return response()->json($response, 200);
        //             }
        //             $wallet = 'two';
        //             $wallet1 = 'bouns_wallet';
        //             $wallet2 = 'main_wallet';
                    
        //               if($main_wallet < $payAmnt2 && $detectwallet < $payAmnt1){
        //                     $response = [
        //                         'success' => false, 
        //                         'message' => "Insufficient balance in Both Wallet ."
        //                     ];
        //                     return response()->json($response, 200);
        //             }
        //         }
                
        //     }else{
        //         $wallet = 'one';
        //         $wallet1 = 'main_wallet';
        //         $payAmnt1 = $plan->pay_limit;
                
        //          if($main_wallet < $payAmnt1){
        //             $response = [
        //                 'success' => false, 
        //                 'message' => "Insufficient balance in E Wallet ."
        //             ];
        //             return response()->json($response, 200);
        //         }
        //     }
        //    if($wallet =='one'){
        //        Wallet::where('user_id',$user_id)->decrement($wallet1,$payAmnt1);
        //    }else{
        //        Wallet::where('user_id',$user_id)->decrement($wallet1,$payAmnt1);
        //        Wallet::where('user_id',$user_id)->decrement($wallet2,$payAmnt2);
        //    }  
             Wallet::where('user_id',$user_id)->decrement($wallet_type,$payAmnt1);
            GiftDrawParticipate::create([
                 'user_id'=>$user_id,
                 'draw_id'=>$id,
                 'pay_amount'=>$plan->pay_limit,
                 'amount'=>$plan->value,
                 'status'=>1,
             
            ]);
            
            
              $ttlParticipate = GiftDrawParticipate::where('status',1)->where('draw_id',$id)->get();
         if(count($ttlParticipate) > ($plan->participate_required - 1)){
          
               $winner = $ttlParticipate->random();
               
               $winnerInfo = User::where('id',$winner->user_id)->first();
               Wallet::where('user_id',$winnerInfo->user_id)->increment('main_wallet',$amount);
               $closedAmnt = $request->user()->wallet->$wallet_type;
               $amount = $plan->value;
                $transaction = Transaction::create([
                    'user_id' => $winnerInfo->user_id,
                    'tx_user' => $winnerInfo->user_id,
                    'amount' => $amount,
                    'type' => 'credit',
                    'tx_type' => 'income',
                    'income' => $plan->source,
                    'status'  => 1,
                    'wallet'  => $wallet_type, 
                    'close_amount'  => $closedAmnt, 
                    'status' => '1',
                    'remark' => "Receive Gift Lucky Draw income of amount $amount Rs."
                ]);  
                
            
            \DB::table('gift_draw_participates')->where('draw_id',$id)->delete();
            
            $data['name'] = $winnerInfo->name;
            $data['mobile'] = $winnerInfo->mobile;
            $data['date'] = Carbon::parse($transaction->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s');
            
            $response = [
                'success' => true, 
                'data' => $data, 
                'message' => "Participation closed please try after some time."
            ];
             
         }else{
                $response = [
                    'success' => true, 
                    'message' => "Participate done."
                ];
         }
         }else{
                $response = [
                    'success' => false, 
                    'message' => "insufficient fund in wallet."
                ];
         }
            
           
            
                
         }
         
         return response()->json($response, 200);
         
     }
 
      public function getGiftLuckyParticipate(Request $request){
         
         $id = $request->id;
         $allParticapate = GiftDrawParticipate::where('draw_id',$id)->orderBy('id', 'DESC')->get();
         $allParticapatecount = GiftDrawParticipate::where('draw_id',$id)->count();
        if($allParticapate){
            $transactions = $allParticapate->map(function ($request) {
           
              return [
                'id' => $request->id,
                'name' => $request->user->name,  
                'mobile' => $request->user->mobile,  
                'date' => Carbon::parse($request->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'), 
              ];
            });
            $response = [
                    'success' => true, 
                    'data' => $transactions, 
                    'count' => $allParticapatecount, 
                    'message' => "Participate fetch successfully."
            ];
        }else{
            $response = [
                    'success' => false,   
                    'message' => "Participate not found!"
            ]; 
        }
        
         
         return response()->json($response, 200); 
     }
     
     
    ////////////////////////////////////////gift start /////////////////////////////////////////////////
    
    public function tourLuckyDraw(Request $request){
        $wallets = WalletType::whereIn('id',['2','8'])->get();
        if($request->user()->kyc_status == '0'){
            $res = [
                'success' => false,
                'wallets' => $wallets,
                'message' => "Please complete your kyc!"
            ];
            return response()->json($res, 200);


        }
     
         
        $PlanGiftDraw = PlanTourDraw::where('status',1)->get();
        if($PlanGiftDraw){
            $user_id =  $request->user()->id;
            
          

                if ($PlanGiftDraw->isNotEmpty()) {
                     
                
                    $PlanGiftDraw = $PlanGiftDraw->map(function ($draw) use ($user_id) {
                        $alreadyParticipate = TourDrawParticipate::where('status', 1)
                            ->where('draw_id', $draw->id)
                            ->where('user_id', $user_id)
                            ->first();
                            
                        $ParticipateCount = TourDrawParticipate::where('status', 1)
                            ->where('draw_id', $draw->id) 
                            ->count();

                
                        $draw->ttlcount = $ParticipateCount;
                        $draw->isParticipate = $alreadyParticipate ? true : false;
                
                        return $draw;
                    });
                }
                $response = [
                    'success' => true,
                    'data' => $PlanGiftDraw, 
                    'wallets' => $wallets,
                    'message' => "Plan Tour Lucky Draw fetch successfully."
                ];
        }else{
                $response = [
                    'success' => false,
                    'data' => [], 
                    'wallets' => $wallets,
                    'message' => "Plan Tour Lucky Draw Not Found."
                ];
        }
          
        return response()->json($response, 200); 
         
         
     }
     
    public function joinTourDraw(Request $request){

        if($request->user()->kyc_status == '0'){
            $res = [
                'success' => false,
                'message' => "Please complete your kyc!"
            ];
            return response()->json($res, 200);


        }

        // if($request->wallet_type == 'main_wallet' && $request->user()->ewallet_status == '0'){
        //     $res = [
        //         'success' => false,
        //         'message' => "Payout service down! Please try again after some time."
        //     ];
        //     return response()->json($res, 200);

        // }
        // if($request->wallet_type == 'gold_membership_wallet' && $request->user()->ewallet_status == '0'){
        //     $res = [
        //         'success' => false,
        //         'message' => "Payout service down! Please try again after some time."
        //     ];
        //     return response()->json($res, 200);

        // }

        $wallet_type =  $request->wallet_type;
        $id =  $request->id;
        $user_id =  $request->user()->id; 
       
        $alreadyParticipate = TourDrawParticipate::where('status',1)->where('draw_id',$id)->where('user_id',$user_id)->first();
        if($alreadyParticipate){
            $response = [
                'success' => false, 
                'message' => "You are already Participate!"
            ];
            return response()->json($response, 200);
          
            $plan = PlanTourDraw::where('status',1)->where('id',$id)->first();
         }else{
            $plan = PlanTourDraw::where('status',1)->where('id',$id)->first();
            $wlts=Wallet::where('user_id',$user_id)->first();
            $detectwallet = $wlts->$wallet_type;
            // $detectwallet = $wlts->bouns_wallet;
            // $main_wallet = $wlts->main_wallet;
            if($detectwallet >= $plan->pay_limit){
                // if($detectwallet >= $plan->pay_limit){
                //     $wallet = 'one';
                //     $wallet1 = 'bouns_wallet';
                //     $payAmnt1 = $plan->pay_limit; 
                // }else{
                    
                $payAmnt1 = $plan->pay_limit;
                    
                    
                //     $payAmnt2 = $plan->pay_limit -  $detectwallet; 
                //     $payAmnt1 = $plan->pay_limit - $payAmnt2;
                    
                    
                //     $wallet = 'two';
                //     $wallet1 = 'bouns_wallet';
                //     $wallet2 = 'main_wallet';
                //     if($main_wallet < $payAmnt2){
                //           $response = [
                //                 'success' => false, 
                //                 'message' => "Insufficient balance in E Wallet ."
                //             ];
                //             return response()->json($response, 200);
                //     }
                     
                //     if($main_wallet < $payAmnt2 && $detectwallet < $payAmnt1){
                //             $response = [
                //                 'success' => false, 
                //                 'message' => "Insufficient balance in Both Wallet ."
                //             ];
                //             return response()->json($response, 200);
                //     }
                // }
                
        //     }else{
        //         $wallet = 'one';
        //         $wallet1 = 'main_wallet';
        //         $payAmnt1 = $plan->pay_limit;
                
        //         if($main_wallet < $payAmnt1){
        //             $response = [
        //                 'success' => false, 
        //                 'message' => "Insufficient balance in E Wallet ."
        //             ];
        //             return response()->json($response, 200);
        //         }
        //     }
        //    if($wallet =='one'){
        //        Wallet::where('user_id',$user_id)->decrement($wallet1,$payAmnt1);
        //    }else{
        //        Wallet::where('user_id',$user_id)->decrement($wallet1,$payAmnt1);
        //       
        //    }  
          Wallet::where('user_id',$user_id)->decrement($wallet_type,$payAmnt1);
            TourDrawParticipate::create([
                 'user_id'=>$user_id,
                 'draw_id'=>$id,
                 'pay_amount'=>$plan->pay_limit,
                 'amount'=>$plan->value,
                 'status'=>1,
             
            ]);
            
            $ttlParticipate = TourDrawParticipate::where('status',1)->where('draw_id',$id)->get();
        if(count($ttlParticipate) > ($plan->participate_required - 1)){
          
               $winner = $ttlParticipate->random();
               
               $winnerInfo = User::where('id',$winner->user_id)->first();
               $amount = $plan->value;
               Wallet::where('user_id',$winnerInfo->user_id)->increment('main_wallet',$amount);
               $closedAmnt = $request->user()->wallet->$wallet_type;
                $transaction = Transaction::create([
                    'user_id' => $winnerInfo->user_id,
                    'tx_user' => $winnerInfo->user_id,
                    'amount' => $amount,
                    'type' => 'credit',
                    'tx_type' => 'income',
                    'income' => $plan->source,
                    'status'  => 1,
                    'wallet'  => $wallet_type, 
                    'close_amount'  => $closedAmnt,  
                    'status' => '1',
                    'remark' => "Receive Tour Lucky Draw income of amount $amount Rs."
                ]);  
                
             
            \DB::table('tour_draw_participates')->where('draw_id',$id)->delete();
            $data['name'] = $winnerInfo->name;
            $data['mobile'] = $winnerInfo->mobile;
            $data['date'] = Carbon::parse($transaction->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s');
            
            $response = [
                'success' => true, 
                'data' => $data, 
                'message' => "Participation closed please try after some time."
            ];
             
         }else{
             
              $response = [
                'success' => true, 
                'message' => "Participate done."
            ];
             
         }
         }else{
             
              $response = [
                'success' => false, 
                'message' => "insufficient fund in wallet."
            ];
             
         }
            
           
            
                
         }
         
         return response()->json($response, 200);
         
     }
 
    public function getTourLuckyParticipate(Request $request){
         
         $id = $request->id;
         $allParticapate = TourDrawParticipate::where('draw_id',$id)->orderBy('id', 'DESC')->get();
         $allParticapatecount = TourDrawParticipate::where('draw_id',$id)->count();
        if($allParticapate){
            $transactions = $allParticapate->map(function ($request) {
           
              return [
                'id' => $request->id,
                'name' => $request->user->name,  
                 'mobile' => $request->user->mobile,  
                'date' => Carbon::parse($request->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'), 
              ];
            });
            $response = [
                    'success' => true, 
                    'data' => $transactions, 
                    'count' => $allParticapatecount, 
                    'message' => "Participate fetch successfully."
            ];
        }else{
            $response = [
                    'success' => false,   
                    'message' => "Participate not found!"
            ]; 
        }
        
         
         return response()->json($response, 200); 
     }
     
     
    ////////////////////////////////////Jackpot////////////////////////////////////////////////////////////
    public function tourJackpotDraw(Request $request){
        $currentDateTime = Carbon::now();
                  
        $formattedExpiryDateTime = $currentDateTime->format('Y-m-d H:i:s');

        // if($request->user()->kyc_status == '0'){
        //     $res = [
        //         'success' => false,
        //         'message' => "Please complete your kyc!"
        //     ];
        //     return response()->json($res, 200);
        $wallets = WalletType::whereIn('id',['2','8'])->get();

        // }
         
        // $response = [
        //     'success' => false, 
        //     'message' => "somthing wrong!"
        // ];
        // return response()->json($response, 200);
         
        $PlanGiftDraw = PlanJackpot::where('status',1)->get();
        if($PlanGiftDraw){
            $user_id =  $request->user()->id;
            
          

                if ($PlanGiftDraw->isNotEmpty()) {
                     
                
                    $PlanGiftDraw = $PlanGiftDraw->map(function ($draw) use ($user_id) {
                        $alreadyParticipate = JackpotDrawParticipate::where('status', 1)
                            ->where('jackpot_id', $draw->id)
                            ->where('user_id', $user_id)
                            ->first();
                            
                        $ParticipateCount = JackpotDrawParticipate::where('status', 1)
                            ->where('jackpot_id', $draw->id) 
                            ->count();

                
                        $draw->ttlcount = $ParticipateCount;
                        $draw->isParticipate = $alreadyParticipate ? true : false;
                
                        return $draw;
                    });
                }
                $dailyTime = Carbon::now()->endOfDay()->subMinute();
                $weeklyTime = Carbon::now()->next(Carbon::SUNDAY)->endOfDay()->subMinute();
                $response = [
                    'success' => true,
                    'data' => $PlanGiftDraw, 
                    'daily_time' => $dailyTime->format('Y-m-d H:i:s'),
                    'weekly_time' => $weeklyTime->format('Y-m-d H:i:s'), 
                    'current_time' => $formattedExpiryDateTime, 
                    'wallets' => $wallets,
                    'message' => "Plan Jackpot Lucky Draw fetch successfully."
                ];
        }else{
                $response = [
                    'success' => false,
                    'data' => [], 
                    'wallets' => $wallets,
                    'message' => "Plan Jackpot Lucky Draw Not Found."
                ];
        }
          
        return response()->json($response, 200); 
         
         
     }
     
     
    public function joinJackpotDraw(Request $request){



        if($request->user()->kyc_status == '0'){
            $res = [
                'success' => false,
                'message' => "Please complete your kyc!"
            ];
            return response()->json($res, 200);


        }


        // if($request->wallet_type == 'main_wallet' && $request->user()->ewallet_status == '0'){
        //     $res = [
        //         'success' => false,
        //         'message' => "Payout service down! Please try again after some time."
        //     ];
        //     return response()->json($res, 200);

        // }
        // if($request->wallet_type == 'gold_membership_wallet' && $request->user()->ewallet_status == '0'){
        //     $res = [
        //         'success' => false,
        //         'message' => "Payout service down! Please try again after some time."
        //     ];
        //     return response()->json($res, 200);

        // }
        // $response = [
        //     'success' => false, 
        //     'message' => "somthing wrong!"
        // ];
        // return response()->json($response, 200);
        $wallet_type =  $request->wallet_type;
        $id =  $request->id;
        $user_id =  $request->user()->id; 
       
        $alreadyParticipate = JackpotDrawParticipate::where('status',1)->where('jackpot_id',$id)->where('user_id',$user_id)->first();
        if($alreadyParticipate){
            $response = [
                'success' => false, 
                'message' => "You are already Participate!"
            ];
            return response()->json($response, 200);
          
            $plan = PlanJackpot::where('status',1)->where('id',$id)->first();
         }else{
            $plan = PlanJackpot::where('status',1)->where('id',$id)->first();
            $wlts=Wallet::where('user_id',$user_id)->first();
            $detectwallet = $wlts->$wallet_type;

            // $detectwallet = $wlts->bouns_wallet;
            // $main_wallet = $wlts->main_wallet;
            if($detectwallet >= $plan->pay_limit){
        //         if($detectwallet >= $plan->pay_limit){
        //             $wallet = 'one';
        //             $wallet1 = 'bouns_wallet';
        //             $payAmnt1 = $plan->pay_limit; 
        //         }else{
                    
                    
                    
                    
        //             $payAmnt2 = $plan->pay_limit -  $detectwallet; 
        //             $payAmnt1 = $plan->pay_limit - $payAmnt2;
                    
                    $payAmnt1 = $plan->pay_limit;
        //             $wallet = 'two';
        //             $wallet1 = 'bouns_wallet';
        //             $wallet2 = 'main_wallet';
        //             if($main_wallet < $payAmnt2){
        //                   $response = [
        //                         'success' => false, 
        //                         'message' => "Insufficient balance in E Wallet ."
        //                     ];
        //                     return response()->json($response, 200);
        //             }
                     
        //             if($main_wallet < $payAmnt2 && $detectwallet < $payAmnt1){
        //                     $response = [
        //                         'success' => false, 
        //                         'message' => "Insufficient balance in Both Wallet ."
        //                     ];
        //                     return response()->json($response, 200);
        //             }
        //         }
                
        //     }else{
        //         $wallet = 'one';
        //         $wallet1 = 'main_wallet';
        //         $payAmnt1 = $plan->pay_limit;
                
        //         if($main_wallet < $payAmnt1){
        //             $response = [
        //                 'success' => false, 
        //                 'message' => "Insufficient balance in E Wallet ."
        //             ];
        //             return response()->json($response, 200);
        //         }
        //     }
        //    if($wallet =='one'){
        //        Wallet::where('user_id',$user_id)->decrement($wallet1,$payAmnt1);
        //    }else{
        //        Wallet::where('user_id',$user_id)->decrement($wallet1,$payAmnt1);
        //        Wallet::where('user_id',$user_id)->decrement($wallet2,$payAmnt2);
        //    }  
        Wallet::where('user_id',$user_id)->decrement($wallet_type,$payAmnt1);
            JackpotDrawParticipate::create([
                 'user_id'=>$user_id,
                 'jackpot_id'=>$id,
                 'pay_amount'=>$plan->pay_limit,
                 'amount'=>$plan->value,
                 'status'=>1,
             
            ]);



            $response = [
                'success' => true, 
                'message' => "Paticipate Done."
            ];

        }else{
             
            $response = [
              'success' => false, 
              'message' => "insufficient fund in wallet."
          ];
           
       }
            
                
         }
         
         return response()->json($response, 200);
         
     }
     
    public function getJackpotParticipate(Request $request){
         
         $id = $request->id;
         $allParticapate = JackpotDrawParticipate::where('jackpot_id',$id)->orderBy('id', 'DESC')->get();
         $allParticapatecount = JackpotDrawParticipate::where('jackpot_id',$id)->count();
         $payAmount = JackpotDrawParticipate::where('jackpot_id',$id)->sum('pay_amount');

        $final =  $payAmount*75/100;
        if($allParticapate){
            $transactions = $allParticapate->map(function ($request) {
           
              return [
                'id' => $request->id,
                'name' => $request->user->name,  
                 'mobile' => $request->user->mobile,  
                'date' => Carbon::parse($request->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'), 
              ];
            });
            $response = [
                    'success' => true, 
                    'data' => $transactions, 
                    'amount' => $final, 
                    'count' => $allParticapatecount, 
                    'message' => "Participate fetch successfully."
            ];
        }else{
            $response = [
                    'success' => false,   
                    'message' => "Participate not found!"
            ]; 
        }
        
         
         return response()->json($response, 200); 
     }
     
    //////////////////////// Spinner ////////////////////////////////////////////////////////////////////
 
    public function joinSpinner(Request $request){

        if($request->user()->kyc_status == '0'){
            $res = [
                'success' => false,
                'message' => "Please complete your kyc!"
            ];
            return response()->json($res, 200);


        }
       
        $user_id =  $request->user()->id;
        
        $alreadyParticipate = SpinnerParticipate::where('status',1)->where('user_id',$user_id)->first();
        if($alreadyParticipate){
             $lastParticipationTime = Carbon::parse($alreadyParticipate->created_at);
            $currentTime = Carbon::now();
            $timeDifference = $currentTime->diffInMinutes($lastParticipationTime);

         if ($timeDifference < 10) {
                $response = [
                    'success' => false, 
                    'message' => "You are already Participate!"
                ];
                return response()->json($response, 200);
            }
        }
        
         $ttlParticipate = SpinnerParticipate::where('status',1)->where('user_id',$user_id)->count();
         if($ttlParticipate == 99){
                
                $transaction = Transaction::create([
                    'user_id' => $user_id,
                    'tx_user' => $user_id,
                    'amount' => 1,
                    'type' => 'credit',
                    'tx_type' => 'income',
                    'income' => 'spinner_income',
                    'status'  => 1,
                    'wallet'  => 'main_wallet', 
                    'status' => '1',
                    'remark' => "Receive Spinner Draw income of amount 1 Rs."
                ]);  
                
             Wallet::where('user_id',$user_id)->increment('main_wallet',1);
            \DB::table('spinner_participates')->delete();
            $response = [
                'success' => true, 
                'count' => $ttlParticipate, 
                'message' => "You have Participate successfully."
            ];
             
         }else{
             
            SpinnerParticipate::create([
                 'user_id'=>$user_id,  
                 'amount'=>1,
                 'status'=>1,
             
            ]);
            
            $response = [
                'success' => true, 
                'count' => $ttlParticipate,
                'message' => "Participate done."
            ];
            
                
         }
         
         return response()->json($response, 200);
         
     }
     
    public function ExistsSpinner(Request $request){
         
        $user_id =  $request->user()->id;
        
        $alreadyParticipate = SpinnerParticipate::where('status',1)->where('user_id',$user_id)->orderBy('created_at','DESC')->first();
       
        if($alreadyParticipate){
              $lastParticipationTime = Carbon::parse($alreadyParticipate->created_at);
            $currentTime = Carbon::now();
            $timeDifference = $currentTime->diffInMinutes($lastParticipationTime);
        
         if ($timeDifference < 10) {
            $response = [
                'success' => true, 
                'time' => Carbon::parse($lastParticipationTime)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'), 
                'message' => "You are already Participate!"
            ];
         }else{
             $response = [
                'success' => false, 
                'time' =>  Carbon::parse($lastParticipationTime)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'), 
                'message' => "not exists!"
            ];
         }
           
        }else{
             $response = [
                'success' => false, 
                'message' => "not exists!"
            ];
        }
         return response()->json($response, 200);
    }


     public function getWinsSpinner(){
        $transactions = Transaction::where('income','spinner_income')->where('status',1)->orderBy('created_at','DESC')->limit(50)->get();
        if($transactions){
            
            
            $record = $transactions->map(function ($request) {
           
              return [ 
                'name' => $request->user->name,  
                 'mobile' => $request->user->mobile,  
                 'amount' => $request->amount,  
                'date' => Carbon::parse($request->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'), 
              ];
            });
            
          
            $res = [
                'success' => true,
                'data' => $record,
                'message' => "Wins fetch successfully. "
            ];
        }else{
            
            $res = [
                'success' => false,
                'data' => '',
                'message' => "Wins not found!"
            ];
        }
         
         
        return response()->json($res, 200);
     }
    
    
    
    ///////////////////////get all wins ///////////////////////////////
    
    public function getDrawWins(Request $request){
        
        $validator = Validator::make($request->all(),[
                'type' => ['required', 'string', 'max:255' , 'exists:transactions,income'],
                'amount' => ['required', 'numeric', 'exists:transactions,amount'],   
        ]);
          
        if ($validator->fails()) {
            $res = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($res, 200);

        }
        $amount= $request->amount;
        $type= $request->type;
        if($type =='jackpot_draw_15' || $type == 'jackpot_draw_25'){
            $transactions = Transaction::where('income',$type)->where('status',1)->latest()->first();
            $amount = $transactions->amount;
        }
       
        
        $transactions = Transaction::where('income',$type)->where('amount',$amount)->where('status',1)->orderBy('created_at','DESC')->limit(50)->get();
        if($transactions){
            
            
            $record = $transactions->map(function ($request) {
           
              return [ 
                'name' => $request->user->name,  
                 'mobile' => $request->user->mobile,  
                 'amount' => $request->amount,  
                'date' => Carbon::parse($request->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'), 
              ];
            });
            
          
            $res = [
                'success' => true,
                'data' => $record,
                'message' => "Wins fetch successfully. "
            ];
        }else{
            
            $res = [
                'success' => false,
                'data' => '',
                'message' => "Wins not found!"
            ];
        }
         
         
        return response()->json($res, 200);
    }
    public function getJackpotWins(Request $request){
        
        $validator = Validator::make($request->all(),[
                'type' => ['required', 'string', 'max:255' , 'exists:transactions,income'],
        ]);
          
        if ($validator->fails()) {
            $res = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($res, 200);

        }
       
        $type= $request->type;
  
        $transactions = Transaction::where('income',$type)->where('status',1)->latest()->limit(20)->get();
        if($transactions){
            
            
            $record = $transactions->map(function ($request) {
           
              return [ 
                'name' => $request->user->name,  
                 'mobile' => $request->user->mobile,  
                 'amount' => $request->amount,  
                'date' => Carbon::parse($request->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'), 
              ];
            });
            
          
            $res = [
                'success' => true,
                'data' => $record,
                'message' => "Wins fetch successfully. "
            ];
        }else{
            
            $res = [
                'success' => false,
                'data' => '',
                'message' => "Wins not found!"
            ];
        }
         
         
        return response()->json($res, 200);
    }
    
}
