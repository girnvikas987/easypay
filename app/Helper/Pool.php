<?php

namespace App\Helper;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\Income;
use App\Models\PlanSetting;
use App\Models\Autopool;

class Pool
{
    public static function getParent($prnt,$type='default',$sub='1'){
       
        $found='no';
        $nodes = PlanSetting::getSetting('autopoolNode',2);
        //$i=0;
        $prnts=[$prnt];
        $parentid=$prnt;
        $last_level=Autopool::isExists($prnt,$type,$sub);
        for($i=1;$i<=100;$i++){

            $kk=Autopool::getChild($prnts,$type,$sub);
            if($kk->count()>=$nodes**$i){
                
                $last_level=$kk;
                $chils = json_decode($kk);
                $prnts = array_column($chils,'id');
                
            }else{                
                foreach($last_level as $newpr){
                    if($found=='no'){
                        if($newpr->children->count()<$nodes){
                            $found='yes'; 
                            $parentid = $newpr->id;
                        
                        }
                    }                    
                } 
                if($found=='yes'){
                    break;                                  
                }
            }
        }
        
        return $parentid;
        
    }

    public static function getParentnew($prnt,$type='1',$sub='1'){
       
        $found='no';
        $nodes = PlanSetting::getSetting('autopoolNode',2);
        //$i=0;
       
        $prnts=[$prnt];
        $parentid=$prnt;
        $last_level=Autopool::isExists($prnt,$type,$sub);
        
       // print_r($last_level);
        for($i=1;$i<=100;$i++){

            $kk=Autopool::whereIn('parent_id',$prnts)->where('pool',$type)->where('pool_num',$sub)->pluck('id')->toArray();
            $cntyArr = !empty($kk) ? count($kk):0;
            // echo "<br> Total usr".$kk->count();
            // print_r($prnts);
            if($cntyArr>=$nodes**$i){
                
                // $last_level=$kk;
                // $chils = json_decode($kk);
                $prnts = $kk ;//array_column($chils,'id');
               
            }else{
                    //   echo $i;        
                foreach($prnts as $newpr){
                    if($found=='no'){
                    //     echo"<pre>";
                    //     echo $newpr->id."<br>";
                    // print_r($newpr->children->count());
                //  die(); 
                $newprs = Autopool::where('parent_id',$newpr)->where('pool',$type)->where('pool_num',$sub)->get();
                        if($newprs->count()<$nodes){
                           // print_r("here");
                               
                            $found='yes'; 
                            $parentid = $newpr;
                        
                        }
                    }                    
                } 
                if($found=='yes'){
                    break;                                  
                }
            }
        }
     return $parentid;
        
    }
}
