<?php

namespace App\Http\Controllers;

use App\Helper\RoiDistribute;
use App\Mail\SignUp;
use Illuminate\Http\Request;
use App\Models\User;
use Validator; 
use App\Models\EbikeInvestment;
use App\Models\LoanInvestment;
use App\Models\Investment;
use App\Models\RechargeInvestment;
use App\Models\Income;
use App\Models\DailyIncome;
use App\Models\Loan;
use App\Models\Withdrawal;
use App\Models\EbikeAchiver;
use App\Models\Binary;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\Gallery;
use App\Models\Banner; 
use App\Models\Affiliate;
use App\Models\GoldInvestment;
use App\Models\WalletType;
use App\Models\Headline;
use App\Models\TourInvestment;
use App\Models\UserOttCredential;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Carbon;
class DashboardController extends Controller
{
    public function index(Request $request)
    {   
        $user = $request->user();
        
        // RoiDistribute::roiClosing();
        // die();
        // Mail::to('kamaljitone@gmail.com')->send(new SignUp($user));
        
    
        $userId=$request->user()->id;
        $dir = Auth::user();
        $setting=DB::table('plan_settings')->where('status',1)->pluck('value','type');
        $usertheme  = Setting::getSetting('utheme','utheme4');
        session()->put('utheme',$usertheme);
        $Homedashborad = 'layouts.'.$usertheme.'.dashboard';

        $wallets = WalletType::where('type','wallet')->get();
         
        //$tr = $dir->transactions->take(5);
        //$transactions = ->get();
        $headlines = Headline::where('status',true)->orderBy('id',"DESC")->limit(5)->get();
        
        $gen_arr=$request->user()->team->gen;
        $gen['total']=!empty($gen_arr) ? count($gen_arr):0;
        //$gen['active'] = User::whereIn('id',$gen_arr)->where('active_status',1)->count();
        $gen['active'] = !empty($gen_arr) ? User::whereIn('id',$gen_arr)->where('active_status',1)->count() : 0 ;
        
        $gen['inactive'] = $gen['total']-$gen['active'];
        
        
        
        return view($Homedashborad, [
            'user' => $dir,
            'settings' => $setting,
            'wallets' => $wallets,
            
            'headlines' => $headlines,
            'gen' => $gen,
        ]);
        // return view('dashboard', [
        //     'user' => $dir,
        //     'settings' => $setting,
        // ]);
    }
    
    public function checkPin(Request $request){
        
        $validator = Validator::make($request->all(),[
            'transaction_pin' => ['required', 'numeric','min:4'],
        ]);
        
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 200);

        }
        $transPin = $request->transaction_pin;
        
        $exists = User::where('id',$request->user()->id)->where('transaction_pin',$transPin)->first();
        if($exists){
            $response = [
                'success' => true, 
                'message' => "Pin check Successfully."
            ]; 
        }else{
            $response = [
                'success' => false, 
                'message' => "Invalid Pin!"
            ]; 
        }
        return response()->json($response,200);
    }
    
    
    
    
    public function updatePin(Request $request){
       
        
        $validator = Validator::make($request->all(),[
           'current_pin' => ['required','min:4','numeric','exists:users,transaction_pin'],
           'new_pin' => ['required','min:4','numeric'],
        ]);
 
         if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 200);

        }
       
        $user = User::where('id',$request->user()->id)->where('transaction_pin',$request->current_pin)->first();
            
        if($user){
            $data  = $request->user()->update([
                'transaction_pin' =>  $request->new_pin,
            ]); 

            $response = [
                'success' => true, 
                'message' => "Pin Update Successfully."
            ]; 

        }else{

            $response = [
                'success' => false, 
                'message' => "current Pin not  match!"
            ]; 
            
        } 
        
        return response()->json($response, 200);
        
    }
    
    
    
    ////////////// API //////////
    public function dashboard(Request $request)
    {   
        
        
        // RoiDistribute::roiClosing();
        // die();
      
        $headlines = Headline::where('status',true)->orderBy('id',"DESC")->limit(5)->get();
       
        $userId=$request->user()->id;
         
        $levelSum = Income::where('user_id', $userId)->sum('level');
    
   
        $DailylevelSum = DailyIncome::where('user_id', $userId)->value('level');

       
       
      //  $total_direct =User::find($userId)->directs();
        $total_direct  = User::find($userId)->directs()->count() > 0 ? User::find($userId)->directs()->count() : 0;
     
        $active_direct=User::find($userId)->directs()->where('active_status',1)->count() > 0 ? User::find($userId)->directs()->where('active_status',1)->count() : 0; 
          
        $gen = $request->user()->team->gen;
        
    if($gen){
         $active_teams = User::whereIn('id', $gen)
        ->select(['*', \DB::raw('NULL as transaction_pin')])->where('active_status',1)->count() > 0 ?User::whereIn('id', $gen)
        ->select(['*', \DB::raw('NULL as transaction_pin')])->where('active_status',1)->count() : 0;
  
        $total_teams = User::whereIn('id', $gen)
        ->select(['*', \DB::raw('NULL as transaction_pin')])->count() > 0 ? User::whereIn('id', $gen)
        ->select(['*', \DB::raw('NULL as transaction_pin')])->count() :0;
    }else{
        $active_teams  = [];
        $total_teams  = [];
    }
       
   
  
    
        $result = []; 
        $exists = Banner::where('type','dashboard')->get(); 
            if($exists){
                foreach($exists as $exist){
                    $links = [];
              
                    $links['image'] = "https://easydigipays.com/storage/".$exist->image;  
                    $links['status'] = $exist->status;
                    $links['type'] = $exist->type;
                    $links['title'] = $exist->title; 
                    $result[] = $links;     
                }   
            } 
              
        $data=array();
        
         $incomes = [
            ['name' => 'Reward', 'slug' => 'reward'],
            ['name' => 'Level', 'slug' => 'level'],
            ['name' => 'Direct', 'slug' => 'direct'], 
            ['name' => 'Self Recharge', 'slug' => 'self_recharge'], 
            ['name' => 'Level Recharge', 'slug' => 'level_recharge'], 
        ];
       

      
        $data['wallets']=$request->user()->wallet;
        $data['headlines']=$headlines;
      
            $totalIncome = Transaction::where('user_id',$userId)->where('status',1)->where('tx_type','income')->sum('amount');
            $todayIncome = Transaction::where('user_id',$userId)->where('status',1)->where('tx_type','income')->whereDate('created_at', Carbon::today())->sum('amount');
       
            $data['active_directs']=$active_direct;
            $data['total_directs']=$total_direct;
            $data['total_teams']=$total_teams;
            $data['active_teams']=$active_teams;
         
            // $data['use_wallet']=array(['slug' => 'fund_wallet', 'name' => 'Fund Wallet'],['slug' => 'main_wallet', 'name' => 'Main Wallet'],['slug' => 'recharge_wallet', 'name' => 'Recharge Wallet']);
        $data['use_wallet'] = \App\Models\WalletType::query()
            ->where('status', 1)
            ->select('slug', 'name')
            ->get()
            ->toArray();
        $data['level_income']=str_pad($levelSum, 4, '0', STR_PAD_LEFT);

        $data['daily_level_income']=str_pad($DailylevelSum, 4, '0', STR_PAD_LEFT);
         $data['banner']=$result;
         
         $data['totalincm']=$totalIncome;
         $data['incomes']=$incomes;
         $data['todayincm']=$todayIncome;
    
        $response = [
            'success' => true,
            'data' => $data
        ];
         
        return response()->json($response, 200);
        
    }
    
    public function getActivePackages(Request $request){
        
        $userId = $request->user()->id;
        $investments = Investment::where('user_id', $userId)
        ->selectRaw('*, CASE WHEN received_amnt >= 2 * amount THEN 0 ELSE status END as pkg_status')
        ->where('status', 1)
        ->latest()
        ->get();
        $investmentAmnt  = Investment::where('user_id',$userId)->where('status',1)->sum('amount');
        $LoanInvestment  = LoanInvestment::where('user_id',$userId)->where('status',1)->latest()->get();
        $EbikeInvestment  = EbikeInvestment::where('user_id',$userId)->where('status',1)->latest()->get();
        $RechargeInvestment  = RechargeInvestment::where('user_id',$userId)->where('status',1)->latest()->get();
        $GoldInvestment  = GoldInvestment::where('user_id',$userId)->where('status',1)->latest()->get();
        $TourInvestment  = TourInvestment::where('user_id',$userId)->where('status',1)->latest()->get();
         
         $response = [
            'success' => true,
            'EbikeInvestment' => $EbikeInvestment,
            'LoanInvestment' => $LoanInvestment,
            'investment' => $investments,
            'ttl_investment' => $investmentAmnt,
            'RechargeInvestment' => $RechargeInvestment,
            'GoldInvestment' => $GoldInvestment,
            'TourInvestment' => $TourInvestment,
        ];
        return response()->json($response, 200);
        
    }
    
                    public function test(){ 
                         $mobile  ="7357378249";
                        $otp = "125634";
                        
                         
                        $msg = "Otp For verify User Mobile";
                        $curl = curl_init();
                        
                        curl_setopt_array($curl, array(
                          CURLOPT_URL => 'https://smsmaa.com/SMS_API/sendsms.php?username=welcomejoykrl&password=KRL999&mobile='.$mobile.'&sendername=WELJOY&message=New%20Registration%20OTP%20'.$otp.'%20WELCOME%20JOY%20TOURS%20AND%20TRAVELS%20OPC%20PVT.LTD%0A&routetype=1',
                          CURLOPT_RETURNTRANSFER => true,
                          CURLOPT_ENCODING => '',
                          CURLOPT_MAXREDIRS => 10,
                          CURLOPT_TIMEOUT => 0,
                          CURLOPT_FOLLOWLOCATION => true,
                          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                          CURLOPT_CUSTOMREQUEST => 'GET',
                        ));
                        
                        $response = curl_exec($curl);
    
                         curl_close($curl);

 
                       
                            if ($response) {
                                $responseArray = explode(";", $response);
                                $status = "";
                                $remark = "";
                                $guid = ""; // Initialize GUID variable
                                
                                // Iterate through each element of the response array
                                foreach ($responseArray as $element) {
                                    // Split each element by colon to separate key and value
                                    $pair = explode(":", $element, 2); // Limit the split to 2 parts to handle values containing colons
                            
                                    // Extract key and value, trim whitespace
                                    $key = trim($pair[0]);
                                    $value = trim($pair[1]);
                            
                                    // Check if the key is "Status"
                                    if ($key === "Status") {
                                        $status = $value;
                                    }
                            
                                    // Check if the key is "Remark"
                                    if ($key === "Remark") {
                                        $remark = $value;
                                    }
                            
                                    // Check if the key is "GUID"
                                    if ($key === "GUID") {
                                        $guid = $value;
                                    }
                                }
    
                           
                            if($status == 1){
                                
                                $otps =  array();
                                $otps['mobile'] = $mobile;
                                $otps['status'] = 0; 
                                $otps['time'] =  date('Y-m-d H:i:s');
                                $otps['code'] =  $otp;
                                $this->db->insert('otp',$otps);
                                $res['status'] = true;
                                $res['msg'] = 'Otp send Successfully.';
                            }else{
                                $res['status'] = false;
                                $res['msg'] = $remark;
                            }
                    }else{
                         $res['status'] = false;
                         $res['msg'] = 'Something Went wrong!';
                    }
                    
                }         
     
             
      
}
