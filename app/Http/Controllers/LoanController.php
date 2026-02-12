<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\LoanPackage;
use App\Models\LoanList;
use App\Models\LoanInvestment;
use App\Models\Loan;
use App\Models\Transaction;
use Validator;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Rules\ValidateLoanPackage;
use App\Rules\ValidateLoan;
use Illuminate\Http\Request;
use App\Helper\Distribute;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

class LoanController extends Controller
{
    
    public function getPackage(){
        
        $packages = LoanPackage::where('status',1)->get();
        
          $response = [
                'success' => true,
                'data' => $packages
            ];
        return response()->json($response, 200);
        
    }
    
    
    public function list(Request $request){
        
                $userId = $request->user()->id;
               
                // Fetch active loans for the user
                $activeLoans = Loan::where('user_id', $userId)
                   ->where('status', 1)
                   ->pluck('loan_id')
                   ->toArray();
     
                // Fetch packages with status 1
                $packages = LoanList::where('status', 1)->get();
           
                // Modify packages to set 'payable' based on loan status
                $packages = $packages->map(function ($package) use ($activeLoans,$userId) {
                    if (in_array($package->id, $activeLoans)) {
                        // Check if the loan is payable or paid and set 'payable' accordingly
                        $loanStatus = Loan::where('loan_id', $package->id)->where('user_id',$userId)->value('loan_status');
                  
                        if ($loanStatus === 'Payable') {
                            $package->payable = 1; // Payable
                        } elseif ($loanStatus === 'Paid') {
                            $package->payable = 2; // Paid
                        } else {
                            $package->payable = 0; // Default or other statuses
                        }
                    } else {
                        $package->payable = 0; // Not active loan
                    }
                    return $package;
                });
        
                // Check if the user is eligible
                $isEligible = LoanInvestment::where('user_id', $userId)
                                             ->where('status', 1)
                                             ->first();
                
                // Determine eligibility status
                $status = $isEligible ? true : false;
                
                // Prepare the response
                $response = [
                    'success' => true,
                    'data' => $packages,
                    'isEligible' => $status,
                ];

        // Return the response (assuming you return as JSON)
        return response()->json($response);
            
    }
    
    
    public function paidLoan(Request $request){
        
        if($request->user()->kyc_status == '0'){
            $res = [
                'success' => false,
                'message' => "Please complete your kyc!"
            ];
            return response()->json($res, 200);


        } 
        
        $validator = Validator::make($request->all(),[
            'loan_id' => ['required', 'numeric', 'max:13' , 'exists:loan_lists,id'],  
        ]);
        
        
        
        if ($validator->fails()) {
            $res = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($res, 200);

        }
        
        
        
        
        $userId = $request->user()->id;
        $loanId = $request->loan_id;
        $isExists = Loan::where('user_id',$userId)->where('loan_id',$loanId)->where('loan_status','Payable')->first();
        if($isExists){
           $wallet =  $request->user()->wallet;
           $packages = LoanList::where('id',$isExists->loan_id)->where('status', 1)->first();
           if($wallet->main_wallet >= $packages->pain_amnt){ 
               $isExists->loan_status = 'Paid';
               $isExists->save();
               Wallet::where('user_id',$userId)->decrement('main_wallet',$packages->pain_amnt);
                  $response = [
                    'success' => true,
                    'data' => '',
                    'message' => "Loan amount paid successfully."
                ]; 
               
           }else{
               $response = [
                    'success' => false,
                    'data' => '',
                    'message' => "Insufficient amount in your E wallet!"
                ]; 
           }
           
            
        }else{
             $response = [
                    'success' => false,
                    'data' => '',
                    'message' => "Loan Not exists!"
                ]; 
        }
        
        return response()->json($response, 200);
        
    }

    
    
    
    public function buyPackage(Request $request){
       // if($request->user()->active_status->name == 'Active' || $request->user()->mobile == $request->mobile){

       if($request->user()->kyc_status == '0'){
        $res = [
            'success' => false,
            'message' => "Please complete your kyc!"
        ];
        return response()->json($res, 200);


    }
       if($request->user()->withdrawal_status == '1'){
            $validator = Validator::make($request->all(),[
                'mobile' => ['required', 'string', 'max:255' , 'exists:users,mobile'],
                'package' => ['required', 'integer', new ValidateLoanPackage($request)],   
            ]);
        // }else{
        //     $res = [
        //         'success' => false,
        //         'message' => "Please Active Yourself First!"
        //     ];
        //     return response()->json($res, 200);
        // }
        

        if ($validator->fails()) {
            $res = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($res, 200);

        }
        
        $resp=$this->make_topup($request);

    }else{ 
        $resp = [
           'success' => false,
           'data' => '',
           'message' => "Payout service down! Please try again after some time."
       ]; 
   }
         
        return response()->json($resp, 200);
          
        
        
    }
    
    public function make_topup(Request $request){
        $wallet_type='fund_wallet';
         $usera = User::where('mobile',$request->mobile)->first();
            $mobile = $request->mobile;
            $pkgDetails = LoanPackage::where('id',$request->package)->first();
            if($pkgDetails->type=='fix'){
                $amnt = $pkgDetails->amount;
            }else{
                $amnt = $request->amount;
            }
            DB::beginTransaction();
            try {
        
                $invest = LoanInvestment::create([
    
                    'user_id' => $usera->id,
                    'package_id' => $request->package,
                    'amount' => $amnt,
                    'status'  => 1
                ]);
                
                // $usera->active_status = 1;
                // $usera->active_date = now();
                // $usera->save();
    
                
                // $team = Team::where('user_id',$usera->id)->first();
                // $team->active_status = 1;
                // $team->save();
    
                $transaction = Transaction::create([
                    'user_id' => Auth::user()->id,
                    'tx_user' => $usera->id,
                    'amount' => $amnt,
                    'type' => 'debit',
                    'tx_type' => 'loan_topup',
                    'status'  => 1,
                    'wallet'  => $wallet_type,
                    'tx_id'  => $invest->id, 
                    'remark'  => 'Loan package of  '.$mobile.'activeted',
                ]);
    
                 
                  Distribute::DirectLoanIncome($transaction);
                  Distribute::LoanBoosterIncome($transaction);
                
                Wallet::where('user_id',Auth::user()->id)->decrement('main_wallet',$amnt);
                // Wallet::where('user_id',$usera->id)->increment('bouns_wallet',$amnt);
                DB::commit();
                $user = $usera;
                $name = $user->name;
                $mobnile = $user->mobile;
                $decoded_msg = urlencode($name);
                $msg = "Congratulations%20Dear%20$decoded_msg%20($mobnile)Your%20successfully%20Completed%20Activation%20Loan%20pakage%20Rs%201200%20Thank%20S2PAY";
                $user->loan_pkg = $amnt;
                $user->save();
                // ActivationMail::dispatch($user);
                $this->sendActiveLoanMsg($msg,$mobnile);
                $response = [
                    'success' => true,
                    'data' => $amnt,
                    'active_datetime' => Carbon::parse($invest->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
                    'message' => "Loan Package Active successfull."
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
            return $response;
    }
    
    public function sendActiveLoanMsg($msg,$mobnile){
     
       
            $apiKey = "71a9b0fbe3cb414583372e7c5664a5b4";
             
              
            $ch = curl_init();
            
            // Set the URL and other options
            curl_setopt($ch, CURLOPT_URL, "http://whatsapp.click4bulksms.in/wapp/api/send?apikey=$apiKey&mobile=$mobnile&msg=$msg");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            
            // Execute the request
            $response = curl_exec($ch);
            
            // Check for errors
            if (curl_errno($ch)) {
                 
                 $response = [
                        'success' => false,
                        'data' => curl_error($ch),
                        'message' => "msg not send!."
                    ];
            } else {
                // Print the response
                $result =  json_decode($response,true);
               if($result['status'] =='success'){
                   $response = [
                        'success' => true,
                        'data' => '',
                        'message' => "Msg send successfully."
                    ];
               }else{
                   $response = [
                        'success' => false,
                        'data' =>  '',
                        'message' => $result['errormsg']
                    ];
               }
                 
            }
            
            // Close the cURL session
            curl_close($ch);
            
            return $response;
      
  }
  
    public function approveLoan(Request $request){
        if($request->user()->kyc_status == '0'){
            $res = [
                'success' => false,
                'message' => "Please complete your kyc!"
            ];
            return response()->json($res, 200);


        }
            $validator = Validator::make($request->all(), [
                'amount' => ['required', 'numeric'], // Assuming hotel_id is an integer
                'username' => ['required', 'string', 'max:255' , 'exists:users,username'],
                'package' => ['required', 'integer', new ValidateLoan($request)], 
            ]);
 
            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->errors()
                ];
                return response()->json($response, 200);

            }
            
            $userId = $request->user()->id;
            $amount = $request->amount;
            $packageId = $request->package;
             $Loanexists = LoanList::where('id',$packageId)->where('status',1)->first();
            if($Loanexists){
                $requiredDirects = $Loanexists->direct_required;
                $mydirs = User::find($userId)->directs->count();
                $totalLaons =  Loan::where('loan_id',$packageId)->where('status',1)->count();
                if($totalLaons < 1){
                    if($mydirs>=$requiredDirects){ 
                        $invest = Loan::create([ 
                            'user_id' => $usera->id,
                            'loan_id' => $packageId,
                            'amount' => $amnt,
                            'charges' => 0,
                            'wallet' => 'fund_wallet', 
                            'remark' => $amnt.'Loan request generated', 
                            'loan_status' => 'pending', 
                            'status'  => 1
                        ]);
                        
                        Wallet::where('user_id',Auth::user()->id)->increment('main_wallet',$amnt);
                        $response = [
                            'success' => true,
                            'message' => "Laon Approve successfully."
                        ];
                            
                    
                    }else{
                        $response = [
                            'success' => false,
                            'message' => "Please Complete Your Refferal condition."
                        ];
                    }
                }
               
                
            }else{
                $response = [
                    'success' => false,
                    'message' => "This loan Amount not approved!"
                ];
            }
              
                            
            return response()->json($response, 200);
     
        
        
        
    }
     
}
