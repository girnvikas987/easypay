<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use DateTimeInterface;
class Kyc extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'pan_no',
        'pan_image',
        'pan_status',
        'aadhar_status',
        'aadhar_no',
        'aadhar_front_image',
        'aadhar_back_image',
        'self_image',
       
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
