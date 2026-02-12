<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
class GameWin extends Model
{
    use HasFactory;
    protected $fillable = [ 
        'user_id',
        'type',
        'status',
        'created_at'
    ];
 protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
