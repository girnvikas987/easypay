<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    
    protected $fillable = [
        'provider_id',
        'provider_name',
        'service_id',
        'service_name',
        'service_type',
        'help_line',
        'status',
        
    ];

     
}
