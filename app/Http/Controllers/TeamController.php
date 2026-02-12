<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\User;
use App\Models\Autopool;
use App\Models\Investment;
use App\Models\EbikeInvestment;
use App\Models\EliteInvestment;
use App\Models\FlyInvestment;
use App\Models\GoldInvestment;
use App\Models\RechargeInvestment;
use App\Models\LoanInvestment;
use App\Models\PlanSetting;
use App\Models\Order;
use App\Models\TourInvestment;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{   
    
    public function directs(Request $request)
    {
        //DB::enableQueryLog();
        $name = $request->query('name');
        $username = $request->query('username');
        $status = $request->query('status');
        

        $userId=$request->user()->id;
        $query=User::find($userId)->directs();
        $count=User::find($userId)->directs()->count();


        if($name){
            //dd($request);
            $query->whereHas(relation : 'user', callback : function($q) use ($name){
                 $q->where('name','like','%'.$name.'%');
            });
        }
        if($username){
            $query->whereHas(relation : 'user', callback : function($q) use ($username){
                 $q->where('username','like','%'.$username.'%');
            });
        }
        if($status!=''){
            $query->where('active_status',$status);
        }
        
        
        $a=$query->paginate(10)->withQueryString();
       // dd(DB::getQueryLog());
         
        
        return view('pages.directs', [
            'user' => $request->user(),
            'name' => $name,
            'username' => $username,
            'status' => $status,
        ])->with('directs',$a);
        
    }

    

    public function generation(Request $request){
        $userId=$request->user()->id;
        $gen = $request->user()->team->gen;
        $query=User::whereIn('id',$gen);
         
        
        //dd($query);
        $name = $request->query('name');
        $username = $request->query('username');
        $status = $request->query('status');

        if($name){             
            $query->where('name','like','%'.$name.'%');             
        }
        if($username){             
            $query->where('username','like','%'.$username.'%');             
        }
        if($status){             
            $query->where('active_status','like','%'.$status.'%');             
        }

        
        
        $a=$query->paginate(10)->withQueryString();
       // dd($a);
         
        
        return view('pages.generation', [
            'user' => $request->user(),
            'name' => $name,
            'username' => $username,
            'status' => $status,
        ])->with('team',$a);
    }
    
    /////////////////////////////////////////// Api start //////////////////////////////////////////////////////////
    // public function getGeneration(Request $request){

       
    //     $user = $request->user();
    //     $userId = $request->user()->id;
         
    //     $gen = $user->team->gen;
          
    //     $teams = array($userId);
       
    //     $level = $request->level;
         
    //     $lvlteam = array();
        
      
    //     if ($level == 0) {
    //         $level = 10;
             
    //         for ($i = 0; $i < $level; $i++) {
    //             if ($teams) {
    //                 $teams = Team::whereIn('sponsor', $teams)->pluck('user_id')->toArray(); // Convert to array
    //                 if (!empty($teams)) {
    //                     $lvlteam = array_merge($lvlteam, $teams); 
    //                     $investment   = Investment::whereIn('user_id',$lvlteam)->where('status',1)->count();
                    
    //                     $lvlTeam = 0;
    //                 }
    //             }
    //         }
    //     } else {
    //         for ($i = 0; $i < $level; $i++) {
    //             if ($teams) {
    //                 $teams = Team::whereIn('sponsor', $teams)->pluck('user_id')->toArray(); // Convert to array
    //                 $lvlteam = $teams; 
                
    //             }
    //         }
    //     }


       
        
    //     // $gen = $request->user()->team->gen;
    //     // $query=User::whereIn('id',$teams);
         

    //     $query = User::whereIn('id', $lvlteam) ->with(['team' => function($query) {
    //         $query->select('user_id', 'position'); // Select only the user_id and position columns
    //     }])
    //         ->select(['*', \DB::raw('NULL as transaction_pin')])
    //         ->paginate(20);

    //     if ($query->count() > 0) {
    //         $response = [
    //             'success' => true, 
            
               
    //             'data' => $query->items(),
    //             'pagination' => [
    //                 'current_page' => $query->currentPage(),
    //                 'last_page' => $query->lastPage(),
    //                 'total_items' => $query->total(),
    //             ],
    //             'message' => 'Team data fetch Successfully.',
    //         ];
    //     } else {
    //         $response = [
    //             'success' => true,
    //             'ttlTeam' => 0,
    //             'lvlTeam' => 0,  
    //             'data' => [],
    //              'pagination' => [
    //                 'current_page' => 0,
    //                 'last_page' => 0,
    //                 'total_items' => 0,
    //             ],
    //             'message' => 'Team data not fetch!',
    //         ];
    //     }

    //     return response()->json($response, 200);
    // }
    
     /////////////////////////////////////////// Api start //////////////////////////////////////////////////////////
  
    public function getGeneration(Request $request)
    {
        $user = $request->user();
        $userId = $user->id;

        $mobile = $request->mobile;
        $username = $request->username;

        if($mobile){
            $info=User::where('mobile',$mobile)->first();
            $userId = $info->id;
        }
        if($username){
            $info=User::where('username',$username)->first();
            $userId = $info->id;
        }
            
        
        $teams = array($userId); // Start with the user's ID
        $level = 15; // Default to 30 levels
        $lvlteam = array();
        $levelWiseCounts = [];
        $levelWiseActiveCounts = [];
    
        // Loop through the levels
        for ($i = 0; $i < $level; $i++) {
            if ($teams) {
                // Fetch all user IDs who are sponsored by the current team members
                $teamsQuery = Team::whereIn('sponsor', $teams);
                $teams = $teamsQuery->pluck('user_id')->toArray();
    
                // Count all team members at the current level
                $levelWiseCounts['level_' . ($i + 1)] = count($teams);
    
                // Count only active team members at the current level
                $activeTeams = $teamsQuery->where('active_status', true)->pluck('user_id')->toArray();
                $levelWiseActiveCounts['level_' . ($i + 1)] = count($activeTeams);
            } else {
                // Set both counts to 0 if no team members are found
                $levelWiseCounts['level_' . ($i + 1)] = 0;
                $levelWiseActiveCounts['level_' . ($i + 1)] = 0;
            }
        }
        $gen = Team::where('user_id', $userId)->value('gen');
     
        $active_teams = User::whereIn('id', $gen)->where('active_status',1)->count();
       $total_teams = User::whereIn('id', $gen)->count();
       
        // Prepare response
        $response = [
            'success' => true,
            'level_wise_counts' => $levelWiseCounts,
            'level_wise_Active_counts' => $levelWiseActiveCounts,
            'active_teams' => $active_teams,
            'total_teams' => $total_teams,
            'message' => 'Team data fetched successfully.',
        ];
    
        return response()->json($response, 200);
    }


     
    /////////////////////////////////////////// Api start //////////////////////////////////////////////////////////
    public function getNewGeneration(Request $request){
        $user = $request->user();
        $userId = $request->user()->id;
        $gen = $user->team->gen;
        $teams = array($userId);
        $level = $request->level;
        $lvlteam = array();
        $ttTeam = count($gen);
        
       
        $activeStatus = $request->active_status;
        if ($level == 0) {
            $level = 10;
             
            for ($i = 0; $i < $level; $i++) {
                if ($teams) {
                    $query = Team::whereIn('sponsor', $teams);

                    // Apply the filter for active_status if it's provided
                    if ($activeStatus !== null) {
                        $query->where('active_status', $activeStatus);
                    }
        
                    // Get the user IDs for the next level
                    $teams = $query->pluck('user_id')->toArray();
                    if (!empty($teams)) {
                        $lvlteam = array_merge($lvlteam, $teams); 
        
                        $ttTeam = count($lvlteam);
                        $lvlTeam = 0;
                    }
                }
            }
        } else {
            for ($i = 0; $i < $level; $i++) {
                if ($teams) {
                    $query = Team::whereIn('sponsor', $teams);

                    // Apply the filter for active_status if it's provided
                    if ($activeStatus !== null) {
                        $query->where('active_status', $activeStatus);
                    }
        
                    // Get the user IDs for the next level
                    $teams = $query->pluck('user_id')->toArray(); // Convert to array
                    $lvlteam = $teams;  
                    $lvlTeam = count($lvlteam);
                }
            }
        }

 
       
        
        // $gen = $request->user()->team->gen;
        // $query=User::whereIn('id',$teams);
         
        $query = User::whereIn('id', $lvlteam)
        ->with(['team' => function($query) {
            // Select all necessary columns except 'gen'
            $query->select('id', 'sponsor', 'active_status', 'user_id', 'created_at', 'updated_at');
        }])
        ->select(['*', \DB::raw('NULL as transaction_pin')])
        ->paginate(20);
    
 
        if ($query->count() > 0) {
            $response = [
                'success' => true, 
                'ttlTeam' => $ttTeam, 
                'lvlTeam' => $lvlTeam,  
                'data' => $query->items(),
                'pagination' => [
                    'current_page' => $query->currentPage(),
                    'last_page' => $query->lastPage(),
                    'total_items' => $query->total(),
                ],
                'message' => 'Team data fetch Successfully.',
            ];
        } else {
            $response = [
                'success' => true,
                'ttlTeam' => 0,
                'lvlTeam' => 0,    
                'data' => [],
                 'pagination' => [
                    'current_page' => 0,
                    'last_page' => 0,
                    'total_items' => 0,
                ],
                'message' => 'Team data not fetch!',
            ];
        }

        return response()->json($response, 200);
    }


      
     
    
    
    public function getDirectsold(Request $request)
    {
       $userId = $request->user()->id;

        // Retrieve a user based on the ID and get the 'directs' relationship
        $user = User::find($userId);
        $query = $user->directs();
        $count = $user->directs()->count();

        // Paginate the results
        $directs = $query->paginate(20);

        if ($directs->count() > 0) {
            $response = [
                'success' => true,
                'ttl' => $count,
                'data' => $directs->items(),
                'pagination' => [
                    'current_page' => $directs->currentPage(),
                    'last_page' => $directs->lastPage(),
                    'total_items' => $directs->total(),
                ],
                'message' => 'Direct data fetch Successfully.',
            ];
        } else {
            $response = [
                'success' => true,
                'data' => [],
                'ttl' => 0,
                 'pagination' => [
                    'current_page' => 0,
                    'last_page' => 0,
                    'total_items' => 0,
                ],
                'message' => 'Direct data not fetch!',
            ];
        }

        return response()->json($response, 200);
        
    }

    public function getDirects(Request $request)
{
    $userId = $request->user()->id;
    $user = User::find($userId);
    
    // Retrieve directs relationship
    $directsQuery = $user->directs();
    $count = $directsQuery->count();

    // Paginate the results
    $directs = $directsQuery->paginate(20);
    
    // Get all user IDs of directs
    $directUserIds = $directs->pluck('user_id')->toArray();
    
    // Prepare an array to store data for each direct
    $directsData = [];

    foreach ($directUserIds as $directId) {
        // Fetch user data
        $userData = User::find($directId);

        
    $teamData = Team::where('user_id', $directId)->first();
    $position = $teamData ? $teamData->position : null; // Get position or set to null if not found
    
        
        // Calculate data for each direct user
        $investment = Investment::where('user_id', $directId)->where('status', 1)->sum('amount');
        $loanInvestment = LoanInvestment::where('user_id', $directId)->where('status', 1)->sum('amount');
        $ebikeInvestment = EbikeInvestment::where('user_id', $directId)->where('status', 1)->sum('amount');
        $rechargeInvestment = RechargeInvestment::where('user_id', $directId)->where('status', 1)->sum('amount');
        $goldInvestment = GoldInvestment::where('user_id', $directId)->where('status', 1)->sum('amount');
        $tourInvestment = TourInvestment::where('user_id', $directId)->where('status', 1)->sum('amount');
        $eliteInvestment = EliteInvestment::where('user_id', $directId)->where('status', 1)->sum('amount');
        $flyInvestment = FlyInvestment::where('user_id', $directId)->where('status', 1)->sum('amount');
        $orders = Order::where('user_id', $directId)->where('status', 1)->count();
        
        // Merge user data with the investment data
        $directsData[] = array_merge(
            $userData->toArray(), // Convert the user object to an array
            [
                'position' => $position,
                'investment' => $investment,
                'loanInvestment' => $loanInvestment,
                'ebikeInvestment' => $ebikeInvestment,
                'rechargeInvestment' => $rechargeInvestment,
                'goldInvestment' => $goldInvestment,
                'tourInvestment' => $tourInvestment,
                'eliteInvestment' => $eliteInvestment,
                'flyInvestment' => $flyInvestment,
                'orders' => $orders,
            ]
        );
    }

    // Build response data
    if ($count > 0) {
        $response = [
            'success' => true,
            'ttl' => $count,
            'directs' => $directsData, // Each direct's individual data
            'pagination' => [
                'current_page' => $directs->currentPage(),
                'last_page' => $directs->lastPage(),
                'total_items' => $directs->total(),
            ],
            'message' => 'Direct data fetched successfully.',
        ];
    } else {
        $response = [
            'success' => true,
            'ttl' => 0,
            'directs' => [],
            'pagination' => [
                'current_page' => 0,
                'last_page' => 0,
                'total_items' => 0,
            ],
            'message' => 'No directs found!',
        ];
    }

    return response()->json($response, 200);
}



}
