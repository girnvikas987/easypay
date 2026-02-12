<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WithdrawRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return Carbon::parse($date)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
