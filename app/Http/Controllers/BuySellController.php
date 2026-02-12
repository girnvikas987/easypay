<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Rules\Checkbalance;
use Illuminate\Validation\Rule;
use App\Models\Transaction; 
use App\Models\Setting; 
use App\Models\Wallet;
use App\Models\WalletType;
use Illuminate\Http\Response; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stringable;
use Illuminate\Support\Carbon;
use Validator;
class BuySellController extends Controller
{
    
    
    public function buyBtc(Request $request){

        if($request->user()->kyc_status == '0'){
            $res = [
                'success' => false,
                'message' => "Please complete your kyc!"
            ];
            return response()->json($res, 200);


        }

       
        $wallet_type2 = $request->wallet_type;
         $validator = Validator::make($request->all(),[
                'inr_amount' => ['required', 'numeric', 'min:100',new Checkbalance($wallet_type2)],   
            ]);
         
        

        if ($validator->fails()) {
            $res = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($res, 200);

        }
        $mobile = Auth::user()->mobile;
        $wallet_type = 'crypto_wallet';
        $wallet_type2 = 'main_wallet';
         $inrAmnt = $request->inr_amount;
         
         $result = $this->fetchBtcPrice(); 
         if($result['success'] == true){
             $usdtPrice = 87.60; 
             $usdt = $inrAmnt/$usdtPrice; 
             $priceBtc =$result['data']['price'];
             $btc = $usdt/$priceBtc;
             $btcRounded = number_format($btc, 8, '.', '');
             if($btcRounded > 0){
                  $transaction = Transaction::create([
                    'user_id' => Auth::user()->id,
                    'tx_user' => Auth::user()->id,
                    'amount' => $btcRounded,
                    'rate' => $priceBtc,
                    'type' => 'credit',
                    'tx_type' => 'buy_btc',
                    'status'  => 1,
                    'wallet'  => $wallet_type, 
                    'remark'  => "$mobile Buy BTC now - you can got $btcRounded BTC for  $inrAmnt",
                ]);
                
                  $transaction2 = Transaction::create([
                    'user_id' => Auth::user()->id,
                    'tx_user' => Auth::user()->id,
                    'amount' => $inrAmnt,
                    'rate' => $priceBtc,
                    'type' => 'debit',
                    'tx_type' => 'buy_btc',
                    'status'  => 1,
                    'wallet'  => $wallet_type2, 
                    'remark'  => "$mobile Buy BTC now - you can got $btcRounded BTC for ₹ $inrAmnt",
                ]);
                
                Wallet::where('user_id',Auth::user()->id)->increment('crypto_wallet',$btcRounded);
                Wallet::where('user_id',Auth::user()->id)->decrement($wallet_type2,$inrAmnt);
                $success['date'] = Carbon::parse($transaction2->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s');
                $res = [
                    'success' => true,
                    'data' => $success,
                    'message' => 'you have successfully Buy btc.'
                ];
             }else{
                 $res = [
                    'success' => false,
                    'message' => 'somthing wrong!'
                ];
                 
            }
            
             
         }else{
             $res = [
                'success' => false,
                'message' => $result['message']
            ];
             
         }
         
        return response()->json($res, 200);
        
    }
    
    public function sellBtc(Request $request){
       
        if($request->user()->kyc_status == '0'){
            $res = [
                'success' => false,
                'message' => "Please complete your kyc!"
            ];
            return response()->json($res, 200);


        }
        $wallet_type2 = $request->wallet_type;
        $validator = Validator::make($request->all(),[
                'btc_amount' => ['required', 'numeric', 'min:0.0000001',new Checkbalance($wallet_type2)],   
        ]);
          
        if ($validator->fails()) {
            $res = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($res, 200);

        }
        $mobile = Auth::user()->mobile;
        $wallet_type = 'crypto_wallet';
       
        $btcAmnt = $request->btc_amount;
         
         $result = $this->fetchBtcPrice(); 
         if($result['success'] == true){
            $usdtPrice = Setting::where('type','usdt_rate')->value('value');
           
            $priceBtc =$result['data']['price'];
            $usdt = $btcAmnt*$priceBtc;
            $btcRounded = number_format($usdt, 8, '.', '');
            $inrAmnt = $usdt*$usdtPrice;
              
            
             if($btcRounded > 0){
                  $transaction = Transaction::create([
                    'user_id' => Auth::user()->id,
                    'tx_user' => Auth::user()->id,
                    'amount' => $btcAmnt,
                    'rate' => $priceBtc,
                    'type' => 'debit',
                    'tx_type' => 'sell_btc',
                    'status'  => 1,
                    'wallet'  => $wallet_type, 
                    'remark'  => "$mobile Sell BTC now - you can got $inrAmnt ₹ for BTC $btcAmnt",
                ]);
                
                  $transaction2 = Transaction::create([
                    'user_id' => Auth::user()->id,
                    'tx_user' => Auth::user()->id,
                    'amount' => $inrAmnt,
                    'rate' => $priceBtc,
                    'type' => 'credit',
                    'tx_type' => 'sell_btc',
                    'status'  => 1,
                    'wallet'  => $wallet_type2, 
                    'remark'  => "$mobile Sell BTC now - you can got $inrAmnt ₹ for BTC $btcAmnt",
                ]);
                
                Wallet::where('user_id',Auth::user()->id)->decrement('crypto_wallet',$btcAmnt);
                Wallet::where('user_id',Auth::user()->id)->increment($wallet_type2,$inrAmnt);
               $success['date'] = Carbon::parse($transaction2->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s');
                $res = [
                    'success' => true,
                    'data' => $success,
                    'message' => 'you have successfully Sell btc.'
                ];
                
             }else{
                 $res = [
                    'success' => false,
                    'message' => 'somthing wrong!'
                ];
                 
            }
            
             
         }else{
             $res = [
                'success' => false,
                'message' => $result['message']
            ];
             
         }
         
        return response()->json($res, 200);
        
    }
    
    public function buyGold(Request $request){
        if($request->user()->kyc_status == '0'){
            $res = [
                'success' => false,
                'message' => "Please complete your kyc!"
            ];
            return response()->json($res, 200);


        }
        $wallet_type = $request->wallet_type;
        $validator = Validator::make($request->all(),[
                'inr_amount' => ['required', 'numeric', 'min:100',new Checkbalance($wallet_type)],   
        ]);
          
        if ($validator->fails()) {
            $res = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($res, 200);

        }
        $mobile = Auth::user()->mobile;
        
        $wallet_type2 = 'gold_wallet';
        $inr_amount = $request->inr_amount;
          
         
            $goldPrice = Setting::where('type','gold_rate')->value('value');
            $onegram =10/$goldPrice; 
            $gramGold = $inr_amount*$onegram; 
            
             if($gramGold > 0){
                  $transaction = Transaction::create([
                    'user_id' => Auth::user()->id,
                    'tx_user' => Auth::user()->id,
                    'amount' => $inr_amount,
                    'rate' => $goldPrice,
                    'type' => 'debit',
                    'tx_type' => 'buy_gold',
                    'status'  => 1,
                    'wallet'  => $wallet_type, 
                    'remark'  => "$mobile Buy Gold now - you can got $gramGold gram for  $inr_amount",
                ]);
                
                  $transaction2 = Transaction::create([
                    'user_id' => Auth::user()->id,
                    'tx_user' => Auth::user()->id,
                    'amount' => $gramGold,
                    'rate' => $goldPrice,
                    'type' => 'credit',
                    'tx_type' => 'buy_gold',
                    'status'  => 1,
                    'wallet'  => $wallet_type2, 
                    'remark'  => "$mobile Buy Gold now - you can got $gramGold gram for ₹ $inr_amount",
                ]);
                
                Wallet::where('user_id',Auth::user()->id)->decrement($wallet_type,$inr_amount);
                Wallet::where('user_id',Auth::user()->id)->increment('gold_wallet',$gramGold);
                 $success['date'] = Carbon::parse($transaction2->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s');
                $res = [
                    'success' => true,
                    'data' => $success,
                    'message' => 'you have successfully Buy Gold.'
                ];
               
             }else{
                 $res = [
                    'success' => false,
                    'message' => 'somthing wrong!'
                ];
                 
            }
          
         
        return response()->json($res, 200);
        
    }
    
    public function sellGold(Request $request){
        if($request->user()->kyc_status == '0'){
            $res = [
                'success' => false,
                'message' => "Please complete your kyc!"
            ];
            return response()->json($res, 200);


        }
        $wallet_type = $request->wallet_type;
        $validator = Validator::make($request->all(),[
                'gold_gram' => ['required', 'numeric', 'min:1',new Checkbalance($wallet_type)],   
        ]);
          
        if ($validator->fails()) {
            $res = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($res, 200);

        }
        $mobile = Auth::user()->mobile;
         
        $wallet_type2 = 'gold_wallet';
        $gold_gram = $request->gold_gram;
         
 
            $goldPrice = Setting::where('type','gold_rate')->value('value');
            $onegram =$goldPrice/10; 
            $inr_amount = $gold_gram*$onegram; 
           
             if($gold_gram > 0){
                  $transaction = Transaction::create([
                    'user_id' => Auth::user()->id,
                    'tx_user' => Auth::user()->id,
                    'amount' => $gold_gram,
                    'rate' => $goldPrice,
                    'type' => 'debit',
                    'tx_type' => 'sell_gold',
                    'status'  => 1,
                    'wallet'  => $wallet_type2, 
                    'remark'  => "$mobile Sell Gold now - you can got  ₹ $inr_amount for $gold_gram gram",
                ]);
                
                  $transaction2 = Transaction::create([
                    'user_id' => Auth::user()->id,
                    'tx_user' => Auth::user()->id,
                    'amount' => $inr_amount,
                    'rate' => $goldPrice,
                    'type' => 'credit',
                    'tx_type' => 'sell_gold',
                    'status'  => 1,
                    'wallet'  => $wallet_type, 
                    'remark'  => "$mobile Sell Gold now - you can got   $inr_amount for $gold_gram gram",
                ]);
                
                Wallet::where('user_id',Auth::user()->id)->increment($wallet_type,$inr_amount);
                Wallet::where('user_id',Auth::user()->id)->decrement('gold_wallet',$gold_gram);
                $success['date'] = Carbon::parse($transaction2->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s');
                $res = [
                    'success' => true,
                    'data' => $success,
                    'message' => 'you have successfully Sell Gold.'
                ];
                
             }else{
                 $res = [
                    'success' => false,
                    'message' => 'somthing wrong!'
                ];
                 
            }
        
         
        return response()->json($res, 200);
        
    }
     
    public function fetchBtcPrice(){
        $response = Http::get('https://api.binance.com/api/v3/ticker/price', [
            'symbol' => 'BTCUSDT'
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return [
                'success' => true,
                'data' => $data,
            ] ;
        } else {
            return [
                'success' => false,
                'message' => 'Failed to fetch data from Binance',
            ];
        }
        
    }
    
    public function getPrice(){
    $wallets = WalletType::whereIn('id',['13','17'])->get();
     $data = Setting::whereIn('type', ['usdt_rate', 'gold_rate'])->get();

        if($data){
       
            $res = [
                'success' => true,
                'data' => $data,
                'wallet' => $wallets,
                'message' => 'Data fetch succesfully.',
            ] ;
        }else{
             $res = [
                'success' => false,
                'data' => '',
                'wallet' => '',
                'message' => 'Data not found!',
            ] ;
        }
        return response()->json($res, 200);
        
    }
    
    
}
