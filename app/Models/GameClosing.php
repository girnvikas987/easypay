<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
class GameClosing extends Model
{
    use HasFactory;
    protected $fillable = [ 
        'user_id',  
        'amount', 
        'type', 
        'no_of_users', 
        'status', 
        'created_at', 
        'updated_at', 
    ];
    
     protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
