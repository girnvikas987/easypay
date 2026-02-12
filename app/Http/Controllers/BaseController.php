<?php

namespace App\Http\Controllers;

use App\Models\FlyInvestment;
use App\Models\Setting;
use App\Models\ReferralData;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function index(Request $request)
    {
        $ipAddress = $request->ip();
        
        if($request->has('referral') && $request->has('position')){
          
            $reffera  = $request->input('referral');
            $position  = $request->input('position');
            
              $exists = ReferralData::where('ip',$ipAddress)->first();
              if($exists){
                  $exists->referral = $reffera;
                  $exists->save();
                  
              }else{
                  ReferralData::create([
                    'ip'=>$ipAddress,
                    'referral'=>$reffera,
                    'status'=>0, 
                ]);
              }
            //   return redirect('https://play.google.com/store/apps/details?id=com.app.s2pay');
            //   $url = 'https://play.google.com/store/apps/details?id=com.app.s2pay&referrer=user%3D987654321';
              $url = 'https://play.google.com/store/apps/details?id=com.app.s2pay&referrer=user%3D'.$reffera.'%26position%3D'.$position;
              return redirect($url);
        }elseif($request->has('referral')){
            $reffera  = $request->input('referral'); 
            
              $exists = ReferralData::where('ip',$ipAddress)->first();
              if($exists){
                  $exists->referral = $reffera;
                  $exists->save();
                  
              }else{
                  ReferralData::create([
                    'ip'=>$ipAddress,
                    'referral'=>$reffera,
                    'status'=>0, 
                ]);
              }
            //   return redirect('https://play.google.com/store/apps/details?id=com.app.s2pay');
            //   $url = 'https://play.google.com/store/apps/details?id=com.app.s2pay&referrer=user%3D987654321';
              $url = 'https://play.google.com/store/apps/details?id=com.app.s2pay&referrer=user%3D'.$reffera;
              return redirect($url);

        }

         
     
        $maintheme  = Setting::getSetting('mtheme','mtheme1');
        session()->put('mtheme',$maintheme);
        $Maindashborad = 'layouts.'.$maintheme.'.index';
        return view($Maindashborad);
        

    }
    
    public function fetchReferral(Request $request){
        $ipAddress =  $request->ip;
        $exists = ReferralData::where('ip',$ipAddress)->first();
        if($exists){
             $response = [
                'success' => true,
                'data' =>  $exists,
                'message' => "Referral data fetch successfully."
            ];
        }else{
            $response = [
                'success' => false,
                'data' =>  '',
                'message' => 'Ip Data not found!'
            ];
        }
        return response()->json($response, 200); 
        
    } 
    
    public function contact()
    {

        $maintheme  = Setting::getSetting('mtheme','mtheme1');
       
        $Maindashborad = 'layouts.'.$maintheme.'.contact';
        return view($Maindashborad);
    }
    public function policyReturn()
    {

        $maintheme  = Setting::getSetting('mtheme','mtheme1');
       
        $Maindashborad = 'layouts.'.$maintheme.'.return_policy';
        return view($Maindashborad);
    }
    public function policyRefund()
    {

        $maintheme  = Setting::getSetting('mtheme','mtheme1');
       
        $Maindashborad = 'layouts.'.$maintheme.'.refund_policy';
        return view($Maindashborad);
    }
    
    public function privacy()
    {

        $maintheme  = Setting::getSetting('mtheme','mtheme1');
       
        $Maindashborad = 'layouts.'.$maintheme.'.privacy_policy';
        return view($Maindashborad);
    }
    
    public function conditions()
    {

        $maintheme  = Setting::getSetting('mtheme','mtheme1');
        
        $Maindashborad = 'layouts.'.$maintheme.'.term_condition';
        return view($Maindashborad);
    }

    public function ticket()
    {
        // Step 1: Fetch the flyinvestment ID (example: from the request or database)
        $flyinvestmentId = 1; // You can replace this with dynamic logic to get the actual ID
    
        // Step 2: Retrieve data based on the flyinvestment ID
        $flyinvestmentData = FlyInvestment::with('user')->where('id','1')->first(); // Example, replace with your model and query logic
    
        // Step 3: Fetch the main theme setting
        $maintheme = Setting::getSetting('mtheme', 'mtheme1');
    
        // Step 4: Set the layout view path
        $Maindashborad = 'layouts.' . $maintheme . '.ticket';
      
        // Step 5: Pass the retrieved data to the view
        return view('myPDF_new', compact('flyinvestmentData'));
    }
    
}
