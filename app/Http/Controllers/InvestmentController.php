<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Investment;
use App\Models\Transaction;
use App\Models\PlanSetting;
use App\Models\CommiteePackage;
use App\Models\Autopool;
use App\Models\Wallet;
use App\Models\PlanRefferalIncome;
use App\Models\Income;
use App\Models\Package;
use App\Models\SavingPackage;
use Illuminate\Http\RedirectResponse;
use App\Rules\Checkbalance;
use Illuminate\Support\Facades\DB;
use App\Listeners\TopupListener;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Helper\Distribute;
use App\Helper\Pool;
use App\Models\OttCredential;
use App\Models\Team;
use App\Models\UserOttCredential;
use App\Rules\ValidateElitePackage;
use App\Rules\ValidateLoanPackage;
use App\Rules\ValidatePackage;
use Illuminate\Pagination\LengthAwarePaginator;
use Validator;
use Illuminate\Support\Str;
use App\Jobs\ActivationMail;
use App\Models\DailyIncome;
use App\Models\EbikeInvestment;
use App\Models\CommiteeInvestment;
use App\Models\SavingFundInvestment;
use App\Models\GoldInvestment;
use App\Models\TourInvestment;
use App\Models\WalletType;
use App\Models\Withdrawal;
use App\Models\EliteInvestment;
use App\Models\FlyInvestment;
use App\Models\Setting;
use Carbon\Carbon;
use PDF;
use Illuminate\Support\Facades\File;
class InvestmentController extends Controller
{

    public function topup(Request $request)
    {
         //dd($request);
        $userId=$request->user()->id;
        $dir = User::find($userId);

        return view('pages.topup.investment', [
            'user' => $dir,
            'packages'=>   Package::all(),
        ]);
    }
    public function investments(Request $request)
    {

        $userId=$request->user()->id;
        $query = User::find($userId)->investments();

        if($request->has('search') && !empty($request->input('search'))){

                 $query->where('amount','like','%'.$request->search.'%');

        }



        $a=$query->paginate(10)->withQueryString();

        return view('pages.investments', [
            'user' => $request->user(),
        ])->with('investments',$a);
    }

    public function store(Request $request): RedirectResponse
    {
        //        dd($request->all());


        $request->validate([
            'username' => ['required', 'string', 'max:255' , 'exists:users,mobile'],
            'package' => ['required', 'integer', new ValidatePackage($request)],
        ]);


        $resp=$this->make_topup($request);
        if($resp['success']==false){
            $request->session()->flash('error', 'Something wrong!');
        }else{
            $request->session()->flash('status', 'Investment successful!');
        }

        //Auth::login($user);

        return redirect('./investment');
    }

    public function packages(Request $request) {
        $wallets = WalletType::whereIn('id',['1','2','3','4','5','6'])->get();

        $response = [
            'success' => true,
            'packages'=>   Package::all(),
            'wallets'=>   $wallets,
        ];
        return response()->json($response, 200);
    }


    public function topup_api(Request $request) {




        $mobile = $request->mobile;
        $user = User::where('mobile',$mobile)->first();

            $validator = Validator::make($request->all(),[
                'mobile' => ['required', 'string', 'max:255' , 'exists:users,mobile'],
                'package' => ['required', 'integer', new ValidatePackage($request)],
            ]);



        if ($validator->fails()) {
            $res = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($res, 200);

        }

        $resp=$this->make_topup($request);


        return response()->json($resp, 200);

    }

    public function make_topup(Request $request){

        $wallet_type=$request->wallet_type;
         $usera = User::where('mobile',$request->mobile)->first();
            $mobile = $request->mobile;
            $pkgDetails = Package::where('id',$request->package)->first();
            if($pkgDetails->type=='fix'){
                $amnt = $pkgDetails->amount;
            }else{
                $amnt = $request->amount;
            }

            DB::beginTransaction();
            try {
                //code...

                $invest = Investment::create([
                    'user_id' => $usera->id,
                    'tx_user' => Auth::user()->id,
                    'package_id' => $request->package,
                    'amount' => $amnt,
                    'status'  => 1
                ]);

                $usera->active_status = 1;
                $usera->package = $pkgDetails->slug;
                $usera->active_date = now();
                $usera->save();


                // $invest->received_amnt += $invest->amount;
                // $invest->save();

                $team = Team::where('user_id',$usera->id)->first();
                $team->active_status = 1;
                $team->save();
                Wallet::where('user_id',Auth::user()->id)->decrement($wallet_type,$amnt);
                $closedAmnt = $request->user()->wallet->$wallet_type;


                $transaction = Transaction::create([
                    'user_id' => Auth::user()->id,
                    'tx_user' => $usera->id,
                    'amount' => $amnt,
                    'type' => 'debit',
                    'tx_type' => 'topup',
                    'status'  => 1,
                    'wallet'  => $wallet_type,
                    'close_amount'  => $closedAmnt,
                    'tx_id'  => $invest->id,
                    'remark'  => 'prime package of  '.$mobile.'activeted',

                ]);

                // $parentId = Pool::getParent(1,1,'1');

                // $parent = User::find($parentId);
                // if($parentId){

                //     $data = Autopool::create([
                //         'user_id'=>$usera->id,
                //         'parent_id' =>  $parentId,
                //         'pool' =>  1,
                //         'pool_num' =>  1,
                //     ]);


                // }


                // event(new TopupListener($transaction));
               // Distribute::DirectIncome($invest);
                if($request->package !=3){
                     Distribute::LevelIncome($invest);
                }

                //Distribute::AutoPoolIncome($data);
                //  Distribute::InvestLevelIncome($transaction);
                 //Distribute::InvestdierctIncome($transaction);
               //  Distribute::distrbutePrimeBinaryIncome($transaction);


                DB::commit();
                $user = $usera;
                $namne =$user->name;
                $mobi = $user->mobile;
                $decoded_msg = urlencode($namne);
                $msg = "Congratulations%20Dear%20$decoded_msg%20($mobi)Your%20successfully%20Completed%20Activation%20prime%20pakage%20Rs%20$amnt%20Thank%20S2PAY";
                // $this->sendActiveMsg($msg,$mobi);
              //  ActivationMail::dispatch($user);
                $response = [
                    'success' => true,
                    'data' => $amnt,
                    'active_datetime' => Carbon::parse($invest->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
                    'message' => "Subscription successfull."
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

    public function commitee_packages(Request $request) {
        $wallets = WalletType::whereIn('id',['1','2','3'])->get();

        $response = [
            'success' => true,
            'packages'=>   CommiteePackage::all(),
            'wallets'=>   $wallets,
        ];
        return response()->json($response, 200);
    }


    public function takeCommitee(Request $request) {


        $mobile = $request->mobile;
        $user = User::where('mobile',$mobile)->first();

            $validator = Validator::make($request->all(),[
                'mobile' => ['required', 'string', 'max:255' , 'exists:users,mobile'],
                'package' => ['required', 'integer', new ValidateLoanPackage($request)],
            ]);


        if ($validator->fails()) {
            $res = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($res, 200);

        }

        $exists =  Investment::where('user_id',$user->id)->first();
        if($exists){
            $resp=$this->make_commitee($request);
        }else{
             $res = [
                'success' => false,
                'message' => "Please Active Your Id First!"
            ];
            return response()->json($res, 200);
        }




        return response()->json($resp, 200);

    }




    public function make_commitee(Request $request){

        $wallet_type=$request->wallet_type;
         $usera = User::where('mobile',$request->mobile)->first();
            $mobile = $request->mobile;

            $pkgDetails = CommiteePackage::where('id',$request->package)->first();
            if($pkgDetails->type=='fix'){
                $amnt = $pkgDetails->amount;
            }else{
                $amnt = $request->amount;
            }

            DB::beginTransaction();
            try {


                  $invest = CommiteeInvestment::create([
                    'user_id' => $usera->id,
                    'tx_user' => Auth::user()->id,
                    'package_id' => $request->package,
                    'amount' => $amnt,
                    'status'  => 1
                ]);

                Wallet::where('user_id',Auth::user()->id)->decrement($wallet_type,$amnt);
                $closedAmnt = $request->user()->wallet->$wallet_type;


                $transaction = Transaction::create([
                    'user_id' => Auth::user()->id,
                    'tx_user' => $usera->id,
                    'amount' => $amnt,
                    'type' => 'debit',
                    'tx_type' => 'commitee',
                    'status'  => 1,
                    'wallet'  => $wallet_type,
                    'close_amount'  => $closedAmnt,
                    'tx_id' => $request->package,
                    'remark'  => 'commitee of  '.$mobile.' activeted',

                ]);


                DB::commit();


                $response = [
                    'success' => true,
                    'data' => $amnt,
                    'active_datetime' => Carbon::parse($transaction->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
                    'message' => "Subscription successfull."
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



    //////////////////////////////////////saving fund income /////////////////////////////////////////////////////////////////////////


    public function saving_packages(Request $request) {
        $wallets = WalletType::whereIn('id',['1','2','3','4','5','6'])->get();
                $daysList = [
                    "0",
                    "6",
                    "12",
                    "18",
                    "24",
                    "36"
                ];


        $userHoldings = SavingFundInvestment::where('user_id', $request->user()->id)
            ->pluck('days') // assuming you store "6 month", "12 month", etc. in `duration` column
            ->toArray();


        $days = collect($daysList)->map(function ($day) use ($userHoldings) {
            return [
                'label' => $day.' month',
                'slug' => $day,
                'status' => in_array($day, $userHoldings) ? 'holding' : 'not holding',
            ];
        });


        $response = [
            'success' => true,
            'packages'=>   SavingPackage::all(),
            'wallets'=>   $wallets,
            'days'=>   $days,
        ];
        return response()->json($response, 200);
    }


    public function takeSavingFund(Request $request) {


        $mobile = $request->mobile;
        $user = User::where('mobile',$mobile)->first();

            $validator = Validator::make($request->all(),[
                'mobile' => ['required', 'string', 'max:255' , 'exists:users,mobile'],
                'package' => ['required', 'integer', new ValidateElitePackage($request)],
            ]);



        if ($validator->fails()) {
            $res = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($res, 200);

        }

        $exists =  Investment::where('user_id',$user->id)->first();
        if($exists){
            $resp=$this->make_saving_fund($request);
        }else{
             $res = [
                'success' => false,
                'message' => "Please Active Your Id First!"
            ];
            return response()->json($res, 200);
        }

        return response()->json($resp, 200);

    }




    public function make_saving_fund(Request $request){

        $wallet_type=$request->wallet_type;
         $usera = User::where('mobile',$request->mobile)->first();
            $mobile = $request->mobile;
            $pkgDetails = SavingPackage::where('id',$request->package)->first();
            if($pkgDetails->type=='fix'){
                $amnt = $pkgDetails->amount;
            }else{
                $amnt = $request->amount;
            }
            DB::beginTransaction();
            try {


                Wallet::where('user_id',Auth::user()->id)->decrement($wallet_type,$amnt);
                $closedAmnt = $request->user()->wallet->$wallet_type;


                  $invest = SavingFundInvestment::create([
                    'user_id' => $usera->id,
                    'tx_user' => Auth::user()->id,
                    'package_id' => $request->package,
                    'amount' => $amnt,
                    'days' => $request->days,
                    'status'  => 1
                ]);


                $transaction = Transaction::create([
                    'user_id' => Auth::user()->id,
                    'tx_user' => $usera->id,
                    'amount' => $amnt,
                    'type' => 'debit',
                    'tx_type' => 'saving_fund',
                    'status'  => 1,
                    'wallet'  => $wallet_type,
                    'close_amount'  => $closedAmnt,
                    'tx_id' => $request->package,
                    'remark'  => 'saving fund of  '.$mobile.' activeted',

                ]);


                DB::commit();


                $response = [
                    'success' => true,
                    'data' => $amnt,
                    'active_datetime' => Carbon::parse($transaction->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
                    'message' => "donation successfull."
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
    //////////////////////////////////////saving fund income /////////////////////////////////////////////////////////////////////////


    public function sendActiveMsg($msg,$mobi){


            $apiKey = "71a9b0fbe3cb414583372e7c5664a5b4";


            $ch = curl_init();

            // Set the URL and other options
            curl_setopt($ch, CURLOPT_URL, "http://whatsapp.click4bulksms.in/wapp/api/send?apikey=$apiKey&mobile=$mobi&msg=$msg");
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

    // public function topup(Request $request){

    // }

    //* -------- API -----------------//


    public function investHistory(Request $request){
         $userId = $request->user()->id;

        // Retrieve a single record based on the condition
        $investment = Investment::where('user_id', $userId)->first();

        // Check if the record is found
        if ($investment) {
            // Paginate the results manually
            $perPage = 10;
            $currentPage = 1; // You may need to adjust this based on the requested page
            $items = collect([$investment]); // Convert the single record to a collection

            $paginatedItems = new LengthAwarePaginator(
                $items->forPage($currentPage, $perPage),
                $items->count(),
                $perPage,
                $currentPage,
                ['path' => LengthAwarePaginator::resolveCurrentPath()]
            );

            $response = [
                'success' => true,
                'data' => $paginatedItems->items(),
                'pagination' => [
                    'current_page' => $paginatedItems->currentPage(),
                    'last_page' => $paginatedItems->lastPage(),
                    'total_items' => $paginatedItems->total(),
                ],
                'message' => 'Transaction History Fetch Successfully.',
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
                'message' => 'Transaction history not fetch!',
            ];
        }

        return response()->json($response, 200);
    }


    ///////test/////////
    public function check(Request $request){



                    $data = Autopool::where('user_id',Auth::user()->id)->first();

                    Distribute::AutoPoolIncome($data);


        /////////////ebike from user by /////////////////////////////////////////

    }

    public function getPackageDetails(Request $request){


        $validator = Validator::make($request->all(),[
                'amount' => ['required', 'integer'],
            ]);



        if ($validator->fails()) {
            $res = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($res, 200);

        }
        $amount = $request->amount;

        $details = PlanRefferalIncome::where('package',$amount)->where('status','1')->first();
        if($details){
            $response = [
                'success' => true,
                'data' => $details,
                'message'=>   'Package Details fetch successfully.',
            ];
        }else{
            $response = [
                'success' => false,
                'data' => '',
                'message'=>   'Package Details not found!.',
            ];
        }

        return response()->json($response, 200);

    }

}
