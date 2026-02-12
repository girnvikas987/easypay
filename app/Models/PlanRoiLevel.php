<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanRoiLevel extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => 'boolean',        
    ];
    protected $fillable = [
        'plan_roi_id',
        'direct_required',
        'level',
        'source',
        'commision_type',
        'value',         
        'status',         
               
    ];

    public function roi(): BelongsTo
    {
        return $this->belongsTo(PlanRoi::class,'plan_roi_id');
    }

}
