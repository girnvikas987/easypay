<?php

namespace App\Http\Controllers;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
     public function getGallery(){
        $result = array();
        
       $exists = Gallery::get();
       
            if($exists){
                    foreach($exists as $exist){
                            $links = [];
                             if($exist->type =='video'){
                                    $links['file'] = ''; 
                                    $links['video'] = $exist->video;
                                    $links['status'] = $exist->status;
                                    $links['type'] = $exist->type;
                                    $links['title'] = $exist->title;
                             }else{
                                    $links['file'] = "https://metvallypay.com/storage/".$exist->file; 
                                    $links['video'] = '';
                                    $links['status'] = $exist->status;
                                    $links['type'] = $exist->type;
                                    $links['title'] = $exist->title;
                             }
                           
                            
                            $result[] = $links;
                            
                    } 
                   $response = [
                      'success' => true, 
                      'data' => $result, 
                      'message' => "Gallery data fetch."
                    ];
                   
            }else{
                    $response = [
                      'success' => false, 
                      'data' => '', 
                      'message' => "Gallery data not fetch!"
                    ];
            }  
        
        return response()->json($response, 200);
     }
}
