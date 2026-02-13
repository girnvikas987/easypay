<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class DemoReward extends Model
{
          use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'reward',
        'description',
        'rank',
        'type',
        'status',

    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
