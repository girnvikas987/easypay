<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class PlanLuckyDraw extends Model
{
    use HasFactory;
    
     
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(WalletType::class,'wallet_type_id');
    }
}
