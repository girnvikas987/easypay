<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LevelCommision extends Model
{
    use HasFactory;
    protected $fillable = [
        'plan_id',
        'commision',
        'type',
        'source',
        'direct_required',
       
    ];
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}
