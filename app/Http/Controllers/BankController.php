<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Bank;
use App\Models\Test;
use Illuminate\Http\Response;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stringable;
use Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Exists;

class BankController extends Controller
{
     public function updateBankDetails(Request $request){


        $bank = Bank::where('user_id', $request->user()->id)->first();
        if($request->user()->kyc_status == '1'){


            $validator = Validator::make($request->all(),[
                'bank_name' => ['required','max:255'],
                  'branch_name' => ['required','max:255'],
                'holder_name' => ['required', 'string', 'min:5', 'max:20'],
                'account' => ['required','numeric'],
                'ifsc_code' => ['required','max:255'],
            ]);
                
            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->errors()
                ];
                return response()->json($response, 200);
    
            }
            
            $bank_name = $request->bank_name;
           $branch_name = $request->branch_name;
            $holder_name = $request->holder_name;
            $account = $request->account;
        $ifsc_code = $request->ifsc_code;
            
                DB::beginTransaction();
                try {
                     
                    
                    $bank = Bank::Create( 
                        [
                            'user_id' => Auth::user()->id,
                            'bank_name' => $bank_name,
                         'branch_name' => $branch_name,
                            'holder_name' => $holder_name,
                            'account' => $account,
                           'ifsc_code' => $ifsc_code,
                            'status' => 1,
                        ]
                    );
                    DB::commit();   
                    $response = [
                       'success' => true, 
                       'data' => '', 
                       'message' => "Bank details updated Successfully."
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

        }else{

            $response = [
                'success' => false, 
                'data' => '', 
                'message' => "Please complete your kyc first!."
            ];

        }
       
        
         
        return response()->json($response, 200);

   }


    public function deleteBankDetails(Request $request)
    {
        // Find the user's bank details
        $bank = Bank::where('user_id', $request->user()->id)->first();

        // Check if bank details exist for the user
        if ($bank) {
            DB::beginTransaction();
            try {

                $bankdetails = Bank::where('id', $request->bank_id)->first();
                if($bankdetails){
                    $bank->delete();
                    DB::commit();
                       // Return success response
                    $response = [
                        'success' => true,
                        'message' => "Bank details deleted successfully.",
                    ];
                }else{
                    $response = [
                        'success' => false,
                        'message' => "Something went wrong: this Record not found!",
                    ];
                }  
            } catch (\Exception $e) {
                DB::rollBack();
                // Return error response
                $response = [
                    'success' => false,
                    'message' => "Something went wrong: " . $e->getMessage(),
                ];
            }
        } else {
            // No bank details found
            $response = [
                'success' => false,
                'message' => "No bank details found for this user.",
            ];
        }

        return response()->json($response, 200);
    }

   
    public function getBankData(Request $request){
       $userId = $request->user()->id;
       $pan_number = $request->user()->pan_number;
       $exists = Bank::where('user_id',$userId)->first();
        if($exists){
                   
                   $response = [
                      'success' => false, 
                      'data' => $exists, 
                      'pan_data' => $pan_number ?? '',
                      'message' => "Bank data fetch."
                    ];
                   
        }else{
                    $response = [
                      'success' => false, 
                      'data' => '', 
                      'pan_data' => '',
                      'message' => "Bank data not found!"
                    ];
        }  
        
        return response()->json($response, 200);
   } 
    public function getNewBankData(Request $request){
       $userId = $request->user()->id;
       $pan_number = $request->user()->pan_number;
       $exists = Bank::where('user_id',$userId)->get();
        if($exists){
                   
                   $response = [
                      'success' => true, 
                      'data' => $exists, 
                      'pan_data' => $pan_number ?? '',
                      'message' => "Bank data fetch."
                    ];
                   
        }else{
                    $response = [
                      'success' => false, 
                      'data' => '', 
                      'pan_data' => '',
                      'message' => "Bank data not found!"
                    ];
        }  
        
        return response()->json($response, 200);
   } 



   ////////////////////////////////cyrus api verify /////////////////////////////////////////////////////////////////////



}
