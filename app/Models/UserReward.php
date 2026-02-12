<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserReward extends Model
{
    use HasFactory;
    
    protected $fillable = [        
        'user_id',
        'plan_reward_id',
        'reward',           
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function reward(): BelongsTo
    {
        return $this->belongsTo(UserReward::class,'plan_reward_id');
    }
}
