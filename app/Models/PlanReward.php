<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanReward extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => 'boolean',        
    ];
    protected $fillable = [        
        'direct_required',
        'generation_team_required',
        'self_business_required',
        'direct_business_required',
        'generation_business_required',
        'left_team_required',
        'right_team_required',
        'reward',         
        'rank',         
        'status',         
        'wallet_type_id',        
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(WalletType::class,'wallet_type_id');
    }

}
