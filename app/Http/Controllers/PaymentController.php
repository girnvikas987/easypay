<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Setting;
use App\Models\Test;
use App\Models\Transaction;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use Validator;
class PaymentController extends Controller
{
    public function createOrder(Request $request)
    {
        
        $defultWithdrawalStatus = Setting::where('type','fund_on_off')->value('status');

        if($defultWithdrawalStatus == '1'){

    $validator = Validator::make($request->all(),[ 
        'wallet_type' => ['required', 'string', 'max:255'],   
        'amount' => ['required', 'string'],   
        'payment_id' => ['required','max:255'],   
       ]);
       
       if ($validator->fails()) {
           $res = [
               'success' => false,
                 'data' =>  '',
               'message' => $validator->errors()
           ];
           return response()->json($res, 200);

       }
   

        $userInfo = $request->user();
        $api = new Api('rzp_live_644sEfB3XkFJUe','ykYU97DGK2CI8gKp0qk5zslx');

        $name = $userInfo->name;
        $mobile = $userInfo->mobile;
        $email = $userInfo->email;
 

        $amount = $request->amount;
        $payment_id = $request->payment_id;
        $wallet_type = $request->wallet_type;

        $order = $api->order->create([
            'receipt'=> $payment_id,
            'amount'=> $amount*100,
            'currency'=> 'INR',
        ]);

        if($order['status']=='created'){
            $transaction = Payment::create([
                'user_id' => $userInfo->id,
                'amount' => $amount, 
                'name' => $name, 
                'mobile' => $mobile, 
                'wallet_type' => $wallet_type, 
                'email' => $email, 
                'order_id' => $order['id'], 
                'payment_id' => $payment_id, 
                'status'  => 0,
            ]);
            $responses = [
                'success' => true,
                'order_id' => $order['id'], 
                'key' =>  'rzp_live_644sEfB3XkFJUe', 
                'message' => "order Generated Sucessfully."
            ];

        }else{
            $responses = [
                'success' => false,
                'order_id' =>'',
                'key' =>  '', 
                'message' => "order not generated!please try after some time."
            ];
        }

    }else{
        $responses = [
            'success' => false,
            'data' =>  '',
            'message' => "Add fund server busy please try again after sometime"
        ];

}
 
      
        return response()->json($responses, 200);
       

    }



    public function updateOrder(Request $request){



        

        $validator = Validator::make($request->all(),[ 
            'order_id' => ['required', 'string', 'max:255','exists:payments,order_id'],   
            'payment_id' => ['required','string','max:255','exists:payments,payment_id'],   
            'status' => ['required', 'string'],   
           ]);
           
           if ($validator->fails()) {
               $res = [
                   'success' => false,
                     'data' =>  '',
                   'message' => $validator->errors()
               ];
               return response()->json($res, 200);
    
           }

           $exists = Payment::where('order_id',$request->order_id)->where('payment_id',$request->payment_id)->where('status','0')->first();
           if($exists){
            $status = 2;
            if($request->status=='success'){
                $wallet_type = $exists->wallet_type;
                Wallet::where('user_id',$exists->user_id)->increment($wallet_type,$request->amount);
                $wlt = $request->user()->wallet;
                $closeAmnt = $wlt->$wallet_type;
                $transactions = Transaction::create([
                    'user_id' => $exists->user_id,
                    'tx_user' => $exists->user_id,
                    'amount' => $exists->amount,
                    'charges' => 0,
                    'type' => 'credit',
                    'tx_type' => 'add_fund',
                    'status'  => 2,
                    'wallet'  =>$wallet_type,
                    'close_amount'  => $closeAmnt,
                    'tx_id'  => $request->order_id,
                    'remark'  => 'Add Fund  of  '.$exists->amount.' amount',
                
                ]);
                $exists->pay_id = $request->pay_id;

                $status = 1;
            }

            
 
            $exists->status = $status;
            $exists->save();

            if($request->status == 'failure'){
                $res = [
                    'success' => false,
                    'data' =>  '',
                    'message' => 'Transaction Failed!'
                ]; 
            }else{
                $res = [
                    'success' => true,
                    'data' =>  '',
                    'date' => Carbon::parse($exists->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
                    'message' => 'order update successfully.'
                ]; 
            }

               
             
           }else{
            
                $res = [
                    'success' => false,
                    'data' =>  '',
                    'message' => "Record Not Found From This Order Id!"
                ];

           }

       
           return response()->json($res, 200);
        
    }

    function verify(Request $request)
    {
        $success = true;
        $error = "Payment Failed!";

        if (empty($request->razorpay_payment_id) === false) {
            $api = new Api('rzp_test_5IzhKlnGRF2qaH', '8WjctTo29s6Y4i69nO26udcE');
            try {
                $attributes = [
                    'razorpay_order_id' => $request->razorpay_order_id,
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'razorpay_signature' => $request->razorpay_signature
                ];
                $api->utility->verifyPaymentSignature($attributes);
            } catch (SignatureVerificationError $e) {
                $success = false;
                $error = 'Razorpay Error : ' . $e->getMessage();
            }
        }

        if ($success === true) {
            // Update database with success data
            // Redirect to success page

            Test::creat([
                'remark'=>"failed",
            ]);
            
        } else {
            Test::creat([
                'remark'=>"failed",
            ]);
        }
    }
}
