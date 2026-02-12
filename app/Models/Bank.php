<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bank extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'bank_name',
        'branch_name',
        'holder_name',
        'account',
        'ifsc_code',
        'status',
    ];
    
     public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
