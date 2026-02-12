<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Rules\Checkbalance;
use App\Models\User;
use App\Models\Wallet;
use App\Rules\MatchPassword;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class FundController extends Controller
{
    public function transfer(Request $request)
    {   
        
        $user = Auth::user();
        return view('pages.transfer.index',['user'=> $user]);
    }
   
    public function fund_transfer(Request $request)
    {

        if($request->user()->kyc_status == '0'){
            $res = [
                'success' => false,
                'message' => "Please complete your kyc!"
            ];
            return response()->json($res, 200);


        }
        
        $wallet_type='fund_wallet';       
        $tx_type='transfer';       

        $request->validate([
            'username' => ['required', 'string', 'max:255' , 'exists:users,username'],
            'amount' => ['required','integer','min:5', new Checkbalance($wallet_type)],
            'password' => ['required','string', new MatchPassword($request)],
               
        ]);
         
        $user1 = User::where('username',$request->username)->first();
        
        $amnt = $request->amount;
        
        DB::beginTransaction();
        try {
            //code...
            
            $debitUser = array(
                    'user_id' => Auth::user()->id,
                    'tx_user' => $user1->id,
                    'type' => 'debit',
                    'amount' => $amnt,
                    'charges' => 0,
                    'wallet' => $wallet_type,
                    'tx_type' => $tx_type,
                    'status'  => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                );
            $creditUser = array(
                    'tx_user' => Auth::user()->id,
                    'user_id' => $user1->id,
                    'type' => 'credit',
                    'amount' => $amnt,
                    'charges' => 0,
                    'wallet' => $wallet_type,
                    'tx_type' => $tx_type,
                    'status'  => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                );
            $insert = array($debitUser,$creditUser);
            Transaction::insert($insert);
            
             
            Wallet::where('user_id',Auth::user()->id)->decrement('fund_wallet',$amnt);
            Wallet::where('user_id',$user1->id)->increment('fund_wallet',$amnt);
            DB::commit();
            $request->session()->flash('success', 'Transfer successful!');

        } catch (\Exception $e) {
            //throw $th;            
            DB::rollBack();
            $request->session()->flash('error', 'Something wrong!');

        }
        return redirect()->back();
    }
    
    public function convert(Request $request)
    {
        $user = Auth::user();
        return view('pages.convert.index',['user'=> $user]);
    }
    
    public function fund_convert(Request $request)
    {



        if($request->user()->kyc_status == '0'){
            $res = [
                'success' => false,
                'message' => "Please complete your kyc!"
            ];
            return response()->json($res, 200);


        }
        $from_wallet_type='main_wallet';       
        $wallet_type='fund_wallet';       
        $tx_type='convert';       

        $request->validate([
           
            'amount' => ['integer','min:5','required', new Checkbalance($from_wallet_type)],
               
        ]);
        
        $amnt = $request->amount;
        $x_charge = $amnt*5/100;
        $payable = $amnt-$x_charge;
        DB::beginTransaction();
        try {
            //code...
            
            $debitUser = array(
                    'user_id' => Auth::user()->id,
                    'tx_user' => Auth::user()->id,
                    'type' => 'debit',
                    'amount' => $amnt,
                    'charges' => $x_charge,
                    'wallet' => $from_wallet_type,
                    'tx_type' => $tx_type,
                    'status'  => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                );
            $creditUser = array(
                    'tx_user' => Auth::user()->id,
                    'user_id' => Auth::user()->id,
                    'type' => 'credit',
                    'amount' => $payable,
                    'charges' => 0,
                    'wallet' => $wallet_type,
                    'tx_type' => $tx_type,
                    'status'  => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                );
            $insert = array($debitUser,$creditUser);
            Transaction::insert($insert);
            
             
            Wallet::where('user_id',Auth::user()->id)->decrement($from_wallet_type,$amnt);
            Wallet::where('user_id',Auth::user()->id)->increment($wallet_type,$payable);
            DB::commit();
            $request->session()->flash('success', 'Fund Convert successful!');

        } catch (\Exception $e) {
            //throw $th;
            DB::rollBack();
            $request->session()->flash('error', 'Something wrong!');

        }
        return redirect()->back();
    }
    
    
    //////////////////////////////////////////////////////////////////////////////Api Start ///////////////////////////////////////////////////////////////////////////////////////////////////////
    public function fundConvert(Request $request)
    {

        
        $from_wallet_type=$request->from_wallet_type;       
        $wallet_type=$request->wallet_type;       
        $tx_type='convert';       

       
         $validator = Validator::make($request->all(),[
           'amount' => ['integer','min:5','required', new Checkbalance($from_wallet_type)]
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 200);

        }
        
        $amnt = $request->amount;
        $x_charge = $amnt*0/100;
        $payable = $amnt-$x_charge;
        $username = Auth::user()->username;
        DB::beginTransaction();
        try {
            //code...
            
            $debitUser = array(
                    'user_id' => Auth::user()->id,
                    'tx_user' => Auth::user()->id,
                    'type' => 'debit',
                    'amount' => $amnt,
                    'charges' => $x_charge,
                    'wallet' => $from_wallet_type,
                    'tx_type' => $tx_type,
                    'status'  => 1,
                    'remark'  => "Converted $amnt to fund wallet by $username",
                    'created_at' => now(),
                    'updated_at' => now(),
                );
            $creditUser = array(
                    'tx_user' => Auth::user()->id,
                    'user_id' => Auth::user()->id,
                    'type' => 'credit',
                    'amount' => $payable,
                    'charges' => 0,
                    'wallet' => $wallet_type,
                    'tx_type' => $tx_type,
                    'status'  => 1,
                    'remark'  => "Converted $amnt to fund wallet by $username",
                    'created_at' => now(),
                    'updated_at' => now(),
                );
            $insert = array($debitUser,$creditUser);
            Transaction::insert($insert);
            
             
            Wallet::where('user_id',Auth::user()->id)->decrement($from_wallet_type,$amnt);
            Wallet::where('user_id',Auth::user()->id)->increment($wallet_type,$payable);
            DB::commit();
            $response =[
                'success'=>true,                
                'data'=>'',                
                'message'=>'Fund Convert successful!'                
            ];

        } catch (\Exception $e) {
            //throw $th;
            DB::rollBack();
             
            $response =[
                'success'=>false,                
                'data'=>'',                
                'message'=>'Something wrong!'                
            ];
        }
        return response()->json($response, 200);
    }
    
    
    public function fundTransfer(Request $request)
    {

         
        
        $wallet_type=$request->wallet_type;     
     
        $tx_type='transfer';    
        
        $validator = Validator::make($request->all(),[
            'mobile' => ['required', 'string', 'max:255' , 'exists:users,mobile'],
            'amount' => ['required','integer','min:5', new Checkbalance($wallet_type)]
            // 'password' => ['required','string', new MatchPassword($request)],
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 200);

        }
         
        $user1 = User::where('mobile',$request->mobile)->first();
        $mobile = $request->mobile;
        $amnt = $request->amount;
     
        $from_mobile =Auth::user()->mobile;
       
        DB::beginTransaction();
        try {
            //code...
            
            $debitUser = array(
                    'user_id' => Auth::user()->id,
                    'tx_user' => $user1->id,
                    'type' => 'debit',
                    'amount' => $amnt,
                    'charges' => 0,
                    'wallet' => $wallet_type,
                    'tx_type' => $tx_type,
                    'status'  => 1,
                    'remark'  => "Fund Transafer $amnt to $mobile in $wallet_type",
                    'created_at' => now(),
                    'updated_at' => now(),
                );
            $creditUser = array(
                    'tx_user' => Auth::user()->id,
                    'user_id' => $user1->id,
                    'type' => 'credit',
                    'amount' => $amnt,
                    'charges' => 0,
                    'wallet' => $wallet_type,
                    'tx_type' => $tx_type,
                    'status'  => 1,
                    'remark'  => "Fund Received $amnt to $wallet_type by $from_mobile",
                    'created_at' => now(),
                    'updated_at' => now(),
                );
            $insert = array($debitUser,$creditUser);
            Transaction::insert($insert);
            
             
            Wallet::where('user_id',Auth::user()->id)->decrement($wallet_type,$amnt);
            Wallet::where('user_id',$user1->id)->increment($wallet_type,$amnt);
            
            
            $response =[
                'success'=>true,                
                'data'=>'',                
                'message'=>'Fund Transfer successfully.'                
            ];
            
            DB::commit();

        } catch (\Exception $e) {
            //throw $th;            
            DB::rollBack();
       
          
              $response =[
                'success'=>false,                
                'data'=>$e,                
                'message'=>'Something wrong!'                
            ];

        }
        return response()->json($response, 200);
    }
}
