<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PlanSetting extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => 'boolean',
    ];
    
    protected $fillable = [
        'plan_id',
        'title',
        'type',
        'value',
        
        'status',
       
    ];
    public static function getSetting($key, $default = null)
    {
        $setting = self::where('type', $key)->where('status',true)->first();

        return $setting ? $setting->value : $default;
    }
}
