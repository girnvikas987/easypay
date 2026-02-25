<?php

namespace App\Http\Controllers;


use App\Helper\Distribute;
use App\Models\Circle;
use App\Models\DailyIncome;
use App\Models\Income;
use App\Models\Operator;
use App\Models\PlanRechargeReferralIncome;
use App\Models\Slab;
use App\Models\Recharge;
use App\Models\Team;
use App\Models\PlanRechargeRoyalty;
use App\Models\PlanTrip;
use App\Models\Provider;
use App\Models\Test;
use App\Models\Transaction;
use App\Models\User;
use App\Models\RechargeInvestment;
use App\Models\RechargeTripAchiver;
use App\Rules\validateRechargePackage;
use App\Models\RechargePackage;
use App\Models\Setting;
use App\Models\Support;
use Illuminate\Validation\Rule;
use App\Models\Wallet;
use App\Models\WalletType;
use App\Rules\Checkbalance;
use App\Rules\CheckRechargeBalance;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stringable;
use Validator;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Support\Facades\Http;
class RechargeController extends Controller
{

    public function fetchAndSaveOperators(Request $request)
    {
        $type = $request->type;
        $operators = Operator::where('service_type',$type)->get();
        $circles = Circle::all();


            $response = [
                'success' => true,
                'operators' => $operators,
                'circles' => $circles,
            ];
            return response()->json($response, 200);


    }




 public function getOperatorData(Request $request){

      $validator = Validator::make($request->all(),[
            'mobile' => ['required', 'numeric'],
        ]);


        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 200);

        }
               $mobile = $request->mobile;


     $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://www.kwikapi.com/api/v2/operator_fetch_v2.php',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => array('api_key' => env('KWIKAPI_KEY'),'number' => $mobile),
      CURLOPT_HTTPHEADER => array(
        'Cookie: PHPSESSID=5aee25cc19bc9808829f233baeb5a16c'
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    $result = json_decode($response,true);

    $re_status =Str::lower($result['success']);
        // $re_status = 'success';
        ///////////////////////////////////////////////// Recharge Api End //////////////////////////////////////////////////////////
        if($re_status == true){

            $_msg = "Recharge successfully.";
            $responses = [
                'success' => true,
                'data' => $result['details'],
                'message' => $_msg
            ];

        }else{
            $responses = [
                'success' => false,
                'data' => '',
                'message' => "Utilities service down! Please try again after some time."
            ];
        }

        return response()->json($responses);

 }

 public function fetch_bill(Request $request){
        $validator = Validator::make($request->all(),[
            'operator' => ['required', 'string','max:255'],
            'number' => ['required', 'string','max:255'],
            'mobile' => ['required', 'string','max:255']
        ]);
         if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 200);

        }

               $opid = $request->operator;
               $number = $request->number;
               $mobile = $request->mobile;
               $amount = $request->amount;

               $timestamp = now()->format('His'); // Current timestamp
        //$randomString = Str::random(2); // Random string (adjust the length as needed)

        $transactionId = $timestamp;
            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://www.kwikapi.com/api/v2/bills/validation.php?api_key=' . env('KWIKAPI_KEY') . '&number='.$number.'&amount='.$amount.'&opid='.$opid.'&order_id='.$transactionId.'&opt1=opt1&opt2=opt2&opt3=opt3&opt4=opt4&opt5=opt5&opt6=opt6&opt7=opt7&opt8=Bills&opt9=opt9&opt10=opt10&mobile='.$mobile,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
              CURLOPT_HTTPHEADER => array(
                'Cookie: PHPSESSID=1f08de1532fc724c8eb013bab0503536'
              ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);


          $result = json_decode($response,true);

            $re_status =Str::lower($result['status']);
            // $re_status = 'success';

            ///////////////////////////////////////////////// Recharge Api End //////////////////////////////////////////////////////////
            if($re_status == 'success'){

                $_msg = "fetch successfully.";
                $responses = [
                    'success' => true,
                    'data' => $result,
                    'message' => $_msg
                ];

            }else{
                $responses = [
                    'success' => false,
                    'data' => '',
                    'message' => $result['message']
                ];
            }

            return response()->json($responses);





 }

 public function viewPlan(Request $request){

      $validator = Validator::make($request->all(),[
            'operator' => ['required', 'string','max:255'],
            'circle' => ['required', 'string','max:255']
        ]);



        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 200);

        }
               $state_code = $request->circle;
               $opid = $request->operator;

              $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://www.kwikapi.com/api/v2/recharge_plans.php',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array('api_key' => env('KWIKAPI_KEY'),'state_code' => $state_code,'opid' => $opid),
              CURLOPT_HTTPHEADER => array(

              ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $result = json_decode($response,true);

            $re_status =Str::lower($result['success']);
                // $re_status = 'success';

                ///////////////////////////////////////////////// Recharge Api End //////////////////////////////////////////////////////////
                if($re_status == true){

                    $_msg = "Recharge successfully.";
                    $responses = [
                        'success' => true,
                        'data' => $result['plans'],
                        'message' => $_msg
                    ];

                }else{
                    $responses = [
                        'success' => false,
                        'data' => '',
                        'message' => $result['message']
                    ];
                }

                return response()->json($responses);

 }

  public function rechargeRequest(Request $request){


        $wallet = $request->wallet_type;

    $validator = Validator::make($request->all(),[
            'mobile' => ['required', 'numeric'],
            'amount' => ['required', 'numeric','min:10',new CheckRechargeBalance($wallet)],
            'operator' => ['required', 'string','max:255'],
            'circle' => ['required', 'string','max:255'],
            'recharge_type' => ['required', 'string','max:255']
        ]);


        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 200);

        }

        $mobile = $request->mobile;
        $amount = $request->amount;
        $operator = $request->operator;
        $circle = $request->circle;
        $recharge_type = $request->recharge_type;
        $timestamp = now()->format('His'); // Current timestamp
        //$randomString = Str::random(2); // Random string (adjust the length as needed)

        $transactionId = $timestamp;


        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://www.kwikapi.com/api/v2/recharge.php?api_key=' . env('KWIKAPI_KEY') . '&number='.$mobile.'&amount='.$amount.'&opid='.$operator.'&state_code='.$circle.'&order_id='.$transactionId,
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
        $result = json_decode($response,true);

        $re_status =Str::lower($result['status']);
        // $re_status = 'success';
        ///////////////////////////////////////////////// Recharge Api End //////////////////////////////////////////////////////////
        if($re_status == 'failure' || $re_status == 'failed'){
            $recharge_status = $re_status;

            $recharge_msg = $result['message'];
            if($recharge_msg != " "){
                $_msg = $recharge_msg;
            }else{
                $_msg = "Recharge Failed!";
            }
            $responses = [
                'success' => false,
                'data' => $result,
                'recharge_status' => $recharge_status,
                'message' => $_msg
            ];

        }else{

                $api_tansasction_id = $result['order_id'];
                $recharge_status = $re_status;
                $status = 0;
                $userId = Auth::user()->id;
                $username = Auth::user()->username;

                DB::beginTransaction();
                try {

                    $transaction = Recharge::create([
                            'user_id' => $userId,
                            'api_tansasction_id' => $api_tansasction_id,
                            'mobile' => $mobile,
                            'amount' => $amount,
                            'tx_id'=> $transactionId,
                            'api_status' => $recharge_status,
                            'wallet_type' => $wallet,
                            'recharge_type' => $recharge_type,
                            'remark' => "Recharge successful for $mobile by $username.",
                            'status'  => $status
                    ]);

                    $wlts=Wallet::where('user_id',$userId)->first();

                    $detectwallet = $wlts->$wallet;

                    /////////////////////////////////////////////////////////////////////////////////////////// both wallet ///////////////////////////////////////////////

                    Wallet::where('user_id',Auth::user()->id)->decrement($wallet,$amount);

                    $wlt = $request->user()->wallet;
                    $closeAmnt = $wlt->$wallet;
                    $transactionse = Transaction::create([
                        'user_id' => $userId,
                        'tx_user' => $userId,
                        'amount' => $amount,
                        'charges' => 0,
                        'type' => 'debit',
                        'tx_type' => 'recharge',
                        'status'  => $status,
                        'wallet'  => $wallet,
                        'close_amount'  => $closeAmnt,
                        'tx_id'  => $transactionId,
                        'remark'  => 'Recharge  of  '.$amount.' amount',

                    ]);




                    /////////////////////////////////////////////////////////////////////////////////////////// both wallet ///////////////////////////////////////////////

                    $usera = User::where('id',$userId)->first();

                    $active_user = Auth::user()->active_status;
                     $cashbackAmnt = 0;



                    if($recharge_status == 'success'){

                                 $package  = $usera->package;
                            $name  = $usera->name;
                            $mobi  = $usera->mobile;


                        /////////////////////////////////////////first distrbute self income //////////////////////////////////////

                        if($package != null){

                             if($package =='prime'){
                                $self_incomes = PlanRechargeReferralIncome::where('status',1)->where('source','self_recharge')->where('package',1)->first();

                            }else{
                                $self_incomes = PlanRechargeReferralIncome::where('status',1)->where('source','self_recharge')->where('package',2)->first();
                            }
                            if($self_incomes){

                                    $comm = $amount * $self_incomes->value/100;
                                    $wallet = 'main_wallet';

                                    Transaction::create([
                                        'user_id' => $userId,
                                        'tx_user' => $userId,
                                        'type' => 'credit',
                                        'tx_type' => 'income',
                                        'wallet' => 'main_wallet',
                                        'income' => 'self_recharge',
                                        'status' => 1,
                                        'amount' => $comm,
                                        'remark' => "Received Self of amount Rs $comm from Recharge of $mobile.",
                                    ]);
                                    $cashbackAmnt = $comm;
                                    Wallet::where('user_id',$userId)->increment($wallet,$comm);
                                    Income::where('user_id',$userId)->increment($self_incomes->source,$comm);
                                    DailyIncome::where('user_id',$userId)->increment($self_incomes->source,$comm);

                            }






                        /////////////////////////////////////////first distrbute self income //////////////////////////////////////

                            // Distribute::DirectIncome($transaction);
                            Distribute::RechargeReferralIncome($transaction);


                        }

                       $transaction->status = 1;
                       $transaction->save();
                       $transactionse->status = 1;
                       $transactionse->save();

                            $_msg = "Recharge successfully.";
                            $responses = [
                                'success' => true,
                                'data' => $cashbackAmnt,
                                'recharge_status' => $recharge_status,
                                'message' => $_msg
                            ];
                    }else{

                        $_msg =  'Recharge Pending.';
                        $responses = [
                            'success' => true,
                            'data' => '',
                            'recharge_status' => $recharge_status,
                            'message' => $_msg
                        ];
                    }
                    DB::commit();


                } catch (\Exception $e) {
                    //throw $th;
                    DB::rollBack();
                    $responses = [
                        'success' => false,
                        'data' =>  $e,
                        'recharge_status' => $recharge_status,
                        'message' => "Something wrong!."
                    ];
                }
        }

     return response()->json($responses, 200);



  }

 /////////////////////////////hotel APi ///////////////////////////////////////////////////////////////////////////////////////////////////////
 public function callback_recharge(Request $request){

                       $status = $request->query('status');
                       $transId = $request->query('payid');

                       $re_status = Str::lower($status);



                        $exists = Recharge::where('tx_id',$transId)->first();
                        if($exists){

                                if(($re_status == 'failure' || $re_status == 'failed') && $exists->api_status != 'success'){
                                        $exists->api_status = $re_status;
                                        $amount = $exists->amount;
                                        $userId = $exists->user_id;
                                        $wallet = $exists->wallet;
                                        $active_status = User::where('id',$userId)->value('active_status');
                                        Wallet::where('user_id',$userId)->increment($wallet,$amount);
                                        $exists->status = 0;
                                        $exists->save();
                                }else{



                                    if($exists->api_status != 'success' && $re_status =='success'){
                                                $usera = User::where('id',$exists->user_id)->first();
                                                $package  = $usera->package;
                                                $name  = $usera->name;
                                                $mobi  = $usera->mobile;


                        /////////////////////////////////////////first distrbute self income //////////////////////////////////////

                                     if($package != null){

                                                if($package =='prime'){
                                                    $self_incomes = PlanRechargeReferralIncome::where('status',1)->where('source','self_recharge')->where('package',1)->first();

                                                }else{
                                                    $self_incomes = PlanRechargeReferralIncome::where('status',1)->where('source','self_recharge')->where('package',2)->first();
                                                }
                                                if($self_incomes){
                                                        $amount = $exists->amount;
                                                        $comm = $amount * $self_incomes->value/100;
                                                        $wallet = 'main_wallet';
                                                       $userId = $exists->user_id;
                                                       $transaction =  Transaction::create([
                                                            'user_id' => $userId,
                                                            'tx_user' => $userId,
                                                            'type' => 'credit',
                                                            'tx_type' => 'income',
                                                            'wallet' => 'main_wallet',
                                                            'income' => 'self_recharge',
                                                            'status' => 1,
                                                            'amount' => $comm,
                                                            'remark' => "Received Self of amount Rs $comm from Recharge of $mobi.",
                                                        ]);
                                                        Wallet::where('user_id',$userId)->increment($wallet,$comm);
                                                        Income::where('user_id',$userId)->increment($self_incomes->source,$comm);
                                                        DailyIncome::where('user_id',$userId)->increment($self_incomes->source,$comm);

                                                }






                                            /////////////////////////////////////////first distrbute self income //////////////////////////////////////

                                                // Distribute::DirectIncome($transaction);
                                                Distribute::RechargeReferralIncome($transaction);

                                            }

                                        $exists->api_status = $re_status;
                                        $exists->status = 1;
                                        $exists->save();

                                    }else{
                                        $exists->api_status = $re_status;
                                        $exists->status = 0;
                                        $exists->save();
                                    }


                               }

                        //     $test = Test::create([
                        //         'remark' => $re_status,
                        //         'updated_at' => now(),
                        //         'created_at' => now(),
                        //   ]);
                    }

            }




 public function rechargeHistory(Request $request){
        $user = $request->user();

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        // Use Carbon to parse the dates if provided
        $fromDate = $fromDate ? Carbon::parse($fromDate)->startOfDay()->timezone('Asia/Kolkata') : null;
        $toDate = $toDate ? Carbon::parse($toDate)->endOfDay()->timezone('Asia/Kolkata') : null;

        // Query the recharges table
        $query = $user->recharges();

        // Apply date filters if provided
        if ($fromDate) {
            $query->where('created_at', '>=', $fromDate);
        }

        if ($toDate) {
            $query->where('created_at', '<=', $toDate);
        }
         $query->orderBy('created_at', 'desc');
        // Paginate the results
        $perPage = 20;
        $recharges = $query->paginate($perPage);

        if ($recharges->count() > 0) {
            $response = [
                'success' => true,
                'data' => $recharges->items(),
                'pagination' => [
                    'current_page' => $recharges->currentPage(),
                    'last_page' => $recharges->lastPage(),
                    'total_items' => $recharges->total(),
                ],
                'message' => 'Recharge History Fetch Successfully.',
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
                'message' => 'Recharge history not fetched!',
            ];
        }

        return response()->json($response, 200);
    }

    public function call_recharge(Request $request){
        return response()->json(['success' => false, 'message' => 'Missing required parameters.'], 200);
    }

    // ── Missing methods (stub implementations) ──────────────────────

    public function packages(){
        try {
            $wallets = \App\Models\WalletType::whereIn('id',['1','2','3','8'])->get();
            $lists = RechargePackage::where('status',1)->get();
            $response = [
                'success' => true,
                'data' => $lists,
                'wallets' => $wallets,
                'message' => 'Recharge Package fetch Successfully.',
            ];
        } catch (\Exception $e) {
            $response = ['success' => false, 'data' => [], 'message' => 'Recharge packages not available.'];
        }
        return response()->json($response, 200);
    }

    public function buyPackage(Request $request){
        $validator = Validator::make($request->all(),[
            'mobile' => ['required', 'string', 'max:255', 'exists:users,mobile'],
            'package' => ['required', 'integer'],
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 200);
        }

        $wallet_type = $request->wallet_type ?? 'main_wallet';
        $usera = User::where('mobile', $request->mobile)->first();
        if (!$usera) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 200);
        }

        $pkgDetails = RechargePackage::where('id', $request->package)->first();
        if (!$pkgDetails) {
            return response()->json(['success' => false, 'message' => 'Package not found.'], 200);
        }

        $amnt = $pkgDetails->type == 'fix' ? $pkgDetails->amount : ($request->amount ?? $pkgDetails->amount);

        DB::beginTransaction();
        try {
            $invest = RechargeInvestment::create([
                'user_id' => $usera->id,
                'package_id' => $request->package,
                'amount' => $amnt,
                'status' => 1,
            ]);

            \App\Models\Wallet::where('user_id', Auth::user()->id)->decrement($wallet_type, $amnt);

            $closed_amount = $request->user()->wallet->$wallet_type ?? 0;
            Transaction::create([
                'user_id' => Auth::user()->id,
                'tx_user' => $usera->id,
                'amount' => $amnt,
                'type' => 'debit',
                'tx_type' => 'recharge_topup',
                'status' => 1,
                'wallet' => $wallet_type,
                'tx_id' => $invest->id,
                'close_amount' => $closed_amount,
                'remark' => 'Recharge package of ' . $request->mobile . ' activated',
            ]);

            DB::commit();
            $response = ['success' => true, 'data' => $amnt, 'message' => 'Recharge Package Active successfully.'];
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['success' => false, 'message' => 'Something went wrong.'];
        }
        return response()->json($response, 200);
    }

    public function getRechargeRoyalty(Request $request){
        try {
            $royalties = PlanRechargeRoyalty::where('status', 1)->get();
            $response = ['success' => true, 'data' => $royalties, 'message' => 'Data fetch successfully.'];
        } catch (\Exception $e) {
            $response = ['success' => false, 'data' => [], 'message' => 'Royalty data not available.'];
        }
        return response()->json($response, 200);
    }

    public function getRechargeTour(Request $request){
        try {
            $userId = $request->user()->id;
            $trips = RechargeTripAchiver::where('user_id', $userId)->get();
            $planTrips = PlanTrip::where('status', 1)->get();
            $response = ['success' => true, 'data' => $trips, 'plans' => $planTrips, 'message' => 'Trip data fetch successfully.'];
        } catch (\Exception $e) {
            $response = ['success' => false, 'data' => [], 'message' => 'Trip data not available.'];
        }
        return response()->json($response, 200);
    }

    public function providers(Request $request){
        try {
            $validator = Validator::make($request->all(),[
                'provider_name' => ['required', 'string'],
                'service_type' => ['required', 'string'],
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()], 200);
            }

            $provider = Provider::updateOrCreate(
                ['provider_name' => $request->provider_name, 'service_type' => $request->service_type],
                $request->only(['provider_id', 'provider_name', 'service_id', 'service_name', 'service_type', 'help_line', 'status'])
            );
            $response = ['success' => true, 'data' => $provider, 'message' => 'Provider saved successfully.'];
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => 'Something went wrong.'];
        }
        return response()->json($response, 200);
    }

    public function getProviders(Request $request){
        try {
            $type = $request->service_type;
            $query = Provider::where('status', 1);
            if ($type) {
                $query->where('service_type', $type);
            }
            $providers = $query->get();
            $response = ['success' => true, 'data' => $providers, 'message' => 'Providers fetched successfully.'];
        } catch (\Exception $e) {
            $response = ['success' => false, 'data' => [], 'message' => 'Providers not available.'];
        }
        return response()->json($response, 200);
    }

    public function recharge_services(Request $request){
        try {
            $services = Provider::where('status', 1)->select('service_id', 'service_name', 'service_type')->distinct()->get();
            $response = ['success' => true, 'data' => $services, 'message' => 'Services fetched successfully.'];
        } catch (\Exception $e) {
            $response = ['success' => false, 'data' => [], 'message' => 'Services not available.'];
        }
        return response()->json($response, 200);
    }

    public function handleRechargecallback(Request $request){
        Log::info('Recharge callback received', $request->all());
        return response()->json(['success' => true, 'message' => 'Callback processed.'], 200);
    }

    public function validateProvider(Request $request){
        $validator = Validator::make($request->all(),[
            'provider_id' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 200);
        }
        try {
            $provider = Provider::where('provider_id', $request->provider_id)->where('status', 1)->first();
            if ($provider) {
                $response = ['success' => true, 'data' => $provider, 'message' => 'Provider validated successfully.'];
            } else {
                $response = ['success' => false, 'message' => 'Provider not found or inactive.'];
            }
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => 'Validation failed.'];
        }
        return response()->json($response, 200);
    }

    public function biilVerify(Request $request){
        $validator = Validator::make($request->all(),[
            'operator' => ['required', 'string'],
            'number' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 200);
        }
        try {
            $opid = $request->operator;
            $number = $request->number;
            $amount = $request->amount ?? 0;
            $mobile = $request->mobile ?? '';

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://www.kwikapi.com/api/v2/bills/validation.php?api_key=' . env('KWIKAPI_KEY') . '&number=' . $number . '&amount=' . $amount . '&opid=' . $opid . '&order_id=' . now()->format('His') . '&mobile=' . $mobile,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            $result = json_decode(curl_exec($curl), true);
            curl_close($curl);

            if ($result && isset($result['status']) && Str::lower($result['status']) == 'success') {
                $response = ['success' => true, 'data' => $result, 'message' => 'Bill verified successfully.'];
            } else {
                $response = ['success' => false, 'data' => $result ?? '', 'message' => $result['message'] ?? 'Bill verification failed.'];
            }
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => 'Bill verification service unavailable.'];
        }
        return response()->json($response, 200);
    }

    public function billPayment(Request $request){
        $validator = Validator::make($request->all(),[
            'operator' => ['required', 'string'],
            'number' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:1'],
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 200);
        }
        try {
            $opid = $request->operator;
            $number = $request->number;
            $amount = $request->amount;
            $mobile = $request->mobile ?? $number;
            $transactionId = now()->format('His');

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://www.kwikapi.com/api/v2/bills/payment.php?api_key=' . env('KWIKAPI_KEY') . '&number=' . $number . '&amount=' . $amount . '&opid=' . $opid . '&order_id=' . $transactionId . '&mobile=' . $mobile,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            $result = json_decode(curl_exec($curl), true);
            curl_close($curl);

            if ($result && isset($result['status']) && Str::lower($result['status']) == 'success') {
                $response = ['success' => true, 'data' => $result, 'message' => 'Bill payment successful.'];
            } else {
                $response = ['success' => false, 'data' => $result ?? '', 'message' => $result['message'] ?? 'Bill payment failed.'];
            }
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => 'Bill payment service unavailable.'];
        }
        return response()->json($response, 200);
    }

    public function rechargeReq(Request $request){
        $validator = Validator::make($request->all(),[
            'mobile' => ['required', 'numeric'],
            'amount' => ['required', 'numeric', 'min:10'],
            'operator' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 200);
        }
        try {
            $mobile = $request->mobile;
            $amount = $request->amount;
            $operator = $request->operator;
            $transactionId = now()->format('His');

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://www.kwikapi.com/api/v2/recharge.php?api_key=' . env('KWIKAPI_KEY') . '&number=' . $mobile . '&amount=' . $amount . '&opid=' . $operator . '&order_id=' . $transactionId,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            $result = json_decode(curl_exec($curl), true);
            curl_close($curl);

            if ($result && isset($result['status']) && Str::lower($result['status']) != 'failure') {
                $response = ['success' => true, 'data' => $result, 'message' => 'Recharge request submitted.'];
            } else {
                $response = ['success' => false, 'data' => $result ?? '', 'message' => $result['message'] ?? 'Recharge failed.'];
            }
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => 'Recharge service unavailable.'];
        }
        return response()->json($response, 200);
    }

    public function getOperator(Request $request){
        try {
            $type = $request->type ?? $request->service_type;
            $query = Operator::query();
            if ($type) {
                $query->where('service_type', $type);
            }
            $operators = $query->get();
            $response = ['success' => true, 'data' => $operators, 'message' => 'Operators fetched successfully.'];
        } catch (\Exception $e) {
            $response = ['success' => false, 'data' => [], 'message' => 'Operators not available.'];
        }
        return response()->json($response, 200);
    }

    public function fetchFill(Request $request){
        $validator = Validator::make($request->all(),[
            'operator' => ['required', 'string'],
            'number' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 200);
        }
        try {
            $opid = $request->operator;
            $number = $request->number;
            $amount = $request->amount ?? 0;
            $mobile = $request->mobile ?? '';

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://www.kwikapi.com/api/v2/bills/validation.php?api_key=' . env('KWIKAPI_KEY') . '&number=' . $number . '&amount=' . $amount . '&opid=' . $opid . '&order_id=' . now()->format('His') . '&mobile=' . $mobile,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            $result = json_decode(curl_exec($curl), true);
            curl_close($curl);

            if ($result && isset($result['status']) && Str::lower($result['status']) == 'success') {
                $response = ['success' => true, 'data' => $result, 'message' => 'Bill fetched successfully.'];
            } else {
                $response = ['success' => false, 'data' => $result ?? '', 'message' => $result['message'] ?? 'Bill fetch failed.'];
            }
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => 'Bill fetch service unavailable.'];
        }
        return response()->json($response, 200);
    }

    public function fetchDTHinfo(Request $request){
        $validator = Validator::make($request->all(),[
            'operator' => ['required', 'string'],
            'number' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 200);
        }
        try {
            $opid = $request->operator;
            $number = $request->number;

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://www.kwikapi.com/api/v2/dth_info.php?api_key=' . env('KWIKAPI_KEY') . '&number=' . $number . '&opid=' . $opid,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            $result = json_decode(curl_exec($curl), true);
            curl_close($curl);

            if ($result && isset($result['success']) && $result['success'] == true) {
                $response = ['success' => true, 'data' => $result, 'message' => 'DTH info fetched successfully.'];
            } else {
                $response = ['success' => false, 'data' => $result ?? '', 'message' => $result['message'] ?? 'DTH info fetch failed.'];
            }
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => 'DTH info service unavailable.'];
        }
        return response()->json($response, 200);
    }

    public function GetSourceList(Request $request){
        return response()->json(['success' => true, 'data' => [], 'message' => 'Bus booking service coming soon.'], 200);
    }

    public function GetDestinationList(Request $request){
        return response()->json(['success' => true, 'data' => [], 'message' => 'Bus booking service coming soon.'], 200);
    }

    public function hotelAvailability(Request $request){
        return response()->json(['success' => true, 'data' => [], 'message' => 'Hotel service coming soon.'], 200);
    }

    public function citySearch(Request $request){
        return response()->json(['success' => true, 'data' => [], 'message' => 'City search service coming soon.'], 200);
    }

}

