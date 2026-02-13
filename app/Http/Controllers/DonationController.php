<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donate;
use App\Models\Transaction;
use App\Models\Donation;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stringable;
use Validator;
use Illuminate\Support\Str;
class DonationController extends Controller
{
   public function index(){

       $donations = Donation::all();
        $response = [
            'success' => true,
            'data'=>   $donations,
        ];

       return response()->json($response,200);
   }
    public function store(Request $request)
    {
        // validate input

         $validator = Validator::make($request->all(),[
            'amount' => ['required', 'numeric'],
            // 'wallet_type' => ['required', 'string','max:255'],
            'donate_id' => ['required', 'string','max:255'],
            'slip_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'], // Added validation
        ]);
        // $wallet_type = $request->wallet_type;

         if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => $validator->errors(),
            ];
            return response()->json($response, 200);

        }

        $imageName = time().'.'.$request->slip_image->extension();

        $request->slip_image->storeAs('donate_slips', $imageName);

        // Wallet::where('user_id',$request->user()->id)->decrement($wallet_type,$request->amount);
        // $closedAmnt = $request->user()->wallet->$wallet_type;

            $mobile = $request->user()->mobile;
        // create donation
        $donation = Donate::create([
            'user_id' => $request->user()->id,
            'donate_id' => $request->donate_id,
            'amount'  => $request->amount,
            'slip_image' => "donate_slips/".$imageName,
            'status'  => '1', // or approved directly
        ]);

        // create transaction entry
        Transaction::create([
            'user_id' => $request->user()->id,
            'tx_user' => $request->user()->id,
            'amount'  => $request->amount,
            'tx_type' => 'donation',
            'type' => 'credit',
            // 'wallet'  => $wallet_type,
            'tx_id'   => $request->donate_id,
            'status'  => '1', // or pending if donation is not approved yet
            'remark'  => 'donation by '.$mobile,
        ]);



        return response()->json([
            'success' => true,
            'message' => 'Donation request submitted successfully.',
            'donation'=> $request->amount,
        ], 200);
    }



}
