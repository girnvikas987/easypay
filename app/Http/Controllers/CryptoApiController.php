<?php

namespace App\Http\Controllers;

use App\Models\CryptoApiPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
class CryptoApiController extends Controller
{
    public function index()
    {   

        $getPending = CryptoApiPayment::where('user_id',Auth::user()->id)->where('end_at','>',now())->where('status','pending')->first();
        if(!$getPending){
            return view('pages.cryptoapi.index', [ 'user' => Auth::user(),]);
        }else{
            return view('pages.cryptoapi.proceed', [ 'wallet_address' => $getPending->wallet_address,'amount' => $getPending->amount, 'end_at'=> $getPending->end_at]);
             
        }
    }
    public function make_payment($tx_id){
        $getPending = CryptoApiPayment::where('tx_id',$tx_id)->where('user_id',Auth::user()->id)->where('end_at','>',now())->where('status','pending')->first();
        if($getPending){
            return view('pages.cryptoapi.proceed', [ 'wallet_address' => $getPending->wallet_address,'amount' => $getPending->amount, 'end_at'=> $getPending->end_at ]);
        }else{
            return redirect()->route('crypto.index');
        }
    }

    public function proceed(Request $request)
    {
        $request->validate([
            'amount' => ['required', 'integer','min:10'],
            'token_id' => ['required'],      
        ]);

        $CryptoApiPayment = CryptoApiPayment::create([
            'user_id' => Auth::user()->id,
            'token_id' => $request->token_id,
            'amount' => $request->amount,
            'tx_id' => Str::random(20),
        ]);

        $tx_id=$CryptoApiPayment->tx_id;

            $curl = curl_init();
            $callback_url = "https://test.mlmdx.com/api/crypto/callback";
            curl_setopt_array($curl, [
            
            CURLOPT_URL => "https://test.mlmdx.com/CryptoApi/public/api/get-address",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "token_id=1&amount=1&tx_id=$tx_id&callback_url=$callback_url",
            CURLOPT_HTTPHEADER => [
                "Accept: */*",
                "Authorization: ",
                "Content-Type: application/x-www-form-urlencoded",
                "User-Agent: Thunder Client (https://www.thunderclient.com)"
            ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);


            curl_close($curl);

            if ($err) {                
                return back()->with('error',"Something wrong!");
            } else {
                $data=json_decode($response,true);                 
                $CryptoApiPayment->wallet_address = $data['data']['wallet'];
                $CryptoApiPayment->end_at = Carbon::parse($data['data']['end_at']);
                $CryptoApiPayment->save();  
                return redirect()->route('crypto.make.payment',['tx_id'=>$tx_id]);           
            }


    }
    
    public function history(Request $request)
    {   
        
        
        $allmethods = CryptoApiPayment::where('user_id',Auth::user()->id)->get();
        
        return view('pages.cryptoapi.history', [
            'transactions'=>$allmethods,
        ]);
    }
    
    public function callback(Request $request)
    {
        $request->validate([
            'api_key' => ['required'],
            'tx_id' => ['required'],     
            'paid' => ['required'],     
            'status' => ['required'],     
                
        ]);

        $tx_id=$request->tx_id;
        /// check api key
        if($request->api_key==""){
            $CryptoApiPayment = CryptoApiPayment::where('tx_id',$tx_id)->whereIn('status',['pending','proceed'])->first();
            
            if($CryptoApiPayment)
            {
                if($request->hash){
                    $CryptoApiPayment->hash = $request->hash;
                }
                $CryptoApiPayment->paid = $request->paid;
                $CryptoApiPayment->status = $request->status;
                $CryptoApiPayment->save();
                
                
                Wallet::where('user_id',$CryptoApiPayment->user_id)->increment('fund_wallet',$CryptoApiPayment->paid);
                $response = [
                    'success' => true,
                    'message' => "Success"
                ];
                return response()->json($response, 200);
            }
            
            
            
        }else{
            $response = [
                'success' => false,
                'message' => "Invalid Api Key."
            ];
            return response()->json($response, 404);
        }

    }
}
