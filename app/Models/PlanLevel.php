<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanLevel extends Model
{
    use HasFactory;
    protected $casts = [
        'status' => 'boolean',        
    ];
    protected $fillable = [        
        'direct_required',
        'level',
        'source',
        'commision_type',
        'value',         
        'status',         
        'wallet_type_id',        
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(WalletType::class,'wallet_type_id');
    }
}
