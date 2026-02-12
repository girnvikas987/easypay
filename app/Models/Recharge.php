<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use DateTimeInterface;
class Recharge extends Model
{
     
    use HasFactory;
    protected $fillable = [
        'user_id',
        'api_tansasction_id',
        'provider_id',
        'transaction_id',
        'mobile',
        'amount',
        'tx_id',
        'wallet',
        'bouns_balance', 
        'status',
        'api_status',
        'wallet_type',
        'recharge_type'
       
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
     public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


}
