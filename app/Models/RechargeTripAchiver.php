<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class RechargeTripAchiver extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 
        'trip_id', 
        'trip_name', 
        'trip_time', 
        'amount',
        'status',
        'created_at',
        'updated_at',
       
    ];
    
     protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function plantrip(): BelongsTo
    {
        return $this->belongsTo(PlanTrip::class);
    }
}
