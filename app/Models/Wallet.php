<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Wallet extends Model
{
    use HasFactory;

    // protected $casts = [
    //     'elite_wallet' => 'float',
    //     // other wallet fields...
    // ];
    
    protected $fillable = [
        'user_id',
        'main_wallet',
        'fund_wallet',
        'bouns_wallet',
        'elite_wallet',
        'fly_wallet',
        'gold_membership_wallet',
        'withdraw_ewallet',
        'withdraw_gwallet', 
        'withdraw_twallet',
        'withdraw_bwallet',
        'withdraw_lwallet',
        'withdraw_fwallet',
        'utilities_ewallet',
        'utilities_gwallet',
        'utilities_bwallet',
        'utilities_twallet',
        'utilities_lwallet',
        'utilities_fwallet',
        'scan_ewallet',
        'scan_gwallet',
        'scan_twallet',
        'scan_bwallet',
        'scan_lwallet',
        'scan_fwallet',
        'withdraw_max',
        'withdraw_min',
        'withdraw_fly_min',
        'withdraw_fly_max',
        'withdraw_elite_min',
        'withdraw_elite_max',
        'withdraw_prime_min',
        'withdraw_prime_max',
        'withdraw_tx_limit',
        'withdraw_percentage',
        'withdraw_fly_tx_limit',
        'withdraw_fly_percentage',
        'withdraw_elite_percentage',
        'withdraw_prime_percentage',
        'withdraw_prime_tx_limit', 
        'withdraw_elite_tx_limit', 
         
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
}
