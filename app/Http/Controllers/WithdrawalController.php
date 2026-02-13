<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Investment;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\Kyc;
use App\Models\Bank;
use App\Models\Withdrawal;
use App\Models\Test;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use App\Rules\Checkbalance;
use Illuminate\Support\Facades\DB;
use App\Listeners\TopupListener;
use App\Models\UserPaymentMethod;
use App\Models\WithdrawRequest;
use App\Rules\WithdrawCheck;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Validator;

class WithdrawalController extends Controller
{
    public function withdraw(Request $request)
    {
        $userId=$request->user()->id;
        $dir = User::find($userId);
        return view('pages.withdrawal.withdrawal', [
            'user' => $dir,
        ]);
    }
    public function store(Request $request): RedirectResponse
    {
        $userId = Auth::user()->id;
        $wallet_type='main_wallet';
        $request->validate([
            //'receive_in' => ['required','integer'],
            'amount' => ['required', 'integer', 'min:2' ,new Checkbalance('fund_wallet'),new WithdrawCheck($request)],
        ]);
        //$usera = User::where('username',$request->username)->first();
        //$receiveing_data=UserPaymentMethod::find($request->receive_in)->first();


        DB::beginTransaction();
        try {
            //code...
            $amnt = $request->amount;
            $x_charge = $amnt*10/100;
            $payable = $amnt-$x_charge;

            $transaction = Withdrawal::create([
                'user_id' => Auth::user()->id,
                'user_details' =>Auth::user()->eth_address,
                'amount' => $payable,
                'tx_charge' => $x_charge,
                'status'  => 0,
            ]);

            Wallet::where('user_id',Auth::user()->id)->decrement('main_wallet',$request->amount);
            DB::commit();
            $request->session()->flash('status', 'Withdrawal Request successful!');

        } catch (\Exception $e) {
            //throw $th;
            DB::rollBack();
            $request->session()->flash('error', 'Something wrong!');

        }




        //Auth::login($user);

        return redirect('./withdraw');
    }

    public function history(Request $request)
    {

        $query=$request->user()->withdrawals();
        $a=$query->paginate(10)->withQueryString();
        return view('pages.withdrawal.history', [
            'user' => $request->user(),
        ])->with('transactions',$a);

    }




    public function WithdrawAmnt(Request $request){



        $kyc  = Kyc::where('user_id',$request->user()->id)->first();
        if($kyc){


         if($kyc->nominee_status == '1' && $kyc->pan_status == '1' && $kyc->aadhar_status == '1'){
            $wallet_type = $request->wallet_type;

           $userId=$request->user()->id;
            $validator = Validator::make($request->all(),[
             'wallet_type' => ['required', 'string', 'max:255'],
             'amount' => ['required', 'integer', 'min:100' ,new Checkbalance($wallet_type)],
            ]);

            if ($validator->fails()) {
                $res = [
                    'success' => false,
                      'data' =>  '',
                    'message' => $validator->errors()
                ];
                return response()->json($res, 200);

            }





                        $bankDetails = Bank::where('user_id',$userId)->first();


                        if($bankDetails->account != '' && $bankDetails->ifsc_code != '' && $bankDetails->bank_name != ''){

                            $accountNum =$bankDetails->account;
                            $bank_name =$bankDetails->bank_name;
                            $ifsc =$bankDetails->ifsc_code;
                            $mobile =$request->user()->mobile;
                            $email =$request->user()->email;
                            $name =Str::lower($request->user()->name);

                            // if (strlen($name) > 4) {

                                    //code...
                                            $amnt = $request->amount;

                                                $tx_charge = $amnt*12/100;
                                                $extra = $amnt*5/100;
                                                $tds_charge = $amnt*2/100;


                                             $fin = $amnt-$tx_charge;
                                            $payable = round($fin);
                                            $timestamp = now()->format('YmdHis'); // Current timestamp






                                                        $transaction = Withdrawal::create([
                                                            'user_id' => Auth::user()->id,
                                                            'amount' => $payable,
                                                            'tx_charge' => $tx_charge,
                                                            'tds_charge' => $tds_charge,
                                                            'wallet_type' => $wallet_type,
                                                            'status'  => 0,
                                                        ]);


                                                         $transactions = Transaction::create([
                                                            'user_id' => Auth::user()->id,
                                                            'tx_user' => Auth::user()->id,
                                                            'amount' => $payable,
                                                            'charges' => $tx_charge,
                                                            'type' => 'debit',
                                                            'tx_type' => 'withdraw',
                                                            'status'  => 0,
                                                            'wallet'  => $wallet_type,
                                                            'remark'  => 'withdraw  of  '.$payable.' amount',

                                                        ]);

                                                        Wallet::where('user_id',Auth::user()->id)->decrement($wallet_type,$request->amount);
                                                        Wallet::where('user_id',Auth::user()->id)->increment('atoot_bandhan_wallet',$extra);
                                                        Wallet::where('user_id',Auth::user()->id)->increment('finacial_support_wallet',$extra);

                                                        $success['date'] = Carbon::parse($transaction->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s');
                                                        $success['bank_details'] = $bankDetails;
                                                        $responses = [
                                                            'success' => true,
                                                            'data' =>  $success,
                                                            'message' => "Withdrawal Request Generated successful."
                                                        ];



                            // }else{
                            //     $responses = [
                            //         'success' => false,
                            //         'data' =>  '',
                            //         'message' => "The length of Name must be 20 characters or fewer."
                            //     ];
                            // }


                        }else{
                            $responses = [
                                'success' => false,
                                'data' =>  '',
                                'message' => "Invalid Account details!Please Fill Correct Account Details."
                            ];
                        }


        }else{
                $responses = [
                    'success' => false,
                    'data' =>  '',
                    'message' => "Withdrawals are not allowed at this time! Please complete your kyc."
                ];
        }
        }else{
                $responses = [
                    'success' => false,
                    'data' =>  '',
                    'message' => "Withdrawals are not allowed at this time! Please complete your kyc."
                ];
        }

            return response()->json($responses, 200);
    }


    //////////////////////////////////////////scan and pay////////////////////////////////////////////////////////////////////////////////////////////////////

    //////////////////////////////////////////scan and pay////////////////////////////////////////////////////////////////////////////////////////////////////


    public function withdrawHistory(Request $request)
    {
        $perPage = 20;
        $currentPage = 1;

        // Retrieve the status from the request
        $status = $request->input('status');  // Assuming 'status' is sent via request

        // Build the base query
        $withdrawalQuery = $request->user()->withdrawals()->orderBy('created_at', 'desc');

        // Apply status filter if status is provided
        if (!is_null($status)) {
            $withdrawalQuery->where('status', $status);
        }

        // Paginate the results
        $filteredTransactions = $withdrawalQuery->paginate($perPage, ['*'], 'page', $currentPage);

        // Manually convert the created_at to IST if needed
        $filteredTransactions->getCollection()->transform(function ($transaction) {
            $transaction->created_at = \Carbon\Carbon::parse($transaction->created_at)
                ->timezone('Asia/Kolkata')  // Convert to IST
                ->format('Y-m-d H:i:s');    // Format as desired
            return $transaction;
        });

        // Prepare the response
        if ($filteredTransactions->count() > 0) {
            $response = [
                'success' => true,
                'data' => $filteredTransactions->items(),
                'pagination' => [
                    'current_page' => $filteredTransactions->currentPage(),
                    'last_page' => $filteredTransactions->lastPage(),
                    'total_items' => $filteredTransactions->total(),
                ],
                'message' => 'Withdrawal history fetched successfully.',
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
                'message' => 'No withdrawal history found!',
            ];
        }

        return response()->json($response, 200);
    }




}
