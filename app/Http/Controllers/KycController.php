<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kyc;
use App\Models\Bank;
use App\Models\Test;
use App\Models\WalletType;
use App\Rules\Checkbalance;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stringable;
use Validator;
use Illuminate\Support\Str;
 
class KycController extends Controller
{
    
   public function updatePanKyc(Request $request){
       
       
       
        
        $validator = Validator::make($request->all(),[
            'pan_no' => ['required'],
            'pan_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif','max:2048'],
            
        ]);
            
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 200);

        }
        
        $pan_number = $request->pan_no;
        // $timestamp = now()->format('YmdHis'); // Current timestamp
        // $randomString = Str::random(5); // Random string (adjust the length as needed)
        // $transactionId = $timestamp . $randomString;

        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        //   CURLOPT_URL => 'https://cyrusrecharge.in/api/total-kyc.aspx',
        //   CURLOPT_RETURNTRANSFER => true,
        //   CURLOPT_ENCODING => '',
        //   CURLOPT_MAXREDIRS => 10,
        //   CURLOPT_TIMEOUT => 0,
        //   CURLOPT_FOLLOWLOCATION => true,
        //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //   CURLOPT_CUSTOMREQUEST => 'POST',
        //   CURLOPT_POSTFIELDS =>'{
        //     "merchantId": "AP203874",
        //     "merchantKey": "5592118D36",
        //     "panNumber": "'.$pan_number.'",
        //     "type": "PANCARD",
        //     "txnid":"'.$transactionId.'",
        //   }',
        //   CURLOPT_HTTPHEADER => array(
        //     'Content-Type: application/json',
        //     'Cookie: ASP.NET_SessionId=letht04w1yrbqt41hpzid4s1'
        //   ),
        // ));
        
        // $result = json_decode(curl_exec($curl),true);
        
        // curl_close($curl);
        // print_r($result);
        // die();
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // if($result['success'] == true){
            DB::beginTransaction();
            try {
                $imageName = time().'.'.$request->pan_image->extension();
                
                $request->pan_image->storeAs('kyc', $imageName);
                
                $kycPan = Kyc::updateOrCreate(
                    ['user_id' => Auth::user()->id],
                    [
                        'pan_no' => $pan_number,
                        'pan_status' => 0,
                        'pan_image' => "app/kyc/".$imageName,
                    ]
                );
                DB::commit();   
                $response = [
                   'success' => true, 
                   'data' => '', 
                   'message' => "Pan Kyc request submit Successfully."
               ];
            } catch (\Exception $e) {
                    //throw $th;
                    DB::rollBack();
                    $responses = [
                        'success' => false,
                        'data' =>  $e,
                        'message' => "Something wrong!."
                    ];
            } 
           
        // }else{
            
        //      $response = [
        //       'success' => false, 
        //       'data' => $result['message_code'], 
        //       'message' => "Pan Kyc Failed!"
        //   ];
            
        // }
         
        return response()->json($response, 200);

   }
   
   public function getAadharOtp(Request $request){
       
          
        $validator = Validator::make($request->all(),[
            'aadhar_no' => ['required', 'digits:12', 'numeric', 'min:12'],
            
        ]);
            
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 200);

        }
        
        
        $timestamp = now()->format('YmdHis'); // Current timestamp
        $randomString = Str::random(5); // Random string (adjust the length as needed)
        $transactionId = $timestamp . $randomString;
        $aadhar_number = $request->aadhar_no;
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://cyrusrecharge.in/api/total-kyc.aspx',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
            "merchantId": "AP203874",
            "merchantKey": "5592118D36",
            "aadharno": "'.$aadhar_number.'",
            "type": "AADHARSENDOTP",
            "txnid":"'.$transactionId.'"
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Cookie: ASP.NET_SessionId=letht04w1yrbqt41hpzid4s1'
          ),
        ));
        
        $result = json_decode(curl_exec($curl),true);
        
        curl_close($curl);
        
        if($result['success'] == true){
            $response = [
                'success' => true,
                'client_id' => $result['data']['client_id'],
                'message' => 'Otp Sent Successfully.'
            ];
            
        }else{
            if($result['data']['valid_aadhaar'] == false){
                $response = [
                    'success' => false,
                    'client_id' => $result['data']['client_id'],
                    'message' => 'Invalid Aadhar number!Please Enter valid Aadhar number'
                ];  
            }else{
                $response = [
                    'success' => false,
                    'message' => 'Otp Failed!'
                ];   
            }
             
        }
       return response()->json($response, 200);
   }
   
   public function updateAadharKyc(Request $request){
       
        
        $validator = Validator::make($request->all(),[
            'aadhar_no' => ['required', 'digits:12', 'numeric', 'min:12'],
            // 'otp' => ['required', 'digits:6', 'numeric', 'min:6'],
            // 'client_id' => ['required', 'regex:/^([a-zA-Z0-9_-]+)$/'],
            'aadhar_front_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif','max:2048'],
            'aadhar_back_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif','max:2048'],
            
        ]);
            
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 200);

        }
        
        $aadhar_no = $request->aadhar_no;
        // $otp = $request->otp;
        // $client_id = $request->aadhar_no;
        // $timestamp = now()->format('YmdHis'); // Current timestamp
        // $randomString = Str::random(5); // Random string (adjust the length as needed)
        // $transactionId = $timestamp . $randomString;

        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        //   CURLOPT_URL => 'https://cyrusrecharge.in/api/total-kyc.aspx',
        //   CURLOPT_RETURNTRANSFER => true,
        //   CURLOPT_ENCODING => '',
        //   CURLOPT_MAXREDIRS => 10,
        //   CURLOPT_TIMEOUT => 0,
        //   CURLOPT_FOLLOWLOCATION => true,
        //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //   CURLOPT_CUSTOMREQUEST => 'POST',
        //   CURLOPT_POSTFIELDS =>'{
        //      "merchantId": "AP203874",
        //     "merchantKey": "5592118D36",
        //     "client_id": "'.$client_id.'",
        //     "otp": "'.$otp.'",
        //     "type": "AADHARGETOTP",
        //     "txnid":"'.$transactionId.'"
        // }',
        //   CURLOPT_HTTPHEADER => array(
        //     'Content-Type: application/json',
        //     'Cookie: ASP.NET_SessionId=letht04w1yrbqt41hpzid4s1'
        //   ),
        // ));

 
        // $result = json_decode(curl_exec($curl),true);
        
        // curl_close($curl);
        
        // if($result['success'] == true){
            DB::beginTransaction();
            try {
                
                $frontImageName = time() . '_front.' . $request->aadhar_front_image->extension();
                $request->aadhar_front_image->storeAs('kyc', $frontImageName);
                
                // For Aadhar Back Image
                $backImageName = time() . '_back.' . $request->aadhar_back_image->extension();
                $request->aadhar_back_image->storeAs('kyc', $backImageName);
                
                 
                $transaction = Kyc::updateOrCreate(
                   ['user_id' => Auth::user()->id],
                    [
                        'aadhar_no' => $aadhar_no,
                        'aadhar_status' => 0,
                        'aadhar_front_image' => "app/kyc/".$frontImageName,
                        'aadhar_back_image' => "app/kyc/".$backImageName,
                    ]
                );
                // DB::enableQueryLog();
                // $laQuery = DB::getQueryLog();
                // print_r($laQuery);
                // die();
                DB::commit();   
                $response = [
                   'success' => true, 
                   'data' => '', 
                   'message' => "Aadhar Kyc request submit Successfully."
                ];
            } catch (\Exception $e) {
                    //throw $th;
                    DB::rollBack();
                    $response = [
                        'success' => false,
                        'data' =>  $e,
                        'message' => "Something wrong!."
                    ];
            } 
           
        // }else{
            
        //      $response = [
        //       'success' => false, 
        //       'data' => '', 
        //       'message' => "Aadhar Kyc Failed!"
        //   ];
            
        // }
         
        return response()->json($response, 200);

   }
   
   
   public function getKycStatus(Request $request){
    $wallets = WalletType::whereIn('id',['1','2','3','8','13'])->get();
       $userId = $request->user()->id;
       $bankDetails  = Bank::where('user_id',$userId)->get();
       if($bankDetails){
                $exists = Kyc::where('user_id',$userId)->first();
                $exists['bank_status'] = 1;
                $exists['bank_data'] = $bankDetails;
               
               if($exists){
                   $response = [
                      'success' => true, 
                      'data' => $exists, 
                      'wallets' => $wallets, 
                      'message' => "kyc data fetch."
                    ];
                   
               }else{
                    $response = [
                      'success' => false, 
                      'data' => '', 
                      'wallets' => $wallets,
                      'message' => "Please complete your kyc!"
                    ];
               }  
       }else{
                    $response = [
                      'success' => false, 
                      'data' => '', 
                      'wallets' => $wallets,
                      'message' => "Please complete your kyc First!"
                    ];
       }
       
        return response()->json($response, 200);
       
   }
   
   public function getKycData(Request $request){
        $userId = $request->user()->id;
        $exists = Kyc::where('user_id',$userId)->first();
        if($exists){
            
                    $exists['pan_image'] = "https://pay30.in/storage/".$exists->pan_image;
                    $exists['aadhar_front_image'] = "https://pay30.in/storage/".$exists->aadhar_front_image;
                    $exists['aadhar_back_image'] = "https://pay30.in/storage/".$exists->aadhar_back_image;
                    if($exists->aadhar_no == null){
                        $exists['aadhar_status'] = 2;
                    }
                    if($exists->pan_no == null){
                        $exists['pan_status'] = 2;
                    }
                    
                   $response = [
                      'success' => true, 
                      'data' => $exists, 
                      'message' => "kyc data fetch."
                    ];
                   
        }else{
                    $response = [
                      'success' => false, 
                      'data' => '', 
                      'message' => "Please complete your kyc!"
                    ];
        }  
        
        return response()->json($response, 200);
   } 
   
}
