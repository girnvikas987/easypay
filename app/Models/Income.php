<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use DateTimeInterface;
class Income extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        
        
        
       
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
