<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
class RoyaltyEbikeAchiver extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'royalty_id', 
        'amount', 
        'status',  
    ];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }

}
