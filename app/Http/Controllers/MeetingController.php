<?php

namespace App\Http\Controllers;
use App\Models\Meeting;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    public function getMeeting(){
        
        $meeting = Meeting::all();
       
        if($meeting){
                $response = [
                    'success' => true,
                    'data' => $meeting,
                    'message' => 'Meeting link fetched Successfully.',
                ];
        }else{
                $response = [
                    'success' => false,
                    'data' => [],
                    'message' => 'Meeting link not fetched!',
                ];
        }
          return response()->json($response, 200);
    }
}
