<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class EbikeAchiver extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 
        'royalty_id', 
        'amount',
        'status',
        'created_at',
        'updated_at',
       
    ];
    
     protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
}
