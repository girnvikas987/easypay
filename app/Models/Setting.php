<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
     use HasFactory;
    protected $casts = [
        'status' => 'boolean',
    ];
    
    protected $fillable = [
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
