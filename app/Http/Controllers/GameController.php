<?php

namespace App\Http\Controllers;
 
use App\Models\Game;
use App\Models\User;
use App\Models\Wallet;
use App\Models\GameClosing;
use App\Models\GameWin;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Validator;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
 
class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
            date_default_timezone_set('Asia/Kolkata'); // Set your timezone
            $date = date("Y-m-d H:i:s");
          
            $allGame = Game::where('status', 1)->get();

            if ($allGame->isNotEmpty()) { // Check if the collection is not empty
                $customData = [];
            
                foreach ($allGame as $game) {
                    
                     $userId = Auth::user()->id;
                         $type = $game->type;
                     $alreadyParticipate = GameClosing::where('type',$type)->where('user_id',$userId)->first();
                     
                     if($alreadyParticipate){
                         $participate = "yes";
                     }else{
                         $participate = "no";
                     }
                     
                
                    $time = $game->time;
                    $ttlparticipate = GameClosing::where('type',$type)->count();
                    $ttlwin = GameWin::where('type',$type)->count();
                    $dateTime = Carbon::parse($time);
                       
                        // Add the number of minutes from $game->number to the datetime
                        if ($game->number == '1440') {
                            // Set the time to today's date at 11:59 PM
                            $DateTime = Carbon::now()->endOfDay()->subMinute();
                        } else {
                            $DateTime = $dateTime->addMinutes($game->number);
                        }
                    
                        // Format the expiry date and time as needed
                        $formattedDateTime = $DateTime->format('Y-m-d H:i:s');
                    
                    $currentDateTime = Carbon::now();
                  
                    $formattedExpiryDateTime = $currentDateTime->format('Y-m-d H:i:s');
                    
                    // Customize each item as needed
                    $customizedItem = [
                        'id' => $game->id,
                        'type' => $game->type,
                        'time' => $formattedDateTime,
                        'number' => $game->number,
                        'amount' => $game->amount,
                        'no_of_users' => $game->no_of_users,
                        'status' => $game->status,
                        'total_participate' => $ttlparticipate,
                        'total_wins' => $ttlwin,
                        'server_time' => $formattedExpiryDateTime,
                        'participate' => $participate,
                        // Add more fields as needed
                    ];
            
                    // Push the customized item into the customData array
                    $customData[] = $customizedItem;
                }
            
                $response = [
                    'success' => true,
                    'data' => $customData,
                    'message' => "Operators Fetched Successfully."
                ];
            } else {
                $response = [
                    'success' => true,
                    'data' => [], 
                    'message' => "Operators Not Found!"
                ];
            }
            
            return response()->json($response, 200);
    }
    
    
    public function joinGame(Request $request){
        

        if($request->user()->kyc_status == '0'){
            $res = [
                'success' => false,
                'message' => "Please complete your kyc!"
            ];
            return response()->json($res, 200);


        }
            $validator = Validator::make($request->all(),[  
                'game_type' => ['required', 'string','max:255','exists:games,type']
            ]);
            
            // $response = [
            //     'success' => false,
            //     'message' => "please make patience we will back soon.."
            // ];
            
            // return response()->json($response, 200);
        
          if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->errors()
                ];
                return response()->json($response, 200);

            }
        $type = $request->game_type;
        $ExistsGame  =  Game::where('status',1)->where('type',$type)->first();
        if($ExistsGame){
             $userId = Auth::user()->id;
             $active_status = Auth::user()->active_status;
             $prime_pkg = Auth::user()->prime_pkg;
             $loan_pkg = Auth::user()->loan_pkg;
             $ebike_pkg = Auth::user()->ebike_pkg;
             $directs = Auth::user()->directs->count();
             
            //  if($active_status->value == 1 || $prime_pkg > 0 || $loan_pkg > 0 || $ebike_pkg > 0 || $directs >=2){ 
             if(1==1){ 
               
                 $alreadyPlay = GameClosing::where('user_id',$userId)->where('type',$type)->first();
                 
                 if(!$alreadyPlay){ 
                     $ttlusers = GameClosing::where('type',$type)->count();
                     $requiredUser= $ExistsGame->no_of_users;
                     
                     $ttlusers = $ttlusers + 1;
                        if($ttlusers <= $requiredUser){
                          DB::beginTransaction();
                             try {
                                 
                                 
                                    $gameEntry = GameClosing::create([
                                        'user_id' => $userId,
                                        'type' => $type,
                                        'amount' => $ExistsGame->amount, 
                                        'status'  => 1
                                    ]);
                                
                                     DB::commit();     
                                    $responses = [
                                        'success' => true,
                                        'data' => '',
                                        'message' => "success game joining."
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
                             $responses = [
                                'success' => false,
                                'data' => '', 
                                'message' => "Game Over!"
                            ];
                        }
                 }else{
                    $responses = [
                        'success' => false,
                        'data' => '', 
                        'message' => "You are already play The Game!"
                    ];
                 }
             }else{
                $responses = [
                    'success' => false,
                    'data' => '', 
                    'message' => "First Active any our pakage or Refer 2 free Apps."
                ];
             }
            
        }else{
            $responses = [
                'success' => false,
                'data' => '', 
                'message' => "Invalid Game type!"
            ];
        }
        
        return response()->json($responses, 200);
    }
    
    public function updateGameTimer(){
         $allGame  =  Game::where('status',1)->get();
         if($allGame){
             foreach($allGame as $game){
                
                     $date = strtotime($game->time);
                     $currentTime = time();
                    // $currentDateTime = date("Y-m-d H:i:s");
                    // echo $currentDateTime;
                    
                    $timeDifference = ($currentTime - $date); // Absolute difference
                     
                    $numberOfMinutes = $game->number * 60;
                 
                    if ($timeDifference >= $numberOfMinutes) {
                         
                        $type = $game->type; 
                        $getAllUsers = GameClosing::where('type',$type)->get();
                        
                  
                   
                       if($getAllUsers){ 
                           
                           
                           if ($getAllUsers->isNotEmpty()) { // Check if the collection is not empty
                                // Shuffle the collection of users
                                $shuffledUsers = $getAllUsers->shuffle();
                            
                                // Retrieve the first user from the shuffled collection
                                $randomUser = $shuffledUsers->first();
                                
                                $amount = $randomUser->amount;
                                $userId = $randomUser->user_id;
                                $id = $randomUser->id;
                                 $amnt = number_format($amount, 2);
                                if ($amount > 0) { 
                                    $user = User::where('id',$userId)->first();
                                    Wallet::where('user_id',$userId)->increment('elite_wallet',$amount);

                                    $closedAmnt = $user->wallet->elite_wallet;
             
                                      $transaction = Transaction::create([
                                            'user_id' => $userId,
                                            'tx_user' => $userId,
                                            'amount' => $amount,
                                            'type' => 'credit',
                                            'tx_type' => 'income',
                                            'income' => 'lucky_draws',
                                            'status'  => 1,
                                            'wallet'  => 'elite_wallet', 
                                            'close_amount'  => $closedAmnt, 
                                            'status' => '1',
                                            'remark' => "Receive lucky_draws income of amount $amnt from $type"
                                        ]);  
                                        
                                         

 
                                    \DB::table('game_closings')->where('id',$id)->delete(); 
                                                  
                                }
                                
                                
                                $gameWin = GameWin::create([
                                    'user_id' => $userId, 
                                    'type' => $type, 
                                    'status' => '1',
                                ]);  
                                 
                                                
                                // $todwin =array();
                                // $todwin['user_id'] = $userId;
                                // $todwin['type'] = $type;
                                // $this->db->insert('today_winers',$todwin);
                                //\DB::table('game_closings')->delete();
                                \DB::table('game_closings')->where('type',$type)->delete(); 
                                    
                                }
                            } 
                            
                            
                        $date = date("Y-m-d H:i:s"); // Update $date to current timestamp
                        
                        // Update time only if condition is met
                        $game->time = $date;
                        $game->save();
                       }
                 }
             }
         }
         
         
    public function getParticipateList(Request $request){
         
         
            $validator = Validator::make($request->all(),[  
                'game_type' => ['required', 'string','max:255','exists:games,type']
            ]);
    
            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->errors()
                ];
                return response()->json($response, 200);
    
            }
            $type = $request->game_type;
            $allParticipate = GameClosing::where('type',$type)->get();
            
            if($allParticipate){
                $userNames = array();
           
            foreach($allParticipate as $participate){
                
                 
                 $userId = $participate->user_id;
    
                        
                        $user = User::find($userId);
                        
                        // Check if user exists
                        if ($user) {
                             
                            $userNames[] = $user->name;
                        } 
                    }
            }
                    
                    // Prepare the response
                    $response = [
                        'success' => true,
                        'user_names' => $userNames,
                        'message' => "User names fetched successfully."
                    ];
                    
                    return response()->json($response, 200);
                
    }
    public function getWinsList(Request $request){
         
         
            $validator = Validator::make($request->all(),[  
                'game_type' => ['required', 'string','max:255','exists:games,type']
            ]);
    
            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->errors()
                ];
                return response()->json($response, 200);
    
            }
            $type = $request->game_type;
            $allwin = GameWin::where('type',$type)->orderBy('created_at', 'desc')->limit(50)->get();
            
            if($allwin){
                $userNames = array();
           
            foreach($allwin as $win){
                
                 
                 $userId = $win->user_id;
    
                        
                        $user = User::find($userId);
                        
                        // Check if user exists
                        if ($user) {
                             
                            $userNames[] = $user->name;
                        } 
                    }
            }
                    
                    // Prepare the response
                    $response = [
                        'success' => true,
                        'user_names' => $userNames,
                        'message' => "User names fetched successfully."
                    ];
                    
                    return response()->json($response, 200);
                
    }
      
    public function getWinsList2(Request $request){
         
         
            $validator = Validator::make($request->all(),[  
                'game_type' => ['required', 'string','max:255','exists:games,type']
            ]);
    
            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->errors()
                ];
                return response()->json($response, 200);
    
            }
            $type = $request->game_type;
            $allwin = GameWin::where('type',$type)->orderBy('created_at', 'desc')->limit(50)->get();
          
            if($allwin){
                $userNames = array();
  
                $record = $allwin->map(function ($request) {
                    $user = User::find($request->user_id);
                    if ($user) {
                      
                        $dat = Game::where('type',$request->type)->first();
                     
                        return [ 
                        'name' => $user->name,  
                        'mobile' => $user->mobile,  
                        'amount' =>  $dat->amount,  
                        'date' => Carbon::parse($request->created_at)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'), 
                        ];
                     }else{
                        return [];
                     }
                  });
                  
            }

            // foreach($allwin as $win){
                
                 
            //      $userId = $win->user_id;
    
                        
            //             $user = User::find($userId);
                        
            //             // Check if user exists
            //             if ($user) {
                             
            //                 $userNames[] = $user->name;
            //             } 
            //         }
            // }
                    
                    // Prepare the response
                    $response = [
                        'success' => true,
                        'user_names' =>  $record,
                        'message' => "User names fetched successfully."
                    ];
                    
                    return response()->json($response, 200);
                
                }
      
     
}
