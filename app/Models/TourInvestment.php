<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourInvestment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_id',
        'days', 
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
    
    public function package(): BelongsTo
    {
        return $this->belongsTo(EbikePackage::class,'package_id');
    }
}
