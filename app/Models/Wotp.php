<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wotp extends Model
{
    use HasFactory;
    protected $fillable = [
        'identifier',
        'token',
        'validity',
        'valid',
        'created_at',
        'updated_at' 
    ];

}
