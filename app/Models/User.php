<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use DateTimeInterface;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
 
use Filament\Panel;

class User extends Authenticatable
{


  
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'eth_address',
        'username',
        'sponsor',
        'mobile', 
        'email',
        'password',
        'alternative_password',
        'active_status',
        'kyc_status',
        'block_status', 
        'transaction_pin',
        'package',
        'image',
        'address',
        'city',
        'state',
        'postcode',
    ];
    
     protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
     

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
        public function bankDetail()
        {
            return $this->hasOne(Bank::class); // adjust if your table is named differently
        }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

      public function byUsername($user_input)
    {
        return $this->where('username', $user_input)->first();
    }
    public function byMobile($user_input)
    {
        return $this->where('mobile', $user_input)->first();
    }

 
    

    /**
     * Get all of the teams for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    
    public function team(): HasOne
    {   
        
        return $this->hasOne(Team::class,'user_id');
    }
    public function directs(): HasMany
    {
        return $this->hasMany(Team::class, 'sponsor');
    }
    public function ott_credentials(): HasMany
    {
        return $this->hasMany(UserOttCredential::class, 'user_id')->where('status',1);
    }
    public function activeDirects(): HasMany
    {
        return $this->hasMany(Team::class, 'sponsor')->where('active_status',1);
    }
    public function rewards(): HasMany
    {
        return $this->hasMany(UserReward::class, 'user_id');
    }
     
    /**
     * Get the user associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
     
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class, 'user_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }
    public function autopools(): HasMany
    {
        return $this->hasMany(Autopool::class, 'user_id');
    }
    
    public function investments(): HasMany
    {
        return $this->hasMany(Investment::class, 'user_id');
    }
    public function elite_investments(): HasMany
    {
        return $this->hasMany(EliteInvestment::class, 'user_id');
    }
    public function fly_investments(): HasMany
    {
        return $this->hasMany(FlyInvestment::class, 'user_id');
    }
    
    public function paymentMethods(): HasMany
    {
        return $this->hasMany(UserPaymentMethod::class, 'user_id');
    }
    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class, 'user_id');
    }
    public function fundRequests(): HasMany
    {
        return $this->hasMany(UserFundRequest::class, 'user_id');
    }
    
    public function income(): HasOne
    {
        return $this->hasOne(Income::class, 'user_id');
    }
    
    public function binary(): HasOne
    {
        return $this->hasOne(Binary::class, 'user_id');
    }
    public function kyc(): HasOne
    {
        return $this->hasOne(Kyc::class, 'user_id');
    }
    
    public function bank(): HasOne
    {
        return $this->hasOne(Bank::class, 'user_id');
    }
    
    public function daily_income(): HasOne
    {
        return $this->hasOne(DailyIncome::class, 'user_id');
    }
    
    public function recharges(): HasMany
    {
        return $this->hasMany(Recharge::class, 'user_id');
    }
    
}
