<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
class Game extends Model
{
    
    use HasFactory;
    protected $fillable = [ 
        'type',
        'time',
        'number',
        'amount', 
        'no_of_users', 
        'status', 
    ];
    
     protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
