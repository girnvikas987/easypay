<?php

namespace App\Http\Controllers;
use App\Models\Setting;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function getVersion(){
        $appVersion  = Setting::getSetting('app_version');
        if($appVersion){
            $response = [
                'success'=> true,
                'version'=> $appVersion,
                'message'=> "App Version fetch successfully.",
            ];
        }else{
            $response = [
                'success'=> false,
                'version'=> '',
                'message'=> "App Version Not fetch successfully.",
            ];
            
        }
       
        return response()->json($response, 200);
    }
}
