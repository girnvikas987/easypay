<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserOttCredential extends Model
{
    use HasFactory;
    protected $casts = [
        'status' => 'boolean',
    ];
    protected $fillable = [
        'user_id',
        'url',
        'username',
        'password',
        'status',        
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
