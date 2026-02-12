<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
 use DateTimeInterface;
use Illuminate\Database\Query\Builder;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sponsor',
        'position',
       
    ];

    protected $casts = [
        'gen' => 'array',
        
    ];
    /**
     * Get the user that owns the Team
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
     

     public function getAllUplines()
    {
        $uplines = [];
        $sponsor = $this->sponsor;
        $current = $this;

        while ($sponsor) {
            $current = Team::where('user_id', $sponsor)->first();
            if ($current) {
                $uplines[] = [
                    'name' => $current->user->name,
                    'mobile' => $current->user->mobile, // Assuming `mobile` is a field in the User model
                    'position' => $current->position // Assuming `mobile` is a field in the User model
                ];
                $sponsor = $current->sponsor;
            } else {
                break;
            }
        }

        return $uplines;
    }
    // User.php (or relevant model)
public function getAllDownlines()
{
    $downlines = [];
    $sponsor = $this->sponsor;
    $current = $this;

    $gens = Team::where('user_id', $sponsor)->value('gen'); // Assuming you fetch from Team or related model

    if ($gens) {
        foreach ($gens as $gen) {
            $user = User::find($gen);
            $flyInvestment = FlyInvestment::where('user_id', $user->id)->sum('amount');
            $eliteInvestment = EliteInvestment::where('user_id', $user->id)->sum('amount');

            $downlines[] = [
                'serial' => count($downlines) + 1, // Serial number
                'name' => $user->name,
                'mobile' => $user->mobile,
                'position' => $user->team->position,
                'fly_investment' => $flyInvestment,
                'elite_investment' => $eliteInvestment,
                'fly_status' => $user->fly_status,  // Assuming you have a status column in the user or related model
                'elite_status' => $user->elite_status,  // Same for elite status
            ];
        }
    }

    return collect($downlines); // Return as collection for Filament
}

    
     protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function sponsorinfo(): BelongsTo
    {
        return $this->belongsTo(User::class,'sponsor');
    }
    
    public function children(): HasMany
    {
        return $this->hasMany(Team::class,'sponsor','user_id');
    }
    
    /**
     * The roles that belong to the Team
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
   
    //  public function genear(): HasMany
    // {
    //     return $this->hasMany(User::class, 'id','gen')
    //     ->whereIn('id', $this->gen);
    // }
    
    public static function businessByLeg($user_id)
    {
        $directs = Self::where('sponsor',$user_id)->pluck('user_id')->toArray();
        $ret= [];
         
        if (!empty($directs))
        {
            foreach ($directs as $user) {
                $gen=array();
                $gen = Self::where('user_id',$user)->value('gen');
                $gen[]=$user;
                $ret[$user]=Investment::whereIn('user_id',$gen)->where('status','1')->sum('amount');

            }
            rsort($ret);
        }
        return $ret;
    }
     
    
    public static function teamByLeg($user_id)
    {
        $directs = Self::where('sponsor',$user_id)->pluck('user_id')->toArray();
        $ret= [];
         
        if (!empty($directs))
        {
            foreach ($directs as $user) {
                $gen=array();
                $gen = Self::where('user_id',$user)->value('gen');
                $gen[]=$user;
                $ret[$user]=count($gen);//Investment::whereIn('user_id',$gen)->sum('amount');

            }
            rsort($ret);
        }
        return $ret;
    }
    
     public static function teamByLegLoanPayable($user_id,$loanId)
    {
        $directs = Self::where('sponsor',$user_id)->pluck('user_id')->toArray();
        $ret= [];
         
        if (!empty($directs))
        {
            foreach ($directs as $user) {
                $gen=array();
                $gen = Self::where('user_id',$user)->value('gen');
                $gen[]=$user;
                $ttl = Loan::whereIn('user_id',$gen)->where('loan_id',$loanId)->where('loan_status','Paid')->COUNT(); 
                $ret[$user]=$ttl;
            }
            rsort($ret);
        }
        return $ret;
    }
    
     public static function teamByEbikeBusiness($user_id)
    {
        $directs = Self::where('sponsor',$user_id)->pluck('user_id')->toArray();
        $ret= [];
         
        if (!empty($directs))
        {
            foreach ($directs as $user) {
                $gen=array();
                $ttl = 0;
                $gen = Self::where('user_id',$user)->value('gen');
               // $gen[]=$user;
                if (count($gen) > 3) {
                    $directsOfUser = Self::whereIn('user_id', $gen)->get();
                    if(count($directsOfUser) > 3){ 
                        foreach($directsOfUser as $direct){
                              $checkInvestment = Transaction::where('user_id',$direct)->where('status','1')->where('income','referral_ebike')->sum('amount');
                              if($checkInvestment >= 1200){
                                  $ret[$user]=$ttl++;
                              }  
                        }
                    }
                   
                }
                
                // $ttl = Loan::whereIn('user_id',$gen)->where('loan_id',$loanId)->where('loan_status','Paid')->COUNT(); 
                // $ret[$user]=$ttl;
            }
            rsort($ret);
        }
        return $ret;
    }
    
  public static function teamByEbikeAchivers($user_id)
    {
        // Fetch direct users who also have an active EbikeInvestment
        $directs = Self::where('sponsor', $user_id)
            ->whereHas('ebikeInvestments', function ($query) {
                $query->where('status', '1'); // Assuming 'status' field indicates active investment
            })
            ->pluck('user_id')
            ->toArray();
    
        $ret = [];
        $num = 0;
       
        if (!empty($directs)) {
            if (count($directs) >= 3) {
                foreach ($directs as $user) {
                    $num++;
                   
                    // Fetch the generation (leg) of the direct user
                    $gen = Self::where('user_id', $user)->pluck('gen')->toArray();
                    
                    // Ensure all users in the generation have an active EbikeInvestment
                    if (!empty($gen) && count($gen[0]) >= 9) {
                        $allActive = true;
                        foreach ($gen[0] as $genUserId) {

                         
                            $hasActiveEbikeInvestment = EbikeInvestment::where('user_id', $genUserId)
                                ->where('status', '1')
                                ->exists();
    
                            if (!$hasActiveEbikeInvestment) {
                                $allActive = false;
                                break;
                            }
                        }
    
                        if ($allActive) {
                            $ret[$num] = 1;
                        }
                    }
                }
            }
        }
    
        rsort($ret);
    
        return $ret;
    }
    
    public function ebikeInvestments()
    {
        return $this->hasMany(EbikeInvestment::class, 'user_id', 'user_id');
    }
    
  

     
    
     public static function teamByRechargeBusiness($user_id)
    {
        $directs = Self::where('sponsor',$user_id)->pluck('user_id')->toArray();
        $ret= [];
         
        if (!empty($directs))
        {
            foreach ($directs as $user) {
                $gen=array();
                $ttl = 0;
                $gen = Self::where('user_id',$user)->value('gen');
               // $gen[]=$user;
                if (count($gen) > 3) {
                    $directsOfUser = Self::whereIn('user_id', $gen)->get();
                    if(count($directsOfUser) > 3){ 
                        foreach($directsOfUser as $direct){
                              $checkInvestment = Transaction::where('user_id',$direct)->where('status','1')->where('income','recharge_referral')->sum('amount');
                              if($checkInvestment >= 3000){
                                  $ret[$user]=$ttl++;
                              }  
                        }
                    }
                   
                }
                
                // $ttl = Loan::whereIn('user_id',$gen)->where('loan_id',$loanId)->where('loan_status','Paid')->COUNT(); 
                // $ret[$user]=$ttl;
            }
            rsort($ret);
        }
        return $ret;
    }
    
     public static function teamByRechargeTripBusiness($user_id,$tripId)
    {
        $directs = Self::where('sponsor',$user_id)->pluck('user_id')->toArray();
        $ret= [];
         
        if (!empty($directs))
        {
            foreach ($directs as $user) {
                $gen=array();
                $ttl = 0;
                $gen = Self::where('user_id',$user)->value('gen');
               // $gen[]=$user;
                if (count($gen) > 3) {
                    $directsOfUser = Self::whereIn('user_id', $gen)->get();
                    if(count($directsOfUser) > 3){ 
                        foreach($directsOfUser as $direct){
                              $checkInvestment = RechargeTripAchiver::where('user_id',$direct)->where('status','1')->where('trip_id',$tripId)->count();
                              if($checkInvestment){
                                  $ret[$user]=$ttl++;
                              }  
                        }
                    }
                   
                }
                
                // $ttl = Loan::whereIn('user_id',$gen)->where('loan_id',$loanId)->where('loan_status','Paid')->COUNT(); 
                // $ret[$user]=$ttl;
            }
            rsort($ret);
        }
        return $ret;
    }
    
    
    
      public static function teamByRechargeLevelAchivers($user_id){
        // Fetch direct users who also have an active EbikeInvestment
        $directs = Self::where('sponsor', $user_id)
            ->whereHas('rechargeInvestments', function ($query) {
                $query->where('status', '1'); // Assuming 'status' field indicates active investment
            })
            ->pluck('user_id')
            ->toArray();
    
        $ret = [];
        $num = 0;
       
        if (!empty($directs)) {
            if (count($directs) >= 3) {
                foreach ($directs as $user) {
                    $num++;
                   
                    // Fetch the generation (leg) of the direct user
                    $gen = Self::where('user_id', $user)->pluck('gen')->toArray();
                    
                    // Ensure all users in the generation have an active EbikeInvestment
                    if (!empty($gen) && count($gen[0]) >= 9) {
                        $allActive = true;
                        foreach ($gen[0] as $genUserId) {

                         
                            $hasActiveRechargeInvestment = RechargeInvestment::where('user_id', $genUserId)
                                ->where('status', '1')
                                ->exists();
    
                            if (!$hasActiveRechargeInvestment) {
                                $allActive = false;
                                break;
                            }
                        }
    
                        if ($allActive) {
                            $ret[$num] = 1;
                        }
                    }
                }
            }
        }
    
        rsort($ret);
    
        return $ret;
    }
    
    public function rechargeInvestments()
    {
        return $this->hasMany(RechargeInvestment::class, 'user_id', 'user_id');
    }
    
    
    public static function getThreePaidLoanUsersByLeg($user_id,$loanId){
        $directs = Self::where('sponsor', $user_id)->get(['user_id', 'leg']); // Assuming 'leg' is an attribute of User
        $paidUsers = [];
        $legsChecked = [];
    
        if (!empty($directs)) {
            foreach ($directs as $directUser) {
                // Get the latest loan with status 'payable'
                $latestPaidLoan = Loan::where('user_id', $directUser->user_id)
                                      ->where('loan_id', $loanId)
                                      ->where('status', 'payable')
                                      ->orderBy('created_at', 'desc')
                                      ->first();
    
                // If such a loan exists and the leg hasn't been checked yet, add the user to the paidUsers array
                if ($latestPaidLoan && !in_array($directUser->leg, $legsChecked)) {
                    $legsChecked[] = $directUser->leg;
                    $paidUsers[$directUser->leg] = $directUser;
                }
    
                // Break the loop if we already have 3 users from different legs
                if (count($paidUsers) >= 3) {
                    break;
                }
            }
        }
    
        // Ensure the result array contains only the first 3 unique users from different legs
        $paidUsers = array_slice($paidUsers, 0, 3);
    
        // Determine if we successfully found 3 users from different legs
        $success = count($paidUsers) === 3;
    
        return ['success' => $success, 'paidUsers' => $paidUsers];
    }
    
    
    public static function teamByLoanLevelAchivers($user_id){
        // Fetch direct users who also have an active EbikeInvestment
        $directs = Self::where('sponsor', $user_id)
            ->whereHas('loanInvestments', function ($query) {
                $query->where('status', '1'); // Assuming 'status' field indicates active investment
            })
            ->pluck('user_id')
            ->toArray();
    
        $ret = [];
        $num = 0;
       
        if (!empty($directs)) {
            if (count($directs) >= 3) {
                foreach ($directs as $user) {
                    $num++;
                   
                    // Fetch the generation (leg) of the direct user
                    $gen = Self::where('user_id', $user)->pluck('gen')->toArray();
                    
                    // Ensure all users in the generation have an active EbikeInvestment
                    if (!empty($gen) && count($gen[0]) >= 9) {
                        $allActive = true;
                        foreach ($gen[0] as $genUserId) {

                         
                            $hasActiveLoanInvestment = LoanInvestment::where('user_id', $genUserId)
                                ->where('status', '1')
                                ->exists();
    
                            if (!$hasActiveLoanInvestment) {
                                $allActive = false;
                                break;
                            }
                        }
    
                        if ($allActive) {
                            $ret[$num] = 1;
                        }
                    }
                }
            }
        }
    
        rsort($ret);
    
        return $ret;
    }
    
    public function loanInvestments()
    {
        return $this->hasMany(LoanInvestment::class, 'user_id', 'user_id');
    }
    public function ecomInvestments()
    {
        return $this->hasMany(Order::class, 'user_id', 'user_id');
    }
    
    
    public function gener()
    {
        return $this->hasMany(User::class, 'user_id', 'id')
            ->whereIn('user_id', function (Builder $query) {
                $query->select('user_id')
                    ->from('teams')
                    ->whereIn('user_id', [7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18]);
            });
    }
    
    
    
}
