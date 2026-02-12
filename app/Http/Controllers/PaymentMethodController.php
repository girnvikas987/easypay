<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PaymentMethod;
use App\Models\UserPaymentMethod;
use Illuminate\Http\RedirectResponse;

class PaymentMethodController extends Controller
{
    public function create(Request $request)
    {   
         

        return view('pages.paymentmethod.create', [
            'user' => Auth::user(),
            'paymentMethods'=>   PaymentMethod::all(),
        ]);
    }
    public function store(Request $request): RedirectResponse
    {   
         
         //dd($request);
         $valida=array();
         $valida['paymentmethodid']=['required'];
        $paymentid=$request->paymentmethodid;
        $paymentdetail=PaymentMethod::find($paymentid);
        $options = $paymentdetail->option;
        if(!empty($options)){
            foreach ($options as  $value) {
                if ($value->isRequired && $value->status) {
                    # code...
                    $valida[$value->name]=['required'];
                }
            }
        }
        
        $request->validate($valida);
        $data=array();
        if(!empty($options)){
            
            foreach ($options as  $value) {
                
                if ($value->status) {
                    # code...
                    $vll=$value->name;
                    $data[$paymentdetail->title][$value->title]=$request->$vll;
                }
            }
        }
        $userpaymentmethods = UserPaymentMethod::create([

            'user_id' => Auth::user()->id,
            'data' => json_encode($data)
            
        ]);
        //Auth::login($user);
        $request->session()->flash('status', 'Payment Method Addded!');

        return redirect('./paymentmethod');
    }
}
