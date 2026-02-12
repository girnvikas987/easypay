<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlanRoi extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => 'boolean',
        'level_on_roi' => 'boolean',
    ];
    protected $fillable = [
        'package_id',
        'direct_required',
        'commision_type',
        'value',         
        'status',         
        'level_on_roi',         
        'wallet_type_id',         
    ];
    
    
    

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class,'package_id');
    }
    
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(WalletType::class,'wallet_type_id');
    }


    /**
     * Get all of the comments for the PlanRoi
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function roiLevel(): HasMany
    {
        return $this->hasMany(PlanRoiLevel::class, 'plan_roi_id');
    }

}
