<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OttCredential extends Model
{
    use HasFactory;
    protected $fillable = [
        'url',
        'username',
        'password',
        'type',        
    ];

    
}
