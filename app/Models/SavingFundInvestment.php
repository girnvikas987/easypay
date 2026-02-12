<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use DateTimeInterface;

class SavingFundInvestment extends Model
{
   use HasFactory;
    protected $fillable = [
        'user_id',
        'tx_user',
        'package_id',
        'received_amnt',
        'days',
        'amount',
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
        return $this->belongsTo(Package::class,'package_id');
    }
}
