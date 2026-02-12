<?php

namespace App\Helper;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Team;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\Investment;
use App\Models\Income;
use App\Models\Order;
use App\Models\PlanDirect;
use App\Models\EbikeAchiver;
use App\Models\RoyaltyEbikeAchiver;
use App\Models\LoanInvestment;
use App\Models\PlanInvestLevelIncome;
use App\Models\EbikeInvestment;
use App\Models\PlanRechargeReferralIncome;
use App\Models\RechargeInvestment;
use App\Models\PlanTrip;
use App\Models\PlanEbikeRoyalty;
use App\Models\PlanLoanDirectIncome;
use App\Models\PlanRechargeRoyalty;
use App\Models\PlanReferralEcom;
use App\Models\RechargeTripAchiver;
use App\Models\RechargeRoayltyAchiver;
use App\Models\Setting;
use App\Models\Loan;
use App\Models\LoanList;
use App\Models\PlanRefferalIncome;
use App\Models\PlanEbikeReferraIncome;
use App\Models\PlanLevel;
use App\Models\PlanRoiLevel;
use App\Models\DailyIncome;
use App\Models\JackpotDrawParticipate;
use App\Models\PlanGoldDirect;
use App\Models\PlanGoldReferral;
use App\Models\GoldInvestment;
use App\Models\EbikeBinaryClosing;
use App\Models\TourInvestment;
use App\Models\TourBinaryClosing;
use App\Models\PlanTourReferralIncome;
use App\Models\PlanEliteReferral;
use App\Models\EliteInvestment;
use App\Models\EliteBinaryClosing;
use App\Models\FlyInvestment;
use App\Models\Autopool;
use App\Models\PlanAutopool;
use App\Models\PlanRechargeCommision;
use Carbon\Carbon;

class Distribute
{
    
    
    //////////////////prime Package start here /////////////////////
    public static function DirectIncome($data) {
    

        if($data->pacakge == '1'){
            $level_commsionall=DB::table('plan_directs')->where('type','prime')->get();
        }else{
            $level_commsionall=DB::table('plan_directs')->where('type','super')->get();
        } 
    $userData = User::where('id',$data->user_id)->first();
    $username= $userData->username;
    $name= $userData->name;
    $mobile= $userData->mobile;
    $sponser= DB::table('teams')->where('user_id', '=',  $data->user_id)->value('sponsor');
    // $isMember = ;
    //$sponser = $userData->user_id;
           
    if (!empty($sponser)) {

       for ($i=1; $i <= count($level_commsionall); $i++) { 
  
          if($sponser){
             
       
            $active_sponsor = User::where('id',$sponser)->value('active_status');
      
             if($active_sponsor == 1){

                 if($data->package_id == '1'){
                          $level_commsion = PlanDirect::where('level',$i)->where('type','prime')->where('status',1)->first();   
                        }else{
                            $level_commsion=PlanDirect::where('level',$i)->where('type','super')->where('status',1)->first();   ;
                        }
                
                 
            
            if ($level_commsion) {
             
                $wallet = $level_commsion->wallet;
                
                $comm = $level_commsion->commision_type=='percent' ? $data->amount*$level_commsion->value/100:$level_commsion->value;
             
      
                if($comm>0){
                    
                    Transaction::create([
                        'user_id' => $sponser, 
                         'tx_id' => $data->user_id,
                        'type' => 'credit',
                        'tx_type' => 'income',
                        'wallet' => $wallet->slug,
                        'income' => $level_commsion->source,
                        'status' => 1,                        
                        'amount' => $comm,
                        'remark' => "Receive Direct of amount Rs $comm from purchasing of  pakage $name $mobile."
                    ]);
                    Wallet::where('user_id',$sponser)->increment($wallet->slug,$comm);
                    Income::where('user_id',$sponser)->increment($level_commsion->source,$comm);                 
                    DailyIncome::where('user_id',$sponser)->increment($level_commsion->source,$comm);                 
                }               
                
                // $sponser= DB::table('teams')
                //   ->where('user_id', '=',  $sponser)
                //   ->value('sponsor'); 
              }                 
          }
          }
        }      

        }
          

     }



 
    


    public static function LevelIncome($data) {
    if($data->pacakge == '1'){
        $level_commsionall=DB::table('plan_levels')->where('type','prime')->where('status',1)->get();

    }else{
        $level_commsionall=DB::table('plan_levels')->where('type','super_prime')->where('status',1)->get();
    }

    
    $sponser = Team::where('user_id', '=',  $data->user_id)->value('sponsor');
    $userData = User::where('id',$data->user_id)->first();
    $username= $userData->username;
    // $isMember = ;
   //DB::table('tests')->insert(['remark'=>$sponser]);
    if ($sponser) {
          
       for ($i=1; $i <= count($level_commsionall); $i++) { 
          if($sponser){
            $active_sponsor = User::where('id',$sponser)->value('active_status');
     
            // DB::table('tests')->insert(['remark'=>$active_sponsor]);
           

         
            if($data->package_id == 1){
                $level_commsion = PlanLevel::where('level',$i)->where('type', 'prime')->first();
 
            }else{
            
                $level_commsion = PlanLevel::where('level',$i)->where('type','super_prime')->first();
            }

           
            if ($level_commsion) {
              
                $wallet = $level_commsion->wallet;
            
                $comm = $level_commsion->commision_type=='percent' ? $data->amount*$level_commsion->value/100:$level_commsion->value;
             
                if($comm>0){
                    Transaction::create([
                        'user_id' => $sponser,
                        'tx_user' => $data->user_id,
                        'tx_id' => $data->id,
                        'type' => 'credit',
                        'tx_type' => 'income',
                        'wallet' => $wallet->slug,
                        'income' => $level_commsion->source,
                        'status' => 1,                        
                        'amount' => $comm,
                        'level' => $i,
                        'remark' => "Receive $level_commsion->source $i Income of amount $comm from $username."
                    ]);
                    Wallet::where('user_id',$sponser)->increment($wallet->slug,$comm);
                    Income::where('user_id',$sponser)->increment($level_commsion->source,$comm);
                    DailyIncome::where('user_id',$sponser)->increment($level_commsion->source,$comm); 
                }               
                
                $sponser= DB::table('teams')
                  ->where('user_id', '=',  $sponser)
                  ->value('sponsor'); 
              }                 
          }
        }      

        }        

     } 
     
     


     public static function AutoPoolIncome($data) {
   
       $b_id= $p_id = $data->id;
        $p_id = Autopool::where('id',$b_id)->value('parent_id'); 
            
        if ($p_id != 0) {
            $source = 'autopool_'.$data->pool;
           
                $lvl = 7;

                   
           for ($i=1; $i <= $lvl; $i++) { 
             
              
              if($p_id){ 
             
         
                $userinfo = AutoPool::find($p_id);
                $userfrominfo = AutoPool::find($b_id);
                  
                $userData = User::where('id',$userfrominfo->user_id)->first();
                $username= $userData->username;
                $active_sponsor = User::where('id',$userinfo->user_id)->value('active_status');
          
               
                $level_commsion = PlanAutopool::where('level',$i)->where('status', 1)->first(); 
                if ($level_commsion) {
     
                    if($active_sponsor == 1){  
                
                            $sponsorInfo = User::find($userinfo->user_id);
                            $alreadyAchive = Transaction::where('user_id',$sponsorInfo->id)->where('level',$i)->where('income',$level_commsion->source)->count();
                                   
                        
                            if($alreadyAchive<=0){
                            
                                $ttl = planAutopool::where('level',$i)->where('status', 1)->first(); 
                        
                                $ttlcount =Self::getGen($p_id,$i,$data->pool); //AutoPool::where('parent_id',$p_id)->count();
                                $exists = Investment::where('user_id',$sponsorInfo->id)->exists();
                         
                                if($exists){
                                             
                                    if($ttlcount >= pow(2,$i)){
                                       
                                        
                                        $wallet = $level_commsion->wallet;
                                    
                                        $comm = $level_commsion->commision_type=='percent' ? $data->amount*$level_commsion->value/100:$level_commsion->value;
                                      
                        
                                        if($comm>0){
                                      $wallet = "main_wallet";
                                            $comission = $level_commsion->value;
                                            $remark = "Receive $level_commsion->source Income of amount $comission from $username.";
                                                   
                                            Transaction::create([
                                                'user_id' => $sponsorInfo->id,
                                                'tx_user' => $userData->id,
                                                'tx_id' => $data->id,
                                                'type' => 'credit',
                                                'tx_type' => 'income',
                                                'wallet' => $wallet,
                                                'income' => 'autopool',
                                                'status' => 1,                        
                                                'amount' => $comission,
                                                'level' => $i,
                                                'remark' => $remark
                                            ]);
         
                                     
                                            Wallet::where('user_id',$sponsorInfo->id)->increment($wallet,$comission);
                                            Income::where('user_id',$userinfo->id)->increment($level_commsion->source,$comission);
                                            DailyIncome::where('user_id',$userinfo->id)->increment($level_commsion->source,$comission); 
                                        }   

                                    }
                                }
                           
                            
                            }
                        }
                        
                        
                        $p_id = Autopool::where('id',$p_id)->value('parent_id');
                  }                 
              }
             
            }      
    
            }        
    
         }   



    public static function getGen($userid,$lvl,$pool){
            $teams = array($userid);
            $tm = array();
           // print_r($teams);
            for ($i=0; $i < $lvl; $i++) {
                if(!empty($teams)){
                    //print_r($teams);
                        
                    $teams = Autopool::whereIn('parent_id',$teams)->where('pool',$pool)->pluck('id')->toArray();
                    
                }
            }
           
            if(!empty($teams)){
                $ttlteams = count($teams);
            }else{
                $ttlteams = 0;
            }
            return $ttlteams;
        }
 
    public static function RoiLevelIncome($data) {
    
    $level_commsionall=DB::table('plan_roi_levels')->get(); 
    
    $sponser= DB::table('teams')->where('user_id', '=',  $data->tx_user)->value('sponsor');
    $userData = User::where('id',$data->tx_user)->first();
    $username= $userData->username;
    // $isMember = ;
    $direct_req = [0,1,1,1,2,2,2,3,3,3,4,4,4,5,5,5,6,6,6,7,7,7,7];
    if (!empty($sponser)) {
        
       for ($i=1; $i <= count($level_commsionall); $i++) { 
          if($sponser){
            $level_commsion = PlanRoiLevel::where('level',$i)->where('status',1)->first();
            $directs= DB::table('teams')->where('sponsor',  $sponser)->where('active_status',  1)->get()->count();
            if ($level_commsion) {
               //DB::table('tests')->insert(['remark'=> json_encode($data)]);
                $wallet = $level_commsion->roi->wallet;
                
                $comm = $level_commsion->commision_type=='percent' ? $data->amount*$level_commsion->value/100:$level_commsion->value;
                
                if($comm>0){
                    $useri=User::where('id',"$sponser")->value('active_status');
                    //print_r();
                    //DB::table('tests')->insert(['remark'=>$useri]);
                    if($directs>=$direct_req[$i]){
                    if($useri->value == 1){
                        Transaction::create([
                            'user_id' => $sponser,
                            'tx_user' => $data->tx_user,
                            'tx_id' => $data->id,
                            'type' => 'credit',
                            'tx_type' => 'income',
                            'wallet' => $wallet->slug,
                            'income' => $level_commsion->source,
                            'status' => 1,                        
                            'amount' => $comm,
                            'level' => $i,
                            'remark' => "Receive Refferal Income of amount $comm from $username",
                        ]);
                        
                            Wallet::where('user_id',$sponser)->increment($wallet->slug,$comm);
                            Income::where('user_id',$sponser)->increment($level_commsion->source,$comm);
                            DailyIncome::where('user_id',$sponser)->increment($level_commsion->source,$comm); 
                        }
                    }
                }               
                
                $sponser= DB::table('teams')
                  ->where('user_id', '=',  $sponser)
                  ->value('sponsor'); 
                 // DB::table('tests')->insert(['remark'=>$sponser]);
              }                 
          }
        }      

        }        

     }   
 
    public static function RechargeIncome($data) {

     
    $userData = User::where('id',$data->user_id)->first();
       $type =  $data->recharge_type;
       $amount =  $data->amount; 
       $self_status = $userData->active_status;
    if($self_status == 1){ 
        $level_commsionall=DB::table('plan_recharge_commisions')->where('type','paid')->get();
        $selfpay = ($amount*$level_commsionall[0]->$type/100) + 1;

    }else{
        $level_commsionall=DB::table('plan_recharge_commisions')->where('type','free')->get(); 
        $selfpay = $level_commsionall[0]->$type;

    }
 
            Transaction::create([
                'user_id' => $data->user_id, 
                'tx_user' => $data->user_id, 
                'type' => 'credit',
                'tx_type' => 'income',
                'wallet' => 'main_wallet',
                'income' => 'self_recharge',
                'status' => 1,                        
                'amount' => $selfpay,
                'remark' => "Receive Self Recharge income of amount Rs $selfpay from purchasing of a Recharge."
            ]);
            Wallet::where('user_id',$data->user_id)->increment($data->wallet_type,$selfpay);
            Income::where('user_id',$data->user_id)->increment('self_recharge',$selfpay);                 
            DailyIncome::where('user_id',$data->user_id)->increment('self_recharge',$selfpay);     

     
    $sponser= DB::table('teams')->where('user_id', '=',  $data->user_id)->value('sponsor');
    $userData = User::where('id',$data->user_id)->first();
    $username= $userData->username;
    $name= $userData->name;
    $mobile= $userData->mobile;
   
    // $isMember = ;
    if (!empty($sponser)) {
       for ($i=1; $i <= count($level_commsionall); $i++) { 
          if($sponser){
            if($self_status == 1){
                $level_commsion = PlanRechargeCommision::where('level',$i)->where('type','paid')->where('status',1)->first();
                $active_sponsor = User::where('id',$sponser)->value('active_status');
            }else{
                $active_sponsor = 1;
                  $level_commsion = PlanRechargeCommision::where('level',$i)->where('type','free')->where('status',1)->first();
            } 

          if($active_sponsor == 1){
              
          
             
            if ($level_commsion) {
              
                $wallet = $data->wallet_type;
                 
                $comm = $data->amount*$level_commsion->$type/100;
            
                if($comm>0){
                    
                    Transaction::create([
                        'user_id' => $sponser, 
                        'tx_user' => $data->user_id, 
                        'type' => 'credit',
                        'tx_type' => 'income',
                        'wallet' => 'main_wallet',
                        'income' => 'recharge_level',
                        'status' => 1,                        
                        'tx_id' => $i,                        
                        'amount' => $comm,
                        'remark' => "Receive Recharge Level $i income of amount Rs $comm from purchasing of a Recharge."
                    ]);
               
                    Wallet::where('user_id',$sponser)->increment('main_wallet',$comm);
                    Income::where('user_id',$sponser)->increment('recharge_level',$comm);                 
                    DailyIncome::where('user_id',$sponser)->increment('recharge_level',$comm);                 
                }               
                
                $sponser= DB::table('teams')
                  ->where('user_id', '=',  $sponser)
                  ->value('sponsor'); 
            }
              }                 
          }
        }      

        }
         

     }
     
    //////////////////Ecommerce start here //////////////////////////// 
    public static function EcomReferralIncomeOld($data) {
    
    $level_commsionall=DB::table('plan_referral_ecoms')->get(); 
    $userData = User::where('id',$data->user_id)->first();
    $username= $userData->username;
    $name= $userData->name;
    $mobile= $userData->mobile;
    $sponser= DB::table('teams')->where('user_id', '=',  $data->user_id)->value('sponsor');
    // $isMember = ;
    //$sponser = $userData->user_id;
    if (!empty($sponser)) {
       for ($i=1; $i <= count($level_commsionall); $i++) { 
          if($sponser){
            $active_sponsor = User::where('id',$sponser)->value('active_status');
             //if($active_sponsor->value == 1){
                $level_commsion = PlanReferralEcom::where('level',$i)->where('status',1)->first();
            // } 
             
            if ($level_commsion) {
              
                $wallet = $level_commsion->wallet;
                
                $comm = $level_commsion->commision_type=='percent' ? $data->total*$level_commsion->value/100:$level_commsion->value;
           
                if($comm>0){
                    
                    Transaction::create([
                        'user_id' => $sponser, 
                        'type' => 'credit',
                        'tx_type' => 'income',
                        'wallet' => $wallet->slug,
                        'income' => $level_commsion->source,
                        'status' => 1,                        
                        'amount' => $comm,
                        'remark' => "Receive Ecommerce Referral income of amount Rs $comm from purchasing of a Product $name $mobile."
                    ]);
                    Wallet::where('user_id',$sponser)->increment($wallet->slug,$comm);
                    Income::where('user_id',$sponser)->increment($level_commsion->source,$comm);                 
                    DailyIncome::where('user_id',$sponser)->increment($level_commsion->source,$comm);                 
                }               
                
                // $sponser= DB::table('teams')
                //   ->where('user_id', '=',  $sponser)
                //   ->value('sponsor'); 
              }                 
         // }
          }
        }      

        }
         

     }
     
   public static function EcomReferralIncome($data) {
        $lvl = 10;
        $level_commsionall=DB::table('plan_referral_ecoms')->get(); 
        $userData = User::where('id',$data->user_id)->first();
        $username= $userData->username;
        $directRequired = 1;
        $name= $userData->name;
        $mobile= $userData->mobile;
        $sponser= DB::table('teams')->where('user_id', '=',  $data->user_id)->value('sponsor');
        // $isMember = ;
        //$sponser = $userData->user_id;
            if (!empty($sponser)) {
                for ($i=1; $i <= $lvl; $i++) { 
                    if($sponser){
                        $sponsorInfo = User::where('id',$sponser)->first();
                        $active_sponsor = Order::where('user_id',$sponser)->value('status');
                        if($active_sponsor == 1){
                        //$ttlDirectsCount = $sponsorInfo->directs;
                      ///  $ttlDirectsCount = $sponsorInfo->activeDirects;
                        $ttlDirectsCount = Team::where('sponsor', $sponser)
                        ->whereHas('ebikeInvestments', function ($query) {
                            $query->where('status', '1'); // Assuming 'status' field indicates active investment
                        })->get();
                        $lvl_number = 0;
                        $directInfo = $ttlDirectsCount->get($lvl_number);
                       
                        if($i ==  1){
                            $cappIncome = 0;
                            $directRequired = 0;
                        }else{ 
                            $directRequired =$i; 
                            $lvl_number = $directRequired - 1;
                            $directInfo = $ttlDirectsCount->get($lvl_number);
                            if($directInfo){
                                $cappIncome = Order::where('user_id',$directInfo->user_id)->sum('subtotal'); 
                            }else{
                                $cappIncome = 0;
                            }
                        } 
                        //if($active_sponsor->value == 1){
                            $level_commsion = PlanReferralEcom::where('status',1)->first();
                        // } 


                                            if($cappIncome > 0){
                                            if(count($ttlDirectsCount) >= $directRequired){
                                                //$DailyselfSum = Income::where('user_id', $sponsorInfo->user_id)->value('ecom_referral');
                                                $DailyselfSum = Transaction::where('user_id', $sponsorInfo->id)->where('income','ecom_referral')->where('level',$i)->value('amount');
                                                if($DailyselfSum < $cappIncome){ 
                                        
                                                    if ($level_commsion) { 
                                                        $wallet = $level_commsion->wallet;
                                                        $comm = $level_commsion->commision_type=='percent' ? $data->subtotal*$level_commsion->value/100:$level_commsion->value;
                                                        $ttl = $comm + $DailyselfSum;
                                                        if($cappIncome >= $ttl){
                                                            $comm =$ttl - $cappIncome;
                                                        }else{
                                                            $comm = $cappIncome; 
                                                        }
                                                    
                                                        if($comm>0){
                                                            
                                                            Transaction::create([
                                                                'user_id' => $sponser, 
                                                                'type' => 'credit',
                                                                'tx_id' => $data->id,
                                                                'tx_type' => 'income',
                                                                'wallet' => $wallet->slug,
                                                                'income' => $level_commsion->source,
                                                                'status' => 1,                        
                                                                'level' => $i,                        
                                                                'amount' => $comm,
                                                                'remark' => "Receive Ecommerce Referral income of amount Rs $comm from purchasing of a Product ."
                                                            ]);
                                                            Wallet::where('user_id',$sponser)->increment($wallet->slug,$comm);
                                                            Income::where('user_id',$sponser)->increment($level_commsion->source,$comm);                 
                                                            DailyIncome::where('user_id',$sponser)->increment($level_commsion->source,$comm);                 
                                                        }               
                                                    
                                                
                                                }                 
                                        // }
                                        }
                                        }  
                                    }
                    } 
                    
                            $sponser= DB::table('teams')
                            ->where('user_id', '=',  $sponser)
                            ->value('sponsor'); 
                                if($lvl == $i){
                                $lvl++;
                                }
                    }
        
                }
            } 
     }
    //////////////////Ecommerce end here ////////////////////////////
     
    //////////////////Loan start here ////////////////////////////
    public static function loanDistribution(){
        $allPackageUsers = LoanInvestment::where('status',1)->get(); 
         
        foreach($allPackageUsers as $user){
            $userId = $user->user_id;
            $userInfo  = User::where('id',$userId)->first();
            
            // if($userInfo->active_status->value == '1'){
              $loanExists = Loan::where('user_id',$userId)->where('status',1)->orderBy('created_at', 'desc')->first(); 
               if($loanExists){ 
                     $previousLoan = Loan::where('user_id', $userId)->where('status', 1)->where('id', '<', $loanExists->id)->first(); 
                     $mydirs = User::find($userId)->directs; 
                  
               }else{
                
                   ///////////////////// for 1st time loan approve //////////////////////////////
                 $Loaninfo = LoanList::where('id','1')->where('status',1)->first();
                 $requiredDirects = $Loaninfo->direct_required;
                 $mydirs = User::find($userId)->directs;
                 $userIds = $mydirs->pluck('user_id'); 
                 $activeLoanCount = LoanInvestment::whereIn('user_id', $userIds)
                        ->where('status', 1)
                        ->distinct('user_id')
                        ->count('user_id'); 
                        
                       $amnt =  $Loaninfo->loan_amnt;
                 if($activeLoanCount >= 3){ 
                       Loan::create([
                            'user_id' => $userId,
                            'loan_id' => $Loaninfo->id,
                            'amount' => $Loaninfo->loan_amnt,
                            'wallet' => 'main_wallet', 
                            'remark' => "Loan  $amnt Rs Credit to Your E WAllet",
                            'loan_status' => 'Payable',                        
                            'status' => 1,  
                        ]); 
                        Wallet::where('user_id',$userId)->increment('main_wallet',$Loaninfo->loan_amnt); 
                 }
                    
               } 
                
            // }
        }   
     }
     
    public static function DirectLoanIncome($data) {
    
        $level_commsionall=DB::table('plan_loan_direct_incomes')->get(); 
        $userData = User::where('id',$data->tx_user)->first();
        $username= $userData->username;
        $name= $userData->name;
        $mobile= $userData->mobile;
        $sponser= DB::table('teams')->where('user_id', '=',  $data->tx_user)->value('sponsor');
        // $isMember = ;
        //$sponser = $userData->user_id;
        if (!empty($sponser)) {
           for ($i=1; $i <= count($level_commsionall); $i++) { 
              if($sponser){
                $active_sponsor = User::where('id',$sponser)->value('active_status');
                 if($active_sponsor->value == 1){
                    $level_commsion = PlanLoanDirectIncome::where('level',$i)->where('status',1)->first();
                // } 
                 
                if ($level_commsion) {
                  
                    $wallet = $level_commsion->wallet;
                    
                    $comm = $level_commsion->commision_type=='percent' ? $data->amount*$level_commsion->value/100:$level_commsion->value;
               
                    if($comm>0){
                        
                        Transaction::create([
                            'user_id' => $sponser, 
                            'tx_user' => $data->tx_user, 
                            'type' => 'credit',
                            'tx_type' => 'income',
                            'wallet' => $wallet->slug,
                            'income' => $level_commsion->source,
                            'status' => 1,                        
                            'amount' => $comm,
                            'remark' => "Receive Loan Direct of amount Rs $comm from purchasing of Loan Investment."
                        ]);
                        Wallet::where('user_id',$sponser)->increment($wallet->slug,$comm);
                        Income::where('user_id',$sponser)->increment($level_commsion->source,$comm);                 
                        DailyIncome::where('user_id',$sponser)->increment($level_commsion->source,$comm);                 
                    }               
                    
                    // $sponser= DB::table('teams')
                    //   ->where('user_id', '=',  $sponser)
                    //   ->value('sponsor'); 
                  }                 
              }
              }
            }      

        }
         

     }
     
    public static function LoanBoosterIncome($data){ 
        $userData = User::where('id',$data->tx_user)->first();
        $username= $userData->username;
        $name= $userData->name;
        $mobile= $userData->mobile;
        $sponser= DB::table('teams')->where('user_id', '=',  $data->tx_user)->value('sponsor');
            // $isMember = ;
            //$sponser = $userData->user_id;
        if (!empty($sponser)) {
            for ($i=1; $i >= 0; $i++) { 
                if($sponser){
                    $active_sponsor = LoanInvestment::where('user_id',$sponser)->where('status',1)->first();
                    if($active_sponsor){
                        
                        if($i >= 4){
                               $directs = Team::where('sponsor', $sponser)
                    ->whereHas('loanInvestments', function ($query) {
                        $query->where('status', '1'); // Assuming 'status' field indicates active investment
                    })
                    ->pluck('user_id')
                    ->toArray();
                    
                        if(count($directs)>=$i){


                            $wallet = 'main_wallet';
                            
                            $comm = $data->amount*10/100;
                    
                            if($comm>0){
                                
                                Transaction::create([
                                    'user_id' => $sponser, 
                                    'tx_user' => $data->tx_user, 
                                    'type' => 'credit',
                                    'tx_type' => 'income',
                                    'wallet' => $wallet,
                                    'income' => "loan_booster_level",
                                    'status' => 1,     
                                    'level' => $i,
                                    'amount' => $comm,
                                    'remark' => "Receive Loan Level of amount Rs $comm from purchasing of Loan investment."
                                ]);
                                Wallet::where('user_id',$sponser)->increment($wallet,$comm);
                                Income::where('user_id',$sponser)->increment('loan_booster_level',$comm);                 
                                DailyIncome::where('user_id',$sponser)->increment('loan_booster_level',$comm);                 
                            }            

                        }
                            
                        } 
                        
                    }
                        
                        
                        $sponser= DB::table('teams')
                        ->where('user_id', '=',  $sponser)
                        ->value('sponsor'); 
                                    
                }else{
                    break;
                }
            }      

        }
         

     } 
     
    //////////////////Loan  end here ////////////////////////////
     
    //////////////////Ebike start here //////////////////////////// 
     
    public static function IsEbikeEligibleOld(){
         
         $allEbikeInvesters = EbikeInvestment::where('status',1)->get();
         foreach($allEbikeInvesters as $invester){
             $userId = $invester->user_id;
             
                $all_PaidUsers = Team::teamByEbikeBusiness($userId);   
                if(!empty($all_PaidUsers)){
                    
                    $nonZeroCount = 0; 
                    foreach ($all_PaidUsers as $key => $value) {
                        if ($value != 0 && $value >= 0 ) { 
                            $nonZeroCount++;
                            if ($nonZeroCount >= 3) {
                                break;  // No need to continue checking if we've found three non-zero values
                            }
                        }
                    } 
                    
                    $atLeastThreeNonZero = $nonZeroCount >= 3;
                    if($atLeastThreeNonZero == true){
                        
                        
                        EbikeAchiver::create([
                            'user_id' => $userId,
                            'amount' => $invester->amount,
                            'status' => 1, 
                            ]);
                        $user = User::where('id',$userId)->first();
                        $user->ebike_eligible = 1;
                        $user->save();
                    }
                }
                
         }
         
         
     }
     
    public static function IsEbikeEligible(){
         
        $allEbikeInvesters = EbikeInvestment::where('status',1)->get();
        foreach($allEbikeInvesters as $invester){
             $userId = $invester->user_id;
           
             $userinfo = User::where('id',$userId)->first();
          
             $directs = Team::where('sponsor', $userId)
             ->whereHas('ebikeInvestments', function ($query) {
                 $query->where('status', '1'); // Assuming 'status' field indicates active investment
             })
             ->pluck('user_id')
             ->toArray();
          

             if(count($directs) >= 12){

                $alreadyExists = EbikeAchiver::where('user_id',$userId)->first();
                       if(!$alreadyExists){
                           EbikeAchiver::create([
                            'user_id' => $userId,
                            'amount' => $invester->amount,
                            'status' => 1, 
                            ]);
                            $user = User::where('id',$userId)->first();
                            $user->ebike_eligible = 1;
                            $user->save();
                       }

             }else{


                $all_PaidUsers = Team::teamByEbikeAchivers($userId);   
               
                if(!empty($all_PaidUsers)){
                    
                    $nonZeroCount = 0; 
                    foreach ($all_PaidUsers as $key => $value) {
                     
                        if ($value != 0 && $value >= 0 ) { 
                            $nonZeroCount++;
                            if ($nonZeroCount >= 1) {
                                break;  // No need to continue checking if we've found three non-zero values
                            }
                        }
                    } 
                  
                    $atLeastOneNonZero = $nonZeroCount >= 1;
                    if($atLeastOneNonZero == true){
                 
                       $alreadyExists = EbikeAchiver::where('user_id',$userId)->first();
                       if(!$alreadyExists){
                           EbikeAchiver::create([
                            'user_id' => $userId,
                            'amount' => $invester->amount,
                            'status' => 1, 
                            ]);
                            $user = User::where('id',$userId)->first();
                            $user->ebike_eligible = 1;
                            $user->save();
                       }
                        
                    }
                }

             }
           
                
         }
         
         
     }
     
    public static function EbikeReferralIncome($data) {
    
    $level_commsionall=DB::table('plan_ebike_referra_incomes')->get(); 
    $userData = User::where('id',$data->tx_user)->first();
    $username= $userData->username;
    $name= $userData->name;
    $mobile= $userData->mobile;
    $sponser= DB::table('teams')->where('user_id', '=',  $data->tx_user)->value('sponsor');
    // $isMember = ;
    //$sponser = $userData->user_id;
    if (!empty($sponser)) {
       for ($i=1; $i <= count($level_commsionall); $i++) { 
          if($sponser){
            $active_sponsor = User::where('id',$sponser)->value('active_status');
            // if($active_sponsor->value == 1){
                $level_commsion = PlanEbikeReferraIncome::where('level',$i)->where('status',1)->first();
            // } 
             
            if ($level_commsion) {
              
                $wallet = $level_commsion->wallet;
                
                $comm = $level_commsion->commision_type=='percent' ? $data->amount*$level_commsion->value/100:$level_commsion->value;
           
                if($comm>0){
                    
                    Transaction::create([
                        'user_id' => $sponser, 
                        'tx_user' => $data->tx_user, 
                        'type' => 'credit',
                        'tx_type' => 'income',
                        'wallet' => $wallet->slug,
                        'income' => $level_commsion->source,
                        'status' => 1,                        
                        'amount' => $comm,
                        'remark' => "Receive Referral of amount Rs $comm from purchasing of E-bike Booking $name $mobile."
                    ]);
                    Wallet::where('user_id',$sponser)->increment($wallet->slug,$comm);
                    Income::where('user_id',$sponser)->increment($level_commsion->source,$comm);                 
                    DailyIncome::where('user_id',$sponser)->increment($level_commsion->source,$comm);                 
                }               
                
                $sponser= DB::table('teams')
                  ->where('user_id', '=',  $sponser)
                  ->value('sponsor'); 
              }                 
          }
        }      

        }
         

    }
     
    public static function EbikeBoosterIncome($data){ 
        $userData = User::where('id',$data->tx_user)->first();
        $username= $userData->username;
        $name= $userData->name;
        $mobile= $userData->mobile;
        $sponser= DB::table('teams')->where('user_id', '=',  $data->tx_user)->value('sponsor');
            // $isMember = ;
            //$sponser = $userData->user_id;
        if (!empty($sponser)) {
            for ($i=1; $i >= 0; $i++) { 
                if($sponser){
                    $active_sponsor = EbikeInvestment::where('user_id',$sponser)->where('status',1)->first();
                    if($active_sponsor){
                        
                        if($i >= 4){
                            
                             $directs = Team::where('sponsor', $sponser)
                    ->whereHas('ebikeInvestments', function ($query) {
                        $query->where('status', '1'); // Assuming 'status' field indicates active investment
                    })
                    ->pluck('user_id')
                    ->toArray();
                    
                        if(count($directs)>=$i){


                            $wallet = 'main_wallet';
                            
                            $comm = $data->amount*10/100;
                    
                            if($comm>0){
                                
                                Transaction::create([
                                    'user_id' => $sponser, 
                                    'tx_user' => $data->tx_user, 
                                    'type' => 'credit',
                                    'tx_type' => 'income',
                                    'wallet' => $wallet,
                                    'income' => "ebike_booster_level",
                                    'status' => 1,     
                                    'level' => $i,
                                    'amount' => $comm,
                                    'remark' => "Receive Ebike Booster Level of amount Rs $comm from purchasing of E-bike Investment."
                                ]);
                                Wallet::where('user_id',$sponser)->increment($wallet,$comm);
                                Income::where('user_id',$sponser)->increment('ebike_booster_level',$comm);                 
                                DailyIncome::where('user_id',$sponser)->increment('ebike_booster_level',$comm);                 
                            }            

                        }
                            
                        }

                       
                        
                        
                    }
                        
                        
                        $sponser= DB::table('teams')
                        ->where('user_id', '=',  $sponser)
                        ->value('sponsor'); 
                                    
                }else{
                    break;
                }
            }      

        }
         

     } 





     public static function ebikeDailyIncome(){
        $comm = 60;
        $investments = EbikeInvestment::all();

   
        if($investments){
            foreach($investments as $investment){

                if($investment->days < 100){

                    $user = User::where('id',$investment->user_id)->first();
                    Wallet::where('user_id',$investment->user_id)->increment('fund_wallet',$comm);
                    $closed_amount = $user->wallet->fund_wallet;
                    Transaction::create([
                        'user_id' => $investment->user_id, 
                        'tx_user' => $investment->user_id, 
                        'type' => 'credit',
                        'tx_type' => 'income',
                        'wallet' => 'fund_wallet',
                        'income' => "ebike_cashback",
                        'status' => 1,  
                        'tx_id' => $investment->id,  
                        'close_amount' => $closed_amount,  
                        'amount' => $comm,
                        'remark' => "Receive Ebike Cashback  of amount Rs 60 from purchasing of E-bike Investment."
                    ]);
                  
                    Income::where('user_id',$investment->user_id)->increment('ebike_booster_level',$comm);                 
                    DailyIncome::where('user_id',$investment->user_id)->increment('ebike_booster_level',$comm);                 
                    $investment->days += 1;
                    $investment->received_amnt += $comm;
                    $investment->save();
                }

                

            }
            
        }

     }


     public static function DitrubteBinaryIdAdd($data) {
   
        
        $sponser = Team::where('user_id', '=',  $data->tx_user)->value('sponsor');
        $userData = Team::where('user_id',$data->tx_user)->first();
       
        
        // $isMember = ;
       //DB::table('tests')->insert(['remark'=>$sponser]);
    
        if ($sponser) {
            
           while ($sponser) { 
         
             
                $sponsorInfo = User::where('id',$sponser)->first();
                $sponsorexists = EbikeInvestment::where('user_id',$sponser)->first();
                if($sponsorexists){
                    if($userData->position == '1'){
                        $sponsorInfo->left_ebike +=  1;
                    }else{
                        $sponsorInfo->rigth_ebike +=  1;
                    }
                    $sponsorInfo->save();

                 }
            
                $userData = Team::where('user_id',$sponser)->first();
              
                $sponser= DB::table('teams')
                ->where('user_id', '=',  $sponser)
                ->value('sponsor'); 
         
               
            }      
    
            }
                 

    }   
    



    public static function distrbuteEbikeBinaryMacth(){
        
        $investments = EbikeInvestment::all();
        if($investments){
     
            
            foreach($investments as $investment){
                $userId= $investment->user_id;
            
                $sponsorInfo = User::where('id',$userId)->first();
                

                $leftbusiness = $sponsorInfo->left_ebike;
                $rigthbusiness = $sponsorInfo->rigth_ebike;
                $matching = min($leftbusiness, $rigthbusiness);
                ///$goldmatching = $sponsorInfo->ebike_matching;
                //$active_sponsor = GoldInvestment::where('user_id',$sponser)->latest()->first();
               
                if($matching >= 8){
                    $number = 8;

                }else{
                    $number = $matching;
                }
                $sponsorInfo->ebike_matching = $matching;
                $sponsorInfo->save();
                    if($number > 0){
                         $exists = EbikeBinaryClosing::where('user_id',$userId)->where('invest_id',$investment->id)->where('macthing',$number)->exists();
                        if (!$exists) {
                           
                            EbikeBinaryClosing::create([
                                'user_id' => $userId,
                                'invest_id' => $investment->id,
                                'macthing' => $number,
                                'days' => 0,
                                'status' => '1',
                                ]); 
                        }
                    }
               
             
            }      
    
        }
                 




    }
    public static function distrbuteEbikeBinaryIncome(){
        
        $investments = EbikeBinaryClosing::all();
        if($investments){
     
            
            foreach($investments as $closing){
                $days = $closing->days;
                if($days < 100){

                    $UserId= $closing->user_id;
                   
                    $sponsorInfo = User::where('id',$UserId)->first();
                    $number =  $closing->macthing;

                   
     
                    if($number >= 8){
                        $amnt = 800;
    
                    }else{
                        $amnt = $number * 100;
                    }
                    $comm =$amnt;

                    $previousNumber = EbikeBinaryClosing::where('user_id', $UserId)
                    ->where('invest_id', $closing->invest_id)
                    ->where('macthing', '<', $number)
                    ->orderBy('id', 'desc') // Assuming you want the most recent record
                    ->value('macthing'); 
                    if($previousNumber > 0){
                  
                        $comm =  $amnt - ($previousNumber * 100);

                    }
             
                     
               
                    $investmentinfo = EbikeInvestment::where('user_id',$UserId)->where('id',$closing->invest_id)->first();
      
                        if($comm>0){
    
                            Wallet::where('user_id',$sponsorInfo->id)->increment('fund_wallet',$comm); 
                            $closingWallet = $sponsorInfo->wallet->fund_wallet;
                            Transaction::create([
                                'user_id' => $sponsorInfo->id,  
                                'tx_user' => $sponsorInfo->id,
                                'type' => 'credit',
                                'tx_type' => 'income',
                                'wallet' => 'fund_wallet',
                                'income' => 'ebike_binary',
                                'status' => 1,                        
                                'amount' => $comm,
                                'close_amount'  => $closingWallet,
                                'tx_id'  => $closing->invest_id,
                                'remark' => "Receive E-bike binary matching of amount Rs $comm"
                            ]);
                            $closing->days += 1;
                            $closing->save(); 
                            $investmentinfo->received_amnt += $comm;
                            $investmentinfo->save();
                          
                            Income::where('user_id',$sponsorInfo->id)->increment('ebike_binary',$comm);                 
                            DailyIncome::where('user_id',$sponsorInfo->id)->increment('ebike_binary',$comm);   
    
    
                        }
                   
                       
                   
                }   

                }
           
    
            }        
    }
     
     //////////////////Ebike  end here ////////////////////////////

    public static function RoyaltyDistribution(){
        
        $Ebikesinvestemnts =  EbikeInvestment::where('status',1)->get();
        if($Ebikesinvestemnts){
            foreach($Ebikesinvestemnts as $investemnt){
                $userId  = $investemnt->user_id;
                $userInfo = User::where('id',$userId)->first();
                 $gen = $userInfo->team->gen;
                $activeDate = $userInfo->active_date; 
                // Calculate days from active_date to today
                $activeDate = Carbon::parse($activeDate);
                $today = Carbon::now();
                
                $daysCount = $activeDate->diffInDays($today);
            
                 $getRoyalty = PlanEbikeRoyalty::where('status',1)->where('id','!=','1')->get();
                  if($getRoyalty){
                      foreach($getRoyalty as $royalty){
                          
                            $daysRequired = $royalty->Commission_limit;
                            if($daysCount <= $daysRequired){
                              
                              
                              
                                $teamRequired = $royalty->team_required;
                                $level = $royalty->level;
                                for ($i = 0; $i < $level; $i++) {
                                    if ($gen) {
                                        $teams = Team::whereIn('sponsor', $gen)->pluck('user_id')->toArray(); // Convert to array
                                        $lvlteam = $teams; 
                                        $lvlTeam = count($lvlteam);
                                    }  
                                }
                                
                                if($lvlTeam >= $teamRequired){
                                    $userExists = RoyaltyEbikeAchiver::where('user_id',$userId)->where('status',1)->first();
                                    if($userExists){
                                        $userExists->royalty_id = $royalty->id;
                                        $userExists->amount = $royalty->value;
                                        $userExists->save;
                                        
                                    }else{
                                        RoyaltyEbikeAchiver::create([
                                                'user_id'=>$userId,
                                                'royalty_id'=>$royalty->id,
                                                'amount'=>$royalty->value,
                                                'status'=>1, 
                                        ]);
                                    }
                                }
                              
                          }    
                    }
                }     
            }
        }   
        
    }
    
    public static function RoyaltyIncomeDistribution(){
        
        $EbikesroyaltyAchievers =  RoyaltyEbikeAchiver::where('status',1)->get();
        if($EbikesroyaltyAchievers){
            foreach($EbikesroyaltyAchievers as $investemnt){
                    $userId  = $investemnt->user_id;
                    $userinfo = User::where('id',$userId)->first();
                    $mobile = $userinfo->mobile;
                    $amnt  = $investemnt->amount; 
                    $wallet = 'main_wallet';
                    $ttlTurnover = EbikeInvestment::where('status','1')->sum('amount');
                    $comm = $ttlTurnover*$amnt/100;
                    if($comm > 0){
                        Transaction::create([
                            'user_id' => $userId,
                            'tx_user' => $userId, 
                            'type' => 'credit',
                            'tx_type' => 'income',
                            'wallet' => $wallet,
                            'income' => 'ebike_royalty',
                            'status' => 1,                        
                            'amount' => $comm, 
                            'remark' => "Receive Ebike royalty Income of amount $comm to $mobile",
                        ]);
                            
                        Wallet::where('user_id',$userId)->increment($wallet,$comm);
                        Income::where('user_id',$userId)->increment($wallet,$comm);
                        DailyIncome::where('user_id',$userId)->increment($wallet,$comm);
                        
                    }  
                }     
        }
            
    }
    
    
    ////////////////////Recharge start start///////////////////////////
    public static function RechargeReferralIncome($data) {
     
     $userData = User::where('id',$data->user_id)->first(); 
     if($userData->package == 'prime'){
        $level_commsionall=DB::table('plan_recharge_referral_incomes')->where('package',1)->get(); 

     }else{
        $level_commsionall=DB::table('plan_recharge_referral_incomes')->where('package',2)->get();
     }
     
  
   
   
     
    $username= $userData->username;
    $name= $userData->name;
    $mobile= $userData->mobile;
    
    
    
    $sponser= DB::table('teams')->where('user_id', '=',  $data->user_id)->value('sponsor');
    // $isMember = ;
    //$sponser = $userData->user_id;

    if (!empty($sponser)) {

       for ($i=1; $i <= count($level_commsionall); $i++) { 
          if($sponser){
            $sponsorInfo = User::where('id',$sponser)->first();
          
            if($sponsorInfo->package != null){
                 

              $active_sponsor = User::where('id',$sponser)->value('active_status');
  
               if($sponsorInfo->package == 'prime'){ 
                $level_commsion = PlanRechargeReferralIncome::where('level',$i)->where('status',1)->where('package',2)->first();
               }else{
                $level_commsion = PlanRechargeReferralIncome::where('level',$i)->where('package',2)->where('status',1)->first();
               }
             
            if ($level_commsion) {
              
                $wallet = 'main_wallet';
                
                $comm = $level_commsion->commision_type=='percent' ? $data->amount*$level_commsion->value/100:$level_commsion->value;
                 
                if($comm>0){
                    
                    Transaction::create([
                        'user_id' => $sponser, 
                        'type' => 'credit',
                        'tx_type' => 'income',
                        'wallet' => $wallet,
                        'income' => $level_commsion->source,
                        'status' => 1,                        
                        'amount' => $comm,
                        'level' => $i,
                        'remark' => "Received Recharge Level  $i income of amount Rs $comm from Recharge of $mobile($name)."
                    ]);
                    Wallet::where('user_id',$sponser)->increment($wallet,$comm);
                    Income::where('user_id',$sponser)->increment($level_commsion->source,$comm);                 
                    DailyIncome::where('user_id',$sponser)->increment($level_commsion->source,$comm);                 
                }               
                
                $sponser= DB::table('teams')
                  ->where('user_id', '=',  $sponser)
                  ->value('sponsor'); 
              }                 
          }
          }
        }      

        }
         

     }
     
     
    public static function RechargeBoosterIncome($data){ 
        $userData = User::where('id',$data->tx_user)->first();
        $username= $userData->username;
        $name= $userData->name;
        $mobile= $userData->mobile;
        $sponser= DB::table('teams')->where('user_id', '=',  $data->tx_user)->value('sponsor');
            // $isMember = ;
            //$sponser = $userData->user_id;
        if (!empty($sponser)) {
            for ($i=1; $i>=0; $i++) { 
                if($sponser){
                    $active_sponsor = RechargeInvestment::where('user_id',$sponser)->where('status',1)->first();
                    if($active_sponsor){
                        
                        if($i >= 2){
                            
                                 $directs = Team::where('sponsor', $sponser)
                    ->whereHas('rechargeInvestments', function ($query) {
                        $query->where('status', '1'); // Assuming 'status' field indicates active investment
                    })
                    ->pluck('user_id')
                    ->toArray();
                    
                        if(count($directs)>=$i){


                            $wallet = 'main_wallet';
                            
                            $comm = $data->amount*10/100;
                    
                            if($comm>0){
                                
                                Transaction::create([
                                    'user_id' => $sponser, 
                                    'tx_user' => $data->tx_user, 
                                    'type' => 'credit',
                                    'tx_type' => 'income',
                                    'wallet' => $wallet,
                                    'income' => "recharge_booster_level",
                                    'status' => 1,     
                                    'level' => $i,
                                    'amount' => $comm,
                                    'remark' => "Receive Recharge Booster Level of amount Rs $comm from purchasing of Recharge Investment."
                                ]);
                                Wallet::where('user_id',$sponser)->increment($wallet,$comm);
                                Income::where('user_id',$sponser)->increment('recharge_booster_level',$comm);                 
                                DailyIncome::where('user_id',$sponser)->increment('recharge_booster_level',$comm);                 
                            }            

                        }
                            
                        }

                   
                        
                        
                    }
                        
                        
                        $sponser= DB::table('teams')
                        ->where('user_id', '=',  $sponser)
                        ->value('sponsor'); 
                                    
                }else{
                    break;
                }
            }      

        }
         

    } 
    
    public static function IsRechargeTripEligible(){
         
         $allEbikeInvesters = RechargeInvestment::where('status',1)->get();
         foreach($allEbikeInvesters as $invester){
             $userId = $invester->user_id;
             
            $latestRechargeTrip = RechargeTripAchiver::where('user_id', $userId)->where('status', 1)->orderBy('created_at', 'desc')->first();
            if($latestRechargeTrip){
                $nextTrip = PlanTrip::where('id', '>', $latestRechargeTrip->trip_id)->where('status', 1)->orderBy('id', 'asc')->first();
                $id = $nextTrip->id;
                $all_PaidUsers = Team::teamByRechargeTripBusiness($userId,$id);   
            }else{
                $nextTrip = 1;
                $nextTrip = PlanTrip::where('id',1)->where('status',$nextTrip)->first(); 
                $all_PaidUsers = Team::teamByRechargeBusiness($userId);   
            }
        
                
                if(!empty($all_PaidUsers)){
                    
                    $nonZeroCount = 0; 
                    foreach ($all_PaidUsers as $key => $value) {
                        if ($value != 0 && $value >= 0 ) { 
                            $nonZeroCount++;
                            if ($nonZeroCount >= 3) {
                                break;  // No need to continue checking if we've found three non-zero values
                            }
                        }
                    } 
                    
                    $atLeastThreeNonZero = $nonZeroCount >= 3;
                    if($atLeastThreeNonZero == true){
                        
                        
                        RechargeTripAchiver::create([
                            'user_id' => $userId,
                            'trip_id' => $nextTrip->id,
                            'trip_name' => $nextTrip->source,
                            'trip_time' => $nextTrip->Commission_limit,
                            'amount' => $nextTrip->value,
                            'status' => 1, 
                            ]);
                        // $user = User::where('id',$userId)->first();
                        // $user->ebike_eligible = 1;
                        // $user->save();
                    }
                }
                
         }
         
         
     }
    
    public static function RoyaltyRechargeDistribution(){
        
        $RechargeInvestment =  RechargeInvestment::where('status',1)->get();
        if($RechargeInvestment){
            foreach($RechargeInvestment as $investemnt){
                $userId  = $investemnt->user_id;
                $userInfo = User::where('id',$userId)->first();
                 $gen = $userInfo->team->gen;
                $activeDate = $userInfo->active_date; 
                // Calculate days from active_date to today
                $activeDate = Carbon::parse($activeDate);
                $today = Carbon::now();
                
                $daysCount = $activeDate->diffInDays($today);
            
                 $getRoyalty = PlanRechargeRoyalty::where('status',1)->where('id','!=','1')->get();
                  if($getRoyalty){
                      foreach($getRoyalty as $royalty){
                          
                            $daysRequired = $royalty->Commission_limit;
                            if($daysCount <= $daysRequired){
                              
                              
                              
                                $teamRequired = $royalty->team_required;
                                $level = $royalty->level;
                                for ($i = 0; $i < $level; $i++) {
                                    if ($gen) {
                                        $teams = Team::whereIn('sponsor', $gen)->pluck('user_id')->toArray(); // Convert to array
                                        $lvlteam = $teams; 
                                        $lvlTeam = count($lvlteam);
                                    }  
                                }
                                
                                if($lvlTeam >= $teamRequired){
                                    $userExists = RechargeRoayltyAchiver::where('user_id',$userId)->where('status',1)->first();
                                    if($userExists){
                                        $userExists->royalty_id = $royalty->id;
                                        $userExists->amount = $royalty->value;
                                        $userExists->save;
                                        
                                    }else{
                                        RechargeRoayltyAchiver::create([
                                                'user_id'=>$userId,
                                                'royalty_id'=>$royalty->id,
                                                'amount'=>$royalty->value,
                                                'status'=>1, 
                                        ]);
                                    }
                                }
                              
                          }    
                    }
                }     
            }
        }   
        
    }
    
    public static function RoyaltyRechargeIncomeDistribution(){
        
        $EbikesroyaltyAchievers =  RechargeRoayltyAchiver::where('status',1)->get();
        if($EbikesroyaltyAchievers){
            foreach($EbikesroyaltyAchievers as $investemnt){
                    $userId  = $investemnt->user_id;
                    $userinfo = User::where('id',$userId)->first();
                    $mobile = $userinfo->mobile;
                    $amnt  = $investemnt->amount; 
                    $wallet = 'main_wallet';
                    $ttlTurnover = RechargeInvestment::where('status','1')->sum('amount');
                    $comm = $ttlTurnover*$amnt/100;
                    if($comm > 0){
                        Transaction::create([
                            'user_id' => $userId,
                            'tx_user' => $userId, 
                            'type' => 'credit',
                            'tx_type' => 'income',
                            'wallet' => $wallet,
                            'income' => 'ebike_royalty',
                            'status' => 1,                        
                            'amount' => $comm, 
                            'remark' => "Receive Ebike royalty Income of amount $comm to $mobile",
                        ]);
                            
                        Wallet::where('user_id',$userId)->increment($wallet,$comm);
                        Income::where('user_id',$userId)->increment($wallet,$comm);
                        DailyIncome::where('user_id',$userId)->increment($wallet,$comm);
                        
                    }  
                }     
        }
            
    }
    
     ////////////////////Recharge end here///////////////////////////
     
     
    ////////////////////Draw start start///////////////////////////




    public  static function joinJackpotDrawUsers(){
            $users = User::where('kyc_status', 1)
            ->whereHas('wallet', function ($query) {
                $query->where('main_wallet', '>', 50)
                    ->orWhere('gold_membership_wallet', '>', 50);
            })
            ->get();
       
                foreach($users as $user){
                    $mainwallet = $user->wallet->main_wallet;
                    $goldwallet = $user->wallet->gold_membership_wallet;
                    $status = false;
                    if($mainwallet > 50){
                        $status = true;
                        $wallettype ='main_wallet'; 
                    }elseif($goldwallet > 50){
                        $status = true;
                        $wallettype ='gold_membership_wallet'; 
                    }
                   if($status == true){

                    $alreadyParticipate = JackpotDrawParticipate::where('status',1)->where('jackpot_id','1')->where('user_id',$user->id)->first();
                    if(!$alreadyParticipate){

                       $jackpot1 =  JackpotDrawParticipate::create([
                                'user_id'=>$user->id,
                                'jackpot_id'=>1,
                                'pay_amount'=>15,
                                'amount'=>75,
                                'status'=>1,
                        
                        ]);
                  

              

                        // $jackpot1 = JackpotDrawParticipate::create([
                        //         'user_id'=>$user->id,
                        //         'jackpot_id'=>2,
                        //         'pay_amount'=>25,
                        //         'amount'=>75,
                        //         'status'=>1, 
                        // ]);
                        Wallet::where('user_id',$user->id)->decrement($wallettype,'15');

                    
                    } 
                        
                   }
                } 
     }
    public  static function joinJackpotDrawUsersWeekly(){
            $users = User::where('kyc_status', 1)
            ->whereHas('wallet', function ($query) {
                $query->where('main_wallet', '>', 50)
                    ->orWhere('gold_membership_wallet', '>', 50);
            })
            ->get();
       
                foreach($users as $user){
                    $mainwallet = $user->wallet->main_wallet;
                    $goldwallet = $user->wallet->gold_membership_wallet;
                    $status = false;
                    if($mainwallet > 50){
                        $status = true;
                        $wallettype ='main_wallet'; 
                    }elseif($goldwallet > 50){
                        $status = true;
                        $wallettype ='gold_membership_wallet'; 
                    }
                   if($status == true){

                    $alreadyParticipate = JackpotDrawParticipate::where('status',1)->where('jackpot_id','2')->where('user_id',$user->id)->first();
                    if(!$alreadyParticipate){

                       $jackpot1 =  JackpotDrawParticipate::create([
                                'user_id'=>$user->id,
                                'jackpot_id'=>2,
                                'pay_amount'=>25,
                                'amount'=>75,
                                'status'=>1,
                        
                        ]);
                  

              

                        // $jackpot1 = JackpotDrawParticipate::create([
                        //         'user_id'=>$user->id,
                        //         'jackpot_id'=>2,
                        //         'pay_amount'=>25,
                        //         'amount'=>75,
                        //         'status'=>1, 
                        // ]);
                        Wallet::where('user_id',$user->id)->decrement($wallettype,'25');

                    
                    } 
                        
                   }
                } 
     }




    public static function JackpotDistribution(){
        
         $allParticapate = JackpotDrawParticipate::where('jackpot_id',1)->get();
         $allParticapatecount = JackpotDrawParticipate::where('jackpot_id',1)->count();
      
         $ttlAmount = JackpotDrawParticipate::where('jackpot_id',1)->sum('pay_amount');
         
         if($allParticapatecount > 0){
             if($allParticapate){
            
                    $winner = $allParticapate->random();
          
                    $userId = $winner->user_id;
                    
                    $userinfo = User::where('id',$userId)->first();
                    $mobile = $userinfo->mobile;
                    $comm = $ttlAmount*$winner->amount/100;
                     
                    if($comm > 0){
                        Transaction::create([
                            'user_id' => $userId,
                            'tx_user' => $userId, 
                            'type' => 'credit',
                            'tx_type' => 'income',
                            'wallet' => 'main_wallet',
                            'income' => 'jackpot_draw_15',
                            'status' => 1,                        
                            'amount' => $comm, 
                            'remark' => "Receive Daily jackpot_draw Income of amount $comm to $mobile",
                        ]);
                            
                        Wallet::where('user_id',$userId)->increment('main_wallet',$comm);
                        Income::where('user_id',$userId)->increment('jackpot_draw',$comm);
                        DailyIncome::where('user_id',$userId)->increment('jackpot_draw',$comm);
           
                    }   
        }

        JackpotDrawParticipate::where('jackpot_id', 1)->delete();
         }
        
        
    }
  
    public static function JackpotWeeklyDistribution(){
        
         $allParticapate = JackpotDrawParticipate::where('jackpot_id',2)->get();
         $allParticapatecount = JackpotDrawParticipate::where('jackpot_id',2)->count();
      
         $ttlAmount = JackpotDrawParticipate::where('jackpot_id',2)->sum('pay_amount');
         
         if($allParticapatecount > 0){
             if($allParticapate){
            
                    $winner = $allParticapate->random();
          
                    $userId = $winner->user_id;
                    
                    $userinfo = User::where('id',$userId)->first();
                    $mobile = $userinfo->mobile;
                    $comm = $ttlAmount*$winner->amount/100;
                     
                    if($comm > 0){
                        Transaction::create([
                            'user_id' => $userId,
                            'tx_user' => $userId, 
                            'type' => 'credit',
                            'tx_type' => 'income',
                            'wallet' => 'main_wallet',
                            'income' => 'jackpot_draw_25',
                            'status' => 1,                        
                            'amount' => $comm, 
                            'remark' => "Receive Weekly jackpot_draw Income of amount $comm to $mobile",
                        ]);
                            
                        Wallet::where('user_id',$userId)->increment('main_wallet',$comm);
                        Income::where('user_id',$userId)->increment('jackpot_draw',$comm);
                        DailyIncome::where('user_id',$userId)->increment('jackpot_draw',$comm);
           
                    }   
        }
        JackpotDrawParticipate::where('jackpot_id', 2)->delete();
         }
        
        
    }
    
    ////////////////////Draw start start///////////////////////////
    
    public static function fetchgold(){
            $curl = curl_init();
        
        curl_setopt_array($curl, [
        	CURLOPT_URL => "https://gold-rates-india.p.rapidapi.com/api/gold-rates",
        	CURLOPT_RETURNTRANSFER => true,
        	CURLOPT_ENCODING => "",
        	CURLOPT_MAXREDIRS => 10,
        	CURLOPT_TIMEOUT => 30,
        	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        	CURLOPT_CUSTOMREQUEST => "GET",
        	CURLOPT_HTTPHEADER => [
        		"x-rapidapi-host: gold-rates-india.p.rapidapi.com",
        	//	"x-rapidapi-key: db9c138051mshdbfc1ad3f3dd75fp16654ajsn3a776accac0d"
        		"x-rapidapi-key: 8d59ad78d5msh42ea84c72d44d4bp1b4a7ajsn7b1583039cb1"
        	],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
        	echo "cURL Error #:" . $err;
        } else {
            $data = json_decode($response,true);
       
        	$goldPrice  = $data['GoldRate'][431]['TenGram24K'];
        	
        	$goldSetting = Setting::where('type', 'gold_rate')->first();

                if ($goldSetting) {
                    // Update the gold price
                    $goldSetting->value = $goldPrice;
                    $goldSetting->save();
                }
        }

    }
    
    public static function clearDailyInmcome(){
        DB::table('daily_incomes')->update([
            'roi' => 0,
            'level' => 0,
            'direct' => 0,
            'self' => 0,
            'reward' => 0,
        ]);
    }
     public static function testAnyThing(){


        $allinvesters = Investment::where('status',1)->get();
      
        foreach($allinvesters as $user){

            $amount = Transaction::where('user_id',$user->user_id)->where('income',['refferal','roi'])->where('tx_id',$user->id)->sum('amount');

            if($amount > 1){
                $user->received_amnt = $amount;
                $user->save();
               
            }else{
                 echo"<pre>";
                print_r($amount);

                echo"</pre>";
            }
            

        }
        
      
    }
    ////////////////////////////////////////////////////////////////tour /////////////////////////////////////////////////////
 
    public static function DitrubteTourIdAdd($data) {
   
        
        $sponser = Team::where('user_id', '=',  $data->tx_user)->value('sponsor');
        $userData = Team::where('user_id',$data->tx_user)->first();
       
        
        // $isMember = ;
       //DB::table('tests')->insert(['remark'=>$sponser]);
    
        if ($sponser) {
            
           while ($sponser) { 
         
            
                $sponsorInfo = User::where('id',$sponser)->first();
                $sponsorexists = TourInvestment::where('user_id',$sponser)->first();
                if($sponsorexists){
                if($userData->position == '1'){
                    $sponsorInfo->left_tour +=  1;
                }else{
                    $sponsorInfo->rigth_tour +=  1;
                }
                $sponsorInfo->save();
                }

            

                $userData = Team::where('user_id',$sponser)->first();
                $sponser= DB::table('teams')
                ->where('user_id', '=',  $sponser)
                ->value('sponsor'); 
         
               
            }      
    
            }
                 

    }   


    public static function ReferralTourIncome($data) {
    
        $level_commsionall=DB::table('plan_tour_referral_incomes')->get(); 
        $userData = User::where('id',$data->tx_user)->first();
        $username= $userData->username;
        $name= $userData->name;
        $mobile= $userData->mobile;
        $sponser= DB::table('teams')->where('user_id', '=',  $data->tx_user)->value('sponsor');
        // $isMember = ;
        //$sponser = $userData->user_id;
        if (!empty($sponser)) {
           for ($i=1; $i <= count($level_commsionall); $i++) { 
              if($sponser){
                $active_status = TourInvestment::where('user_id',$sponser)->first();
                if($active_status){
                 
                    $level_commsion = PlanTourReferralIncome::where('level',$i)->where('status',1)->first();
                // } 
                 
                if ($level_commsion) {
                  
                   
                    
                    $comm = $level_commsion->commision_type=='percent' ? $data->amount*$level_commsion->value/100:$level_commsion->value;
               
                    if($comm>0){
                        $user = User::where('id',$sponser)->first();
                        Wallet::where('user_id',$sponser)->increment('bouns_wallet',$comm);
                        $closed_amount = $user->wallet->bouns_wallet;
                        
                        Transaction::create([
                            'user_id' => $sponser, 
                            'tx_user' =>  $data->tx_user, 
                            'type' => 'credit',
                            'tx_type' => 'income',
                            'wallet' => 'bouns_wallet',
                            'income' => $level_commsion->source,
                            'status' => 1,                        
                            'amount' => $comm,
                            'close_amount' => $closed_amount,  
                            'remark' => "Receive Direct of amount Rs $comm from purchasing of prime pakage $name $mobile."
                        ]);
                       
                        Income::where('user_id',$sponser)->increment($level_commsion->source,$comm);                 
                        DailyIncome::where('user_id',$sponser)->increment($level_commsion->source,$comm);                 
                    }               
                    
                    // $sponser= DB::table('teams')
                    //   ->where('user_id', '=',  $sponser)
                    //   ->value('sponsor'); 
                }  
                
                
              
              }
            }
            }      
    
            }
             
    
         }
    public static function tourDailyIncome(){
        $comm = 60;
        $investments = TourInvestment::all();
        if($investments){
            foreach($investments as $investment){

                if($investment->days < 100){
                    $user = User::where('id',$investment->user_id)->first();
                    Wallet::where('user_id',$investment->user_id)->increment('bouns_wallet',$comm);
                    $closed_amount = $user->wallet->bouns_wallet;
                    Transaction::create([
                        'user_id' => $investment->user_id, 
                        'tx_user' => $investment->user_id, 
                        'type' => 'credit',
                        'tx_type' => 'income',
                        'wallet' => 'bouns_wallet',
                        'income' => "tour_cashback",
                        'status' => 1,  
                        'tx_id' => $investment->id,  
                        'close_amount' => $closed_amount,  
                        'amount' => $comm,
                        'remark' => "Receive Tour Cashback  of amount Rs 60 from purchasing of Tour Investment."
                    ]);
                  
                    Income::where('user_id',$investment->user_id)->increment('tour_cashback',$comm);                 
                    DailyIncome::where('user_id',$investment->user_id)->increment('tour_cashback',$comm);                 
                    $investment->days += 1;
                    $investment->received_amnt += $comm;
                    $investment->save();
                }
              

            }
            
        }

     }

     public static function distrbuteTourBinaryMacth(){
        
        $investments = TourInvestment::all();
        if($investments){
     
            
            foreach($investments as $investment){
                $userId= $investment->user_id;
            
                $sponsorInfo = User::where('id',$userId)->first();
                

                $leftbusiness = $sponsorInfo->left_tour;
                $rigthbusiness = $sponsorInfo->rigth_tour;
                $matching = min($leftbusiness, $rigthbusiness);
                ///$goldmatching = $sponsorInfo->ebike_matching;
                //$active_sponsor = GoldInvestment::where('user_id',$sponser)->latest()->first();
               
                if($matching >= 5){
                    $number = 5;

                }else{
                    $number = $matching;
                }
                
                $sponsorInfo->tour_matching = $matching;
                $sponsorInfo->save();
                    if($number > 0){
                         $exists = TourBinaryClosing::where('user_id',$userId)->where('invest_id',$investment->id)->first();
                    
                        if ($exists) {
                            $exists->macthing =$number;
                            $exists->save(); 
                        }else{

                            TourBinaryClosing::create([
                                'user_id' => $userId,
                                'invest_id' => $investment->id,
                                'macthing' => $number,
                                'days' => 0,
                                'status' => '1',
                                ]); 

                        }
                    }
               
             
            }      
    
        }
                 




    }
    public static function distrbuteTourBinaryIncome(){
        
        $investments = TourBinaryClosing::all();
        $ttlcount = TourInvestment::where('status',1)->count();
       if($ttlcount > 0){
        if($investments){
     
            
            foreach($investments as $closing){
               

                    $UserId= $closing->user_id;
                   
                    $sponsorInfo = User::where('id',$UserId)->first();
                    $number =  $closing->macthing;

                    $ttlAmount =  $ttlcount*500;
                  


                    for($i = $number;$i>0;$i--){
                        $ttlUsers = TourBinaryClosing::where('macthing',$number)->count();
                        $finalPay = $ttlAmount/$ttlUsers;
                       $pendingamnt =  Transaction::where('user_id',$sponsorInfo->id)->where('income','tour_binary')->where('level',$i)->sum('amount');
                       $finpay = $pendingamnt + $finalPay;
                       if($finpay > 15000){
                            $finalPay = $finpay - 15000;
                       } 
                       if($pendingamnt <= '15000'){

                        if($finalPay>0){

                               $userinfo= TourInvestment::where('user_id',$sponsorInfo->id)->latest()->first();

                                Wallet::where('user_id',$sponsorInfo->id)->increment('bouns_wallet',$finalPay); 
                                $closingWallet = $sponsorInfo->wallet->bouns_wallet;
                                Transaction::create([
                                    'user_id' => $sponsorInfo->id,  
                                    'tx_user' => $sponsorInfo->id,
                                    'type' => 'credit',
                                    'tx_type' => 'income',
                                    'wallet' => 'bouns_wallet',
                                    'income' => 'tour_binary',
                                    'status' => 1,                        
                                    'amount' => $finalPay,
                                    'close_amount'  => $closingWallet,
                                    'level'  => $i,
                                    'tx_id'  => $closing->invest_id,
                                    'remark' => "Receive Tour binary matching of amount Rs $finalPay"
                                ]);
                                $closing->days += 1;
                                $closing->save(); 
                                $userinfo->received_amnt += $finalPay;
                                $userinfo->save();
                                Income::where('user_id',$sponsorInfo->id)->increment('tour_binary',$finalPay);                 
                                DailyIncome::where('user_id',$sponsorInfo->id)->increment('tour_binary',$finalPay); 

                            }
    
                            
                    }
                     }
       
    
                        }
                   
                       
            

                }
           
    
            }        
    }
     

    ////////////////////////////////////////////////////////////////tour /////////////////////////////////////////////////////



    ////////////////////////////////////////////////////////////////Elite  /////////////////////////////////////////////////////

    public static function ReferralEliteIncome($data) {
    
        $level_commsionall=DB::table('plan_elite_referrals')->get(); 
        $userData = User::where('id',$data->tx_user)->first();
        $username= $userData->username;
        $name= $userData->name;
        $mobile= $userData->mobile;
        $sponser= DB::table('teams')->where('user_id', '=',  $data->tx_user)->value('sponsor');
        // $isMember = ;
        //$sponser = $userData->user_id;
        if (!empty($sponser)) {
            for ($i=1; $i <= count($level_commsionall); $i++) { 
                if($sponser){

                    $active_status = EliteInvestment::where('user_id',$sponser)->latest()->first();
                    if($active_status){
 
                        $level_commsion = PlanEliteReferral::where('level',$i)->where('status',1)->first();
                        
                        
                        if ($level_commsion) {
                        
                        
                            
                            $comm = $level_commsion->commision_type=='percent' ? $data->amount*$level_commsion->value/100:$level_commsion->value;
                    
                            if($comm>0){
                                $user = User::where('id',$sponser)->first();
                                Wallet::where('user_id',$sponser)->increment('elite_wallet',$comm);
                                $closed_amount = $user->wallet->elite_wallet;
                                
                                Transaction::create([
                                    'user_id' => $sponser, 
                                    'tx_user' =>  $data->tx_user, 
                                    'type' => 'credit',
                                    'tx_type' => 'income',
                                    'wallet' => 'elite_wallet',
                                    'income' => $level_commsion->source,
                                    'status' => 1,                        
                                    'amount' => $comm,
                                    'close_amount' => $closed_amount,  
                                    'remark' => "Receive Referral of amount Rs $comm from purchasing of Elite pakage $name $mobile."
                                ]);
                                
                                Income::where('user_id',$sponser)->increment($level_commsion->source,$comm);                 
                                DailyIncome::where('user_id',$sponser)->increment($level_commsion->source,$comm); 
                                $active_status->received_amnt =$active_status->received_amnt + $comm;
                                $active_status->save();
                            }               
                            
                            // $sponser= DB::table('teams')
                            //   ->where('user_id', '=',  $sponser)
                            //   ->value('sponsor'); 
                        }        
                        
                    }
                            
                }
            }      
        
        }
             
    
    }

    public static function DitrubteEliteIdAdd($data) {
   
        
        $sponser = Team::where('user_id', '=',  $data->tx_user)->value('sponsor');
        $userData = Team::where('user_id',$data->tx_user)->first();
       
        
        // $isMember = ;
       //DB::table('tests')->insert(['remark'=>$sponser]);
    
        if ($sponser) {
            
           while ($sponser) { 
         
                $amount = $data->amount;
                $sponsorInfo = User::where('id',$sponser)->first();
                $sponsorexists = EliteInvestment::where('user_id',$sponser)->first();
                if($sponsorexists){
                    if($userData->position == '1'){
                        $sponsorInfo->left_elite +=  $amount;
                    }else{
                        $sponsorInfo->rigth_elite +=  $amount;
                    }
                    $sponsorInfo->save();

                }
               
            

                $userData = Team::where('user_id',$sponser)->first();
                $sponser= DB::table('teams')
                ->where('user_id', '=',  $sponser)
                ->value('sponsor'); 
         
               
            }      
    
            }
                 

    }   


    public static function eliteDailyIncome(){
      
        $investments = EliteInvestment::where('id','<=','567')->get();
        if($investments){
            foreach($investments as $investment){
                $per = 15;

                if($investment->days < 15){
                    if($investment->days == '0'){
                       
                        $comm = $investment->amount*$per/100;
                    }else{
                        $per = $per - $investment->days;
                        $comm = $investment->amount*$per/100;
                    }
                    $user = User::where('id',$investment->user_id)->first();
                    Wallet::where('user_id',$investment->user_id)->increment('elite_wallet',$comm);
                    $closed_amount = $user->wallet->elite_wallet;
                    Transaction::create([
                        'user_id' => $investment->user_id, 
                        'tx_user' => $investment->user_id, 
                        'type' => 'credit',
                        'tx_type' => 'income',
                        'wallet' => 'elite_wallet',
                        'income' => "elite_cashback",
                        'status' => 1,  
                        'tx_id' => $investment->id,  
                        'close_amount' => $closed_amount,  
                        'amount' => $comm,
                        'remark' => "Receive Elite Cashback  of amount Rs $comm from purchasing of Elite Investment."
                    ]);
                  
                    Income::where('user_id',$investment->user_id)->increment('elite_cashback',$comm);                 
                    DailyIncome::where('user_id',$investment->user_id)->increment('elite_cashback',$comm);                 
                    $investment->days = $days =  $investment->days + 1;

                    if($days >= 15 && $investment->package_status == '1'){
                        $investment->package_status = 0;
                        $user->left_elite = 0;
                        $user->rigth_elite = 0;
                        $user->elite_matching = 0;
                        $user->save();
                    }

                    $investment->received_amnt += $comm;
                    $investment->cashback_amount += $comm;
                    $investment->save();


                    
                }
              

            }
            
        }

     }
    public static function eliteDailynewIncome(){
      
        $investments = EliteInvestment::where('id','>=','567')->get();
        if($investments){
            foreach($investments as $investment){
                $per = 1;

                if($investment->days < 15){
                    if($investment->days == '0'){
                       
                        $comm = $investment->amount*$per/100;
                    }else{
                        $per = $per + $investment->days;
                        $comm = $investment->amount*$per/100;
                    }
                    $user = User::where('id',$investment->user_id)->first();
                    Wallet::where('user_id',$investment->user_id)->increment('elite_wallet',$comm);
                    $closed_amount = $user->wallet->elite_wallet;
                    Transaction::create([
                        'user_id' => $investment->user_id, 
                        'tx_user' => $investment->user_id, 
                        'type' => 'credit',
                        'tx_type' => 'income',
                        'wallet' => 'elite_wallet',
                        'income' => "elite_cashback",
                        'status' => 1,  
                        'tx_id' => $investment->id,  
                        'close_amount' => $closed_amount,  
                        'amount' => $comm,
                        'remark' => "Receive Elite Cashback  of amount Rs $comm from purchasing of Elite Investment."
                    ]);
                  
                    Income::where('user_id',$investment->user_id)->increment('elite_cashback',$comm);                 
                    DailyIncome::where('user_id',$investment->user_id)->increment('elite_cashback',$comm);                 
                    $investment->days = $days =  $investment->days + 1;

                    if($days >= 15 && $investment->package_status == '1'){
                        $investment->package_status = 0;
                        $user->left_elite = 0;
                        $user->rigth_elite = 0;
                        $user->elite_matching = 0;
                        $user->save();
                    }

                    $investment->received_amnt += $comm;
                    $investment->cashback_amount += $comm;
                    $investment->save();


                    
                }
              

            }
            
        }

     }

     public static function distrbuteEliteBinaryMacth(){
      
        $investments = EliteInvestment::where('package_status','1')->get();
        if($investments){
            
            foreach($investments as $investment){
                $userId= $investment->user_id;
                
                $sponsorInfo = User::where('id',$userId)->first();
                

                $leftbusiness = $sponsorInfo->left_elite;
                $rigthbusiness = $sponsorInfo->rigth_elite;
                $cuurentmatching = min($leftbusiness, $rigthbusiness);
                ///$goldmatching = $sponsorInfo->ebike_matching;
                //$active_sponsor = GoldInvestment::where('user_id',$sponser)->latest()->first();
                $preMactching = $sponsorInfo->elite_matching;
                $matching = $cuurentmatching - $preMactching; 
             
                  
                if($matching > 0){ 
                    
                    $perMatching = floor($matching / $investment->amount);

                    if($perMatching >= 1){
                        if($perMatching > 5){

                            $sponsorInfo->elite_matching = $cuurentmatching;
                            $sponsorInfo->save();
                            $amount = 0;
                            $amount = 0;
                            for($i=1;$i<=5;$i++){
                                $per = 10 + (($i - 1) * 5);
                                $amount += $investment->amount * $per/100;
                                $current_matching = $investment->amount*5;
    
                            }
                             
                            
                             
                        }else{
                            
                           
                            
                            $amount =0;
                            for($i=1;$i<=$perMatching;$i++){
                                $per = 10 + (($i - 1) * 5);
                                $amount += $investment->amount * $per/100;
                            }
                            $current_matching = $investment->amount*$perMatching;
                           
                            
                        }
                        
                        $sponsorInfo->elite_matching = $cuurentmatching;
                        $sponsorInfo->save();
                            if($amount > 0){
                                 $exists = EliteBinaryClosing::where('user_id',$userId)->where('invest_id',$investment->id)->first();
                            
                                if ($exists) {
                                    $exists->macthing =$amount;
                                    $exists->save(); 
                                }else{
        
                                    EliteBinaryClosing::create([
                                        'user_id' => $userId,
                                        'invest_id' => $investment->id,
                                        'current_match' => $current_matching,
                                        'macthing' => $amount,
                                        'days' => 0,
                                        'status' => '1',
                                        ]); 
        
                                }
                            }
    

                    }
                
                  
                }
               
               
               
             
            }      
    
        }
                 




    }


    public static function distrbuteEliteBinaryIncome(){
        
        $investments = EliteBinaryClosing::all();
        $ttlcount = EliteInvestment::where('status',1)->count();
       if($ttlcount > 0){
        if($investments){
     
            
            foreach($investments as $closing){

                if($closing->macthing > 0){
                         

                    $UserId= $closing->user_id;
                   
                    $sponsorInfo = User::where('id',$UserId)->first();
                    $finalAmnt =  $closing->macthing;
                    $current_match =  $closing->current_match;
                 
                            $userinfo= EliteInvestment::where('user_id',$sponsorInfo->id)->where('id',$closing->invest_id)->where('package_status','1')->first();
                            $ttlIncome = Transaction::where('user_id',$sponsorInfo->id)->where('tx_id',$closing->invest_id)->where('income','elite_binary')->sum('amount');
                            if($userinfo){
                                $allamnt = $finalAmnt;
                                if($allamnt > $userinfo->amount){
                                    $finalPay = $finalAmnt;
                                }else{
                                    $finalPay =$finalAmnt;
                                }
                           

                            if($finalPay > 0){
                                $userData = User::where('id',$sponsorInfo->id)->first();
                                $eliteMatch = $userData->elite_matching;
                                Wallet::where('user_id',$sponsorInfo->id)->increment('elite_wallet',$finalPay); 
                                $closingWallet = $sponsorInfo->wallet->elite_wallet;
                                Transaction::create([
                                    'user_id' => $sponsorInfo->id,  
                                    'tx_user' => $sponsorInfo->id,
                                    'type' => 'credit',
                                    'tx_type' => 'income',
                                    'wallet' => 'elite_wallet',
                                    'income' => 'elite_binary',
                                    'status' => 1,                        
                                    'amount' => $finalPay,
                                    'close_amount'  => $closingWallet, 
                                    'tx_id'  => $closing->invest_id,
                                    'remark' => "Receive Elite binary $current_match matching of amount Rs $finalPay"
                                ]);
                                 $closing->macthing = 0; 
                                $closing->save(); 

                                $userinfo->received_amnt = $userinfo->received_amnt + $finalPay;
                                $userinfo->save();
                                Income::where('user_id',$sponsorInfo->id)->increment('elite_binary',$finalPay);                 
                                DailyIncome::where('user_id',$sponsorInfo->id)->increment('elite_binary',$finalPay); 


                            }
                            
                        }
                            
    
                            
                    }
                      
       

            }
                }
           
    
            }        
    }
     


    ////////////////////////////////////////////////////////////////Elite  /////////////////////////////////////////////////////



    ////////////////////////////////////////////////////////////////Fly  /////////////////////////////////////////////////////


    public static function distrbuteFlyBinaryIncome($data){
        
        $sponser = Team::where('user_id', '=',  $data->tx_user)->value('sponsor');
        $userData = Team::where('user_id',$data->tx_user)->first();
       
        
        // $isMember = ;
       //DB::table('tests')->insert(['remark'=>$sponser]);
    
        if ($sponser) {
            
           while ($sponser) { 
         
            
                $sponsorInfo = User::where('id',$sponser)->first();
                $sponsorexists = FlyInvestment::where('user_id',$sponser)->first();
                if($sponsorexists){
                        if($userData->position == '1'){
                            $sponsorInfo->fly_left +=  $data->amount;
                        }else{
                            $sponsorInfo->fly_rigth +=  $data->amount;
                        }
                        $sponsorInfo->save();

                        $leftbusiness = $sponsorInfo->fly_left;
                        $rigthbusiness = $sponsorInfo->fly_rigth;
                        $primematching = $sponsorInfo->fly_matching;
                        $matching = min($leftbusiness, $rigthbusiness);
                        $currmatching= $matching -  $primematching;
                        $active_sponsor = FlyInvestment::where('user_id',$sponser)->latest()->first();
                        if($active_sponsor){

                        $ttlAmnt =  Transaction::where('user_id',$sponser)->where('tx_id',$active_sponsor->id)->where('income','fly_binary')->sum('amount');
                        if($ttlAmnt >= '1200'){

                            $active_sponsor->package_status = 0;
                            $active_sponsor->save();

                        }else{
                            $comm =$currmatching;
                            $sponsorInfo->fly_matching = $matching;
                            $sponsorInfo->save();

                            if($comm > 1200){
                                $active_sponsor->flash =$comm -  1200;
                                $comm = 1200;

                            }
                             
                                if($comm>0){
    
                                    Wallet::where('user_id',$sponser)->increment('fly_wallet',$comm); 
                                    $closingWallet = $sponsorInfo->wallet->fly_wallet;
                                    Transaction::create([
                                        'user_id' => $sponser,  
                                        'tx_user' => $data->tx_user,
                                        'type' => 'credit',
                                        'tx_type' => 'income',
                                        'wallet' => 'fly_wallet',
                                        'income' => 'fly_binary',
                                        'status' => 1,                        
                                        'amount' => $comm,
                                        'close_amount'  => $closingWallet,
                                        'tx_id'  => $active_sponsor->id,
                                        'remark' => "Receive fly binary matching of amount Rs $comm"
                                    ]);
                                    $active_sponsor->received_amnt += $comm;
                                    $active_sponsor->save(); 
                                
                                    Income::where('user_id',$sponser)->increment('fly_binary',$comm);                 
                                    DailyIncome::where('user_id',$sponser)->increment('fly_binary',$comm);   
    
    
                                }
    
                        }
                        
                        
                        }
                }

                $userData = Team::where('user_id',$sponser)->first();
                   
                $sponser= DB::table('teams')
                ->where('user_id', '=',  $sponser)
                ->value('sponsor'); 
         
               
            }      
    
            }
                 




    }
    ////////////////////////////////////////////////////////////////Fly  /////////////////////////////////////////////////////
 
public static function clrRequest(){
    DB::table('withdrawal_requests')->truncate();

}
}
