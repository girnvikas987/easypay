<?php

namespace App\Helper;

use App\Models\Income;
use App\Models\Investment;
use App\Models\Team;
use App\Models\PlanRoi;
use App\Models\Transaction;
use App\Models\User;
use App\Models\DailyIncome;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;


class RoiDistribute
{
    public static function roiClosing(){
      $date = '2024-07-17 18:43:33';
     $enddate = '2024-07-31 23:59:00';

$investments = Investment::where('status', 1)
    ->where('created_at', '>=', $date)
    ->where('created_at', '<=', $enddate)
    ->get();
        foreach ($investments as $key => $value) {
            $packageId = $value->package_id;
            $settings = PlanRoi::where('status',1)->first();
            //$settings = $value->package->roiSetting;
            $stat = $settings->status;
          
            $wallet = 'main_wallet';//$settings->wallet();
             
            if($stat == '1'){
                $activeDirects = User::find($value->user_id)->activeDirects->count();

                if ($settings->direct_required<=$activeDirects) {                   
                
                    if($settings->commision_type=="percent"){
                        $commision = $value->amount*$settings->value/100;
                    }else{
                        $commision = $settings->value;
                    }
                    
                    $trans = Transaction::create([
                        'user_id' => $value->user_id,
                        'tx_id' => $value->id,
                        'type' => 'credit',
                        'tx_type' => 'income',
                        'wallet' =>  $wallet,
                        'income' => 'roi',
                        'status' => 1,                    
                        'amount' => $commision,
                        'remark' => "Recieve Roi income of amount $commision",
                    ]);

                    Wallet::where('user_id',$value->user_id)->increment($wallet,$commision);
                    Income::where('user_id',$value->user_id)->increment('roi',$commision);
                    DailyIncome::where('user_id',$value->user_id)->increment('roi',$commision);
                    if($settings->level_on_roi==1){
                        //Distribute::RoiLevelIncome($trans);
                    }
                }


            }
        }        
        
    }
    
    public static function roiFourClosing(){
        $date = '2024-08-01 00:00:01';
        $enddate = '2024-07-31 23:59:00';

        $investments = Investment::where('status', 1)
            ->where('created_at', '>=', $date)
            ->get();
        foreach ($investments as $key => $value) {
            $packageId = $value->package_id;
            $settings = PlanRoi::where('status',1)->first();
            //$settings = $value->package->roiSetting;
            $stat = $settings->status;
          
            $wallet = 'main_wallet';//$settings->wallet();
             
            if($stat == '1'){
                $activeDirects = User::find($value->user_id)->activeDirects->count();

                if ($settings->direct_required<=$activeDirects) {                   
                
                    if($settings->commision_type=="percent"){
                        $commision = $value->amount*4/100;
                    }else{
                        $commision = $settings->value;
                    }
                    
                    $trans = Transaction::create([
                        'user_id' => $value->user_id,
                        'tx_id' => $value->id,
                        'type' => 'credit',
                        'tx_type' => 'income',
                        'wallet' =>  $wallet,
                        'income' => 'roi',
                        'status' => 1,                    
                        'amount' => $commision,
                        'remark' => "Recieve Roi income of amount $commision",
                    ]);

                    Wallet::where('user_id',$value->user_id)->increment($wallet,$commision);
                    Income::where('user_id',$value->user_id)->increment('roi',$commision);
                    DailyIncome::where('user_id',$value->user_id)->increment('roi',$commision);
                    if($settings->level_on_roi==1){
                        //Distribute::RoiLevelIncome($trans);
                    }
                }


            }
        }        
        
    }
}
