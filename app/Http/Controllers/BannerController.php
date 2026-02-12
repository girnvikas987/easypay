<?php

namespace App\Http\Controllers;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    
    public function getBanner(){
         $result = array();
        
            $exists = Banner::where('type','dashboard')->get();
       
            if($exists){
                    foreach($exists as $exist){
                                    $links = [];
                              
                                    $links['image'] = "https://s2pay.life/storage/".$exist->image;  
                                    $links['status'] = $exist->status;
                                    $links['type'] = $exist->type;
                                    $links['title'] = $exist->title; 
                                    $result[] = $links;
                            
                    } 
                        $response = [
                          'success' => true, 
                          'data' => $result, 
                          'message' => "Banner data fetch."
                        ];
                   
            }else{
                        $response = [
                          'success' => false, 
                          'data' => '', 
                          'message' => "Banner data not fetch!"
                        ];
            }  
        
        return response()->json($response, 200);
    }
           
}
