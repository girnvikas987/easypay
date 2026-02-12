<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use DateTimeInterface;
class Support extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject',
        'message',
        'response',
        'status',
        
    ];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function myticketd(): HasMany
    {
        return $this->hasMany(Support::class,'user_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
