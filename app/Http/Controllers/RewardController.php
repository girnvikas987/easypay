<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use App\Models\PlanMonth;
use App\Models\PlanReward;
use App\Models\Team;
use App\Models\UserReward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RewardController extends Controller
{
    public function index() 
    {
        $rewards = PlanReward::all();
        $this->calculateReward();
        $my_reward = UserReward::where('user_id',Auth::user()->id)->get();
        $direct_required = $rewards->where('direct_required','>',0)->count();
        $generation_team_required = $rewards->where('generation_team_required','>',0)->count();
        $self_business_required = $rewards->where('self_business_required','>',0)->count();
        $direct_business_required = $rewards->where('direct_business_required','>',0)->count();
        $generation_business_required = $rewards->where('generation_business_required','>',0)->count();
        
        
        //$check = $rewards->where('direct_required','>',0);
        
        
        return view('pages.reward',['rewards'=>$rewards,'my_rewards'=>$my_reward,'direct_required'=>$direct_required,'generation_team_required'=>$generation_team_required,'self_business_required'=>$self_business_required,'direct_business_required'=>$direct_business_required,'generation_business_required'=>$generation_business_required]);
    }

    public function calculateReward()
    {
        $rewards = PlanReward::all();
        $user = Auth::user();
        $activeDirects = $user->activeDirects;
       

        $gen_tm = $user->team->gen;
     
        $active_Team = Team::whereIn('user_id',$gen_tm)->where('active_status',1)->get();
      
  

        if ($rewards) {
            foreach ($rewards as  $reward) {
                $user_reward = UserReward::where('user_id',Auth::user()->id)->where('plan_reward_id',$reward->id)->first();
                if (!$user_reward) {
                    $direct_required = $reward->direct_required;
                    $generation_team_required = $reward->generation_team_required;
                 
                    $direct_status = $activeDirects->count()>=$direct_required ? 1 : 0;
                    $gen_status = $active_Team->count()>=$generation_team_required ? 1 : 0;
  
                    
                    if ($direct_status == 1 && $gen_status == 1 ) {
                        $nreward = new UserReward();
                        $nreward->user_id = $user->id;
                        $nreward->plan_reward_id = $reward->id;
                        $nreward->reward = $reward->reward;
                        $nreward->save();
                    }                    
                }
            }
        }
    }
    
    
    ////////////////////////////////////////////////////Api Start here //////////////////////////////////////////////////////////////////////////////////
    public function  getReward(){
        $rewards = PlanReward::all();
      
        //$this->calculateReward();
        $userRewards = UserReward::where('user_id', Auth::user()->id)->get();
   
        // Loop through all rewards and adjust status based on user data
        foreach ($rewards as $reward) {
            // Check if the user has achieved this reward
            $userReward = $userRewards->where('plan_reward_id', $reward->id)->first();
        
            // Determine status based on whether the user has achieved the reward
            $status = $userReward ? 'Achieved' : 'Pending';
        
            // Adjust the status of the reward
            $reward->user_status = $status;
         
        }
        
        // Include only the $rewards variable in the response
        $response = [
            'rewards' => $rewards,
        ];
        
        return response()->json($response, 200);
    }
    public function  TestgetReward(){
        $rewards = PlanReward::all();
        $this->calculateReward();
        
        $userRewards = UserReward::where('user_id', Auth::user()->id)->get();
        $leg_team = Team::teamByLeg(Auth::user()->id);
        
        // print_r($leg_team);
        // die();
        $max_leg = !empty($leg_team) ? $leg_team[0]:0;
        $all_leg = !empty($leg_team) ? array_sum($leg_team):0;
        $other_leg = $all_leg-$max_leg;
        print_r($other_leg);
        die();
        
        // Loop through all rewards and adjust status based on user data
        foreach ($rewards as $reward) {
            // Check if the user has achieved this reward
            $userReward = $userRewards->where('plan_reward_id', $reward->id)->first();
        
            // Determine status based on whether the user has achieved the reward
            $status = $userReward ? 'Achieved' : 'Pending';
        
            // Adjust the status of the reward
            $reward->user_status = $status;
            $reward->pending_left_team_required = $reward->left_team_required - $max_leg;
           
            $reward->pending_right_team_required = $reward->right_team_required - $other_leg;
            if($max_leg >= $reward->left_team_required){
                $reward->pending_left_team_required = 30;
            }
               print_r($reward->pending_left_team_required);
        die();
        }
       
        // Include only the $rewards variable in the response
        $response = [
            'rewards' => $rewards,
        ];
        
        return response()->json($response, 200);
    }
    
    ////////////////////////////////////////////////////Api End here ///////////////////////////////////////////////////////////////////////////////////



    public function  getMonthIncentive(){
        $rewards = PlanMonth::all();
           foreach ($rewards as $reward) {
           
            $status =  'Pending';
        
            // Adjust the status of the reward
            $reward->user_status = $status;
         
        }
        
        // Include only the $rewards variable in the response
        $response = [
            'rewards' => $rewards,
        ];
        
        return response()->json($response, 200);
    }
    public function  getLoan(){
        $rewards = [
            "10 Direct"  => "20000",
            "20 Direct"  => "50000",
            "30 Direct"  => "100000",
            "50 Direct"  => "500000",
            "100 Direct" => "1000000",
        ];
        
        // Convert into array of objects with name, value, and status
        $formattedRewards = [];
        foreach ($rewards as $name => $value) {
            $formattedRewards[] = [
                'name'   => $name,
                'value'  => $value,
                'status' => 'Pending', // you can also fetch from DB if dynamic
            ];
        }
        
        $response = [
            'rewards' => $formattedRewards,
        ];
        
        return response()->json($response, 200);
    }
}
