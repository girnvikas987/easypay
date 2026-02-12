<?php

namespace App\Http\Controllers;

use App\Models\FundRequestMethod;
use App\Models\FundRequestMethodOption;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Test;
use App\Models\UserFundRequest;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Validator;
use App\Models\Wallet;
use Illuminate\Support\Str;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon; 
use Stringable;
class FundRequestController extends Controller
{



    public function getphonePayToken(){


            $curl = curl_init();
            
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api-preprod.phonepe.com/apis/pg-sandbox/v1/oauth/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'client_version=1&grant_type=&client_id=&client_secret=',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
            ));
            
            $response = curl_exec($curl);
            
            curl_close($curl);
            $result = json_decode($response,true);
            return $result['access_token'];

    }

    public function makeOrder(Request $request){
            $validator = Validator::make($request->all(),[  
                'amount' => ['required', 'numeric','min:100']
            ]);
        
            if ($validator->fails()) {
                    $response = [
                        'success' => false,
                        'message' => $validator->errors()
                    ];
                    return response()->json($response, 200);

            }

           $accessToken =  $this->getphonePayToken();
           $amount =  $request->amount;

            $timestamp = "TXR".now()->format('YmdHis'); // Current timestamp
            $randomString = Str::random(5); // Random string (adjust the length as needed)

            $transactionId = $timestamp . $randomString; 

 
                            
                $curl = curl_init();

                curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api-preprod.phonepe.com/apis/pg-sandbox/checkout/v2/sdk/order',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                "merchantOrderId": "'.$transactionId.'",
                "amount": '.$amount.',
                "paymentFlow": {
                    "type": "PG_CHECKOUT",
                    "message": "Payment message used for collect requests",
                    "merchantUrls": {
                    "redirectUrl": "https://www.xyz.com/PGIntegration/"
                    }
                }
                }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: O-Bearer '.$accessToken
                ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);
           
       
            $result = json_decode($response,true);

            $orderId = $result['orderId'];
            $redirectUrl = $result['token'];

            DB::beginTransaction();
            try {
                //code...
                UserFundRequest::create([
                    'user_id' => Auth::user()->id,
                    'utr_number' => $orderId,
                    'fund_request_method_option_id' => 1,
                    'fund_request_method_id' => 1,
                    'amount' => $amount, 
                    'screenshot' =>$transactionId,
                    'status'  => 0
                ]);

                
                    
                DB::commit();
                    $response =[
                            'status'=> true,
                            'url'=>  $redirectUrl,
                            'transactionId'=> $orderId,
                            'message'=> 'Fund Request successful.'
                        ];
    
            } catch (\Exception $e) {
                //throw $th;
                DB::rollBack();
                
                    $response =[
                            'status'=> false,
                            'error'=> $e,
                            'message'=> 'Something wrong!'
                        ];
    
            }       
                                 
            return response()->json($response, 200);   


    }



    public function handle(Request $request)
    {
        $event = $request->input('event');
        $payload = $request->input('payload');

        if (!$payload || !isset($payload['orderId'])) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        $orderId = $payload['orderId'];
        $status = $payload['state']; // "COMPLETED" or "FAILED"

        $fundRequest = UserFundRequest::where('utr_number', $orderId)->first();

        if ($fundRequest) {
           
       
 
        $paymentDetails = $payload['paymentDetails'][0] ?? null;
        $transactionId = $paymentDetails['transactionId'] ?? null;
        $paymentMode = $paymentDetails['paymentMode'] ?? null;
        $errorCode = $paymentDetails['errorCode'] ?? null;

        // Update status
        if ($status === 'COMPLETED') {
            $fundRequest->status = 1; // success
        } elseif ($status === 'FAILED') {
            $fundRequest->status = 2; // failed
        }
 
        $fundRequest->save();

         }
    }




    public function index(Request $request)
    {   
        
        $allmethods = FundRequestMethod::where('status',1)->get();
        return view('pages.fundrequest.create', [
            'methods'=>$allmethods,
        ]);
    }
    
    ////////////////////////////////////////////////// ADD Fund Api Start /////////////////////////////////////////////////////////////////////////////////////
    public function addFundRequest(Request $request){
        if($request->user()->kyc_status == '0'){
            $res = [
                'success' => false,
                'message' => "Please complete your kyc!"
            ];
            return response()->json($res, 200);


        }
        
        
          $validator = Validator::make($request->all(),[  
                'amount' => ['required', 'numeric','min:100']
            ]);
        
          if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->errors()
                ];
                return response()->json($response, 200);

            }
            
                    $amnt = $request->amount;
                    
                    $tokenData = $this->generateToken();
             
               
                    if($tokenData['error'] == false){
                        
                 
                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                      CURLOPT_URL => 'http://103.205.64.251:8080/clickncashapi/rest/auth/transaction/generate-upi',
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => '',
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 0,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => 'POST',
                      CURLOPT_POSTFIELDS =>'{
                    "amount": '.$amnt.',
                    "option": "INTENT"
                    }',
                      CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Authorization: Bearer  '.$tokenData['token']
                      ),
                    ));
                    
                    $responses = curl_exec($curl);

                    curl_close($curl);
     
                        
                        $result = json_decode($responses,true);
                        
                        if($result['status'] == 'INITIATED' && $result['statusCode'] == '0.0'){
                            
                               $url =  $result['intentData'];
                               
                                
                                 DB::beginTransaction();
                                try {
                                    //code...
                                    UserFundRequest::create([
                                        'user_id' => Auth::user()->id,
                                        'utr_number' => $result['txnId'],
                                        'fund_request_method_option_id' => 1,
                                        'fund_request_method_id' => 1,
                                        'amount' => $amnt, 
                                        'txs_status' => "pending",
                                        'status'  => 0
                                    ]);

                                    
                                     
                                    DB::commit();
                                     $response =[
                                              'status'=> true,
                                              'url'=> $url,
                                              'transactionId'=> $result['txnId'],
                                              'message'=> 'Fund Request successful.'
                                            ];
                        
                                } catch (\Exception $e) {
                                    //throw $th;
                                    DB::rollBack();
                                  
                                     $response =[
                                              'status'=> false,
                                              'error'=> $e,
                                              'message'=> 'Something wrong!'
                                            ];
                        
                                }       
                                 
        					}else{
        						 $response =[
                                  'status'=> false,
                                  'data' =>$result,
                                  'message'=> 'Transaction failed!Please try after some time.'
                                ];
        					}
                            
                        }else{
                                $response =[
                                  'status'=> false,
                                  'message'=> 'Token Expired!Please try after some time.'
                                ];
                        }
                       return response()->json($response, 200);   
    }
    
    
    public function FetchFundData(Request $request){
         


          $validator = Validator::make($request->all(),[  
                'transaction_id' => ['required', 'string','exists:user_fund_requests,utr_number']
            ]);
        
          if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->errors()
                ];
                return response()->json($response, 200);

            }
            
            $transaction_id = $request->transaction_id;
                   
            $Exists = UserFundRequest::where('utr_number',$transaction_id)->first();
            if($Exists){
                    $response =[
                      'status'=> true,
                      'data' =>$Exists,
                      'message'=> 'Transaction failed!Please try after some time.'
                    ];
   
              }else{
                    $response =[
                      'status'=> false,
                      'data' =>'',
                      'message'=> 'Transaction Id  not Match!'
                    ];
              }
        return response()->json($response, 200);   
    }
    
    
    
    
    public function addFund(){
        if($request->user()->kyc_status == '0'){
            $res = [
                'success' => false,
                'message' => "Please complete your kyc!"
            ];
            return response()->json($res, 200);


        }
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://cyrusrecharge.in/services_cyapi/payout_cyapi.aspx',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('MerchantID' => '','MerchantKey' => '','MethodName' => 'paytransfer','orderId' => '5458454545465','vpa' => '7357378249@ybl','Name' => 'anilkumar','amount' => '1','MobileNo' => '9813538826','TransferType' => 'UPI'),
        ));
        
        $response = json_decode(curl_exec($curl),true);
        
        curl_close($curl);
        print_r($response);
        
        
    }
    
    
    public function generateToken(){
             $res['error'] = true;
              $curl = curl_init();
    
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'http://103.205.64.251:8080/clickncashapi/rest/auth/generateToken',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>'{
                    "username":"",
                    "password":""
                }',
                  CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                  ),
                ));
            
            $response = json_decode(curl_exec($curl),true);
            
            curl_close($curl);
            
             if(empty($response['errors'])){
                 
                 $res['error'] = false;
                 $res['token'] = $response['payload']['token'];
             }
             
             return $res;
    }
    
    
    
    public function  callBack_payIn(Request $request){
        
        
    //         $response_array = file_get_contents('php://input');
    // 		$response_array = json_decode($response_array);
	        $agent_id = $request->txnId;
            //$agent_id = $response_array->txnId;
            $Exists = UserFundRequest::where('utr_number',$agent_id)->where('status',0)->first();
            if($Exists){
              $amount =  $Exists->amount;
              if($Exists->status != 1 && $request->status == 'SUCCESS'){
                  
                  $Exists->status = 1;
                  $Exists->txs_status = 'success';
                  $Exists->save();
                  Wallet::where('user_id',$Exists->user_id)->increment('main_wallet',$amount);
              }
              
            }
        
    }
    
    
    
    
    
    
    
    ////////////////////////////////////////////////// ADD Fund Api Start /////////////////////////////////////////////////////////////////////////////////////
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    public function options(Request $request) 
    {   
        $methodId=$request->methodId;//"success";
        $data = FundRequestMethodOption::where('status',1)->where('fund_request_method_id',$methodId)->get();
        return response()->json($data, 200, []);
        // $allmethods = FundRequestMethod::where('status',1)->get();
        // return view('pages.fundrequest.create', [
        //     'methods'=>$allmethods,
        // ]);
    }

    public function store(Request $request): RedirectResponse
    {   
        if($request->user()->kyc_status == '0'){
            $res = [
                'success' => false,
                'message' => "Please complete your kyc!"
            ];
            return response()->json($res, 200);


        } 
        
        $request->validate([             
                        
            'screenshot' => ['required','image','mimes:jpg,png,jpeg,gif,svg','max:1048'],            
            'method' => ['required'],            
            'option' => ['required'],            
            'utr_number' => ['utr_number','unique:user_fund_requests'],            
            'amount' => ['required','integer'],            
        ]);     

        $imageName = time().'.'.$request->screenshot->extension();
        $request->screenshot->move(storage_path('app/public/fundRequest/'),$imageName);
        $amnt = $request->amount;
        $utr_number = $request->utr_number;
        DB::beginTransaction();
        try {
            //code...
            UserFundRequest::create([
                'user_id' => Auth::user()->id,
                'fund_request_method_id' => $request->method,
                'fund_request_method_option_id' => $request->option,
                'screenshot'=> 'fundRequest/'.$imageName,
                'amount' => $amnt,
                'utr_number' => $utr_number,
                'status'  => 0
            ]);
             
            DB::commit();
            $request->session()->flash('status', 'Fund Request successful!');

        } catch (\Exception $e) {
            //throw $th;
            DB::rollBack();
            $request->session()->flash('error', 'Something wrong!');

        }       

        //Auth::login($user);

        return back();
    }
    ////////////////////////////////////////////////////////// Api Call //////////////////////////////////////////////////////////////
    public function history(Request $request)
    {   

        $userId=Auth::user()->id;
        $dir = User::find($userId);
        return view('pages.fundrequest.history', [
            'user' => Auth::user(),
        ])->with('transactions',$dir->fundRequests);
    }
    
    public function fund_request_details(){
        
        $payament = PaymentMethod::all();
        if($payament){
                $response =[
                      'status'=> true,
                      'data'=> $payament,
                      'message'=> 'Fund Details Succcessfully.'
                    ];
        }else{
                $response =[
                      'status'=> true,
                      'data'=> $payament,
                      'message'=> 'Fund Details Not Found!'
                    ];
        }
       
        return response()->json($response, 200);
        
    }
    
    
    public function fund_request(Request $request){
        


      
         $validator = Validator::make($request->all(),[
             'amount' => ['required', 'numeric'],
            'utr_number' => ['required', 'string','max:255','unique:user_fund_requests'],
            'slip_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'], // Added validation
        ]);
        
        
         if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => $validator->errors()->first('utr_number')
            ];
            return response()->json($response, 200);

        }
        
        $slipImageName = time() . '_slip.' . $request->slip_image->extension();
        $request->slip_image->storeAs('fundRequest', $slipImageName);
        
  
        
        $utrNumber = $request->utr_number;
        $amount = $request->amount;
        
         DB::beginTransaction();
        try {
            //code...
            UserFundRequest::create([
                'user_id' => Auth::user()->id,
                'utr_number' => $utrNumber,
                // 'fund_request_method_option_id' => 1,
                // 'fund_request_method_id' => 1,
                'screenshot' => "app/private/fundRequest/".$slipImageName,
                'amount' => $amount,
                'status'  => 0
            ]);
             
            DB::commit();
             $response =[
                      'status'=> true,
                      'error'=> '',
                      'message'=> 'Fund Request successful.'
                    ];

        } catch (\Exception $e) {
            //throw $th;
            DB::rollBack();
            
             $response =[
                      'status'=> false,
                      'error'=> $e,
                      'message'=> 'Something wrong!'
                    ];

        }       
        
       
        return response()->json($response, 200);
        
    }
    
   
    public function fundHistory(Request $request){
            $user = $request->user();
            $page = $request->page;
            
            // Retrieve fund requests for the user and order them by created_at in descending order
            $fundRequestsQuery = $user->fundRequests()->orderBy('created_at', 'desc');
            
            // Paginate the results
            $perPage = 20;
            $page = $page ?: 1; // Use the requested page, default to 1 if not provided
            
            $fundRequests = $fundRequestsQuery->paginate($perPage, ['*'], 'page', $page);
            
            // Check if there are any fund requests
            if ($fundRequests->count() > 0) {
                $response = [
                    'success' => true,
                    'data' => $fundRequests->items(),
                    'pagination' => [
                        'current_page' => $fundRequests->currentPage(),
                        'last_page' => $fundRequests->lastPage(),
                        'total_items' => $fundRequests->total(),
                    ],
                    'message' => 'Fund Requests Fetch Successfully.',
                ];
            } else {
                $response = [
                    'success' => true,
                    'data' => [],
                    'pagination' => [
                        'current_page' => 0,
                        'last_page' => 0,
                        'total_items' => 0,
                    ],
                    'message' => 'Fund requests not fetch!',
                ];
            }
            
            return response()->json($response, 200);

    }
    
    
    ////////////////////////////////////// fund Gateway start //////////////////////////////////////////////////////////
    
    
     public function fund_request_gateway(Request $request){
        

        

        $validator = Validator::make($request->all(),[
            'amount' => ['required', 'numeric'],  
            'wallet_type' => ['required', 'string'],  
        ]);
        
         
        $amount = $request->amount; 
        
        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => $validator->errors()->first('utr_number')
            ];
            return response()->json($response, 200);

        }
        
        $timestamp = now()->format('YmdHis'); // Current timestamp
        $randomString = Str::random(5); // Random string (adjust the length as needed)

        $transactionId = $timestamp . $randomString; 
        $namne =$request->user()->name;
        $email =$request->user()->email;
        $mobile =$request->user()->mobile;
        $wallet_type =$request->wallet_type;
                
                 
        $decoded_msg = urlencode($namne);
         
        
        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        //   CURLOPT_URL => 'https://api.ekqr.in/api/create_order',
        //   CURLOPT_RETURNTRANSFER => true,
        //   CURLOPT_ENCODING => '',
        //   CURLOPT_MAXREDIRS => 10,
        //   CURLOPT_TIMEOUT => 0,
        //   CURLOPT_FOLLOWLOCATION => true,
        //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //   CURLOPT_CUSTOMREQUEST => 'POST',
        //   CURLOPT_POSTFIELDS => 'key=54122137-6fc4-44fe-b983-53e083e50a5a&client_txn_id='.$transactionId.'&amount='.$amount.'&p_info=Product%20Name&customer_name='.$decoded_msg.'&customer_email='.$email.'&customer_mobile='.$mobile.'&redirect_url=https%3A%2F%2Fs2pay.life%2Fapi%2Fpayment-success&udf1=user%20defined%20field%201&udf2=user%20defined%20field%202&udf3=user%20defined%20field%203&=',
        //   CURLOPT_HTTPHEADER => array(
        //     'Content-Type: application/x-www-form-urlencoded'
        //   ),
        // ));
        
        // $responsed = json_decode(curl_exec($curl),true);
        
        // curl_close($curl);
        
        
        
        
        
        
        
        
        
            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://api.ekqr.in/api/create_order',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => 'key=54122137-6fc4-44fe-b983-53e083e50a5a&client_txn_id='.$transactionId.'&amount='.$amount.'&p_info=Product%2520Name&customer_name='.$decoded_msg.'&customer_email='.$email.'&customer_mobile='.$mobile.'&redirect_url=https%3A%2F%2Fs2pay.life%2Fapi%2Fpayment-success&udf1=user%2520defined%2520field%25201&udf2=user%2520defined%2520field%25201&udf3=user%2520defined%2520field%25201',
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
              ),
            ));
            
           $responsed = json_decode(curl_exec($curl),true);
            
            curl_close($curl);
         
        if($responsed['status'] == true){
            
            $orderId = $responsed['data']['order_id'];
            
           
                DB::beginTransaction();
                try {
                    //code...
                    $data = UserFundRequest::create([
                        'user_id' => Auth::user()->id,
                        'utr_number' => $transactionId,
                        'trans_Id' => $orderId,
                        'fund_request_method_option_id' => 1,
                        'fund_request_method_id' => 1,
                        'amount' => $amount, 
                        'wallet_type' => $wallet_type, 
                        'txs_status' => "pending",
                        'status'  => 0
                    ]);
                   
                   
                   
                  
                    DB::commit();
                     $response =[
                              'status'=> true,
                              'data'=> $responsed,
                              'message'=> 'Fund Request successful.'
                            ];
        
                } catch (\Exception $e) {
                    //throw $th;
                    DB::rollBack();
                    
                     $response =[
                              'status'=> false,
                              'error'=> $e,
                              'message'=> 'Something wrong!'
                            ];
        
                }    
            
        }else{
                $msg = $responsed['msg'];
                $response =[
                  'status'=> false,
                  'error'=> '',
                  'message'=> $msg
                ];
        }
         
         
        
       
        return response()->json($response, 200);
        
    }
    
    
    public function success(Request $request)
    {
         $txn_date = date('d-m-Y');
        $client_txn_id = $request->input('client_txn_id');
        $txn_id = $request->input('txn_id');
        
        $curl = curl_init();

                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://api.ekqr.in/api/check_order_status',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS => 'key=54122137-6fc4-44fe-b983-53e083e50a5a&client_txn_id='.$client_txn_id.'&txn_date='.$txn_date,
                  CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                  ),
                ));
                
                $response = curl_exec($curl);
                
                curl_close($curl);
                $result =  json_decode($response,true);
                
                if($result['data']['status'] =='success'){
                    $status = true;
                }else{
                    $status = false;
                }
               
             
        $Exists = UserFundRequest::where('trans_Id',$txn_id)->first();
        $user = [];
        if($Exists){
          $user =   User::find($Exists->user_id);
        }
        
            
         
        return view('success',['result'=>$result,'user'=>$user]);
    }
    
    
    public function callBackFundRequest(Request $request){
        
        $client_txn_id = $request->client_txn_id;
        $Exists = UserFundRequest::where('utr_number',$client_txn_id)->first();
        if($Exists){ 
            if($Exists->txs_status !='success' && $Exists->txs_status !='failure'){
                $wallet = $Exists->wallet_type;

                $amount = $Exists->amount;
                $userId = $Exists->user_id;
                $Exists->status = 1;
                $Exists->txs_status = 'success';
                $Exists->save();
                Wallet::where('user_id',$userId)->increment($wallet,$amount); 
            }
             
        }
        $test = Test::create([
                'remark' => $client_txn_id,
                'updated_at' => now(),
                'created_at' => now(),
        ]); 
    }
    
    public function callback_gateway(Request $request){
                $input = $request->all();
                $saltKey = '1bcb474a-51a6-4bf0-b3b5-98e1e60c9ac6';
                $saltIndex = 1;
                $resdsult  = base64_decode($input['response']); 
                 $test = Test::create([
                                'remark' => $resdsult,
                                'updated_at' => now(),
                                'created_at' => now(),
                    ]); 
                $result = json_decode($resdsult,true);     
                if($result['success'] == true){
                   
                      $transId = $result['data']['merchantTransactionId'];
                      $Exists = UserFundRequest::where('utr_number',$transId)->first();
                        if($Exists){
                          
                          $finalXHeader = hash('sha256','/pg/v1/status/'.$result['data']['merchantId'].'/'.$result['data']['merchantTransactionId'].$saltKey).'###'.$saltIndex;
    
                            //$response = Curl::to('https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/status/'.$result['data']['merchantId'].'/'.$result['data']['merchantTransactionId'])
                            $response = Curl::to('https://api.phonepe.com/apis/hermes/pg/v1/status/'.$result['data']['merchantId'].'/'.$result['data']['merchantTransactionId'])
                                ->withHeader('Content-Type:application/json')
                                ->withHeader('accept:application/json')
                                ->withHeader('X-VERIFY:'.$finalXHeader)
                                ->withHeader('X-MERCHANT-ID:'.$result['data']['merchantId'])
                                ->get();
                                
                               $res =  json_decode($response,true);
                             
                            if($res['success'] == true){
                                  
                                      if($res['code'] == 'PAYMENT_SUCCESS'){
                                 
                                         
                                             $amount = $res['data']['amount'];
                                             $amnt = $amount/100; 
                                      
                                        
                                            if($Exists->txs_status != 'success' && $amnt == $Exists->amount){
                                                 $userId = $Exists->user_id;
                                                 $Exists->txs_status = Str::lower($res['data']['responseCode']);
                                                 $Exists->status = 1;
                                                 $Exists->save();
                                                 Wallet::where('user_id',$userId)->increment('fund_wallet',$amnt);
                                            }else{
                                                $Exists->txs_status = Str::lower($res['data']['responseCode']);
                                                $Exists->save();
                                            } 
                                         }else{
                                            $Exists->txs_status = Str::lower($res['data']['responseCode']);
                                            $Exists->save();
                                         }
                                 
                                }else{
                                    $Exists->txs_status = "failed";
                                    $Exists->save();
                                }
                        } 
                       
                    }else{
                        $transId = $result['data']['merchantTransactionId'];
                            // $test = Test::create([
                            //         'remark' => $transId,
                            //         'updated_at' => now(),
                            //         'created_at' => now(),
                            // ]); 
                            $Exists = UserFundRequest::where('utr_number',$transId)->first();
                            if($Exists){                   
                             $Exists->txs_status = Str::lower($result['data']['responseCode']); 
                             $Exists->save();                
                         }
                    }  
    }
    
    
    public function approveFundRequest(Request $request){

        if($request->user()->kyc_status == '0'){
            $res = [
                'success' => false,
                'message' => "Please complete your kyc!"
            ];
            return response()->json($res, 200);


        }
        $validator = Validator::make($request->all(),[ 
            'amount' => ['required', 'numeric'],
            'utr_number' => ['required', 'string','max:255'], 
            'status' => ['required', 'string','max:255'], 
            ]);
            
            if ($validator->fails()) {
                $response = [
                    'status' => false,
                    'message' => $validator->errors()->first('utr_number')
                ];
                return response()->json($response, 200); 
            }
            $utrNumber = $request->utr_number;
            $status = $request->status;
            $amount = $request->amount;
            $Exists = UserFundRequest::where('utr_number',$utrNumber)->first();
            if($Exists->txs_status != 'success' && $amount == $Exists->amount){
                 $userId = $Exists->user_id;
                 $Exists->txs_status = $status;
                 $Exists->status = 1;
                 $Exists->save();
                 Wallet::where('user_id',$userId)->increment('fund_wallet',$amount); 
                    $response =[
                          'status'=> true,
                          'error'=> '',
                          'message'=> 'Add Fund Updated Successfully.'
                    ];
            }else{
                    $response =[
                          'status'=> true,
                          'error'=> '',
                          'message'=> 'This Transaction Id Not Exists!'
                    ];
            }
            return response()->json($response, 200);
            
    } 
    
    public function userCancelled(Request $request){
        
        
          $validator = Validator::make($request->all(),[ 
            'utr_number' => ['required', 'string','max:255'], 
            'amount' => ['required', 'string','max:255'], 
            'utr_number' => ['required', 'string','max:255'], 
            ]);
        
        
            if ($validator->fails()) {
                $response = [
                    'status' => false,
                    'message' => $validator->errors()->first('utr_number')
                ];
                return response()->json($response, 200); 
            }
            
            $utrNumber = $request->utr_number;
            $Exists = UserFundRequest::where('utr_number',$utrNumber)->first();
            if($Exists){
                $Exists->txs_status = 'user_cancelled';
                $Exists->save();
                $response =[
                  'status'=> true,
                  'error'=> '',
                  'message'=> 'Transaction Updated Successfully.'
                ];
                
            }else{
                $response =[
                  'status'=> true,
                  'error'=> '',
                  'message'=> 'Transaction not Exists!'
                ];
            }
            return response()->json($response, 200);
            
    }
}
