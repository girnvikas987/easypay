<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class EbikeInvestment extends Model
{
    use HasFactory;
    
     protected $fillable = [
        'user_id',
        'tx_user',
        'package_id',
        'days',
        'pair_days',
        'amount',
        'received_amnt',
        'status',
       
    ];
    
     protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function tx_user_details(): BelongsTo
    {
        return $this->belongsTo(User::class,'tx_user');
    }
    
    public function package(): BelongsTo
    {
        return $this->belongsTo(EbikePackage::class,'package_id');
    }
}
