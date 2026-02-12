<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\TransactionStatus;
use Carbon\Carbon;

class Withdrawal extends Model
{
    use HasFactory;
    protected $casts = [        
        'status' =>TransactionStatus::class,        
    ];
    protected $fillable = [
        'user_id',
        'user_details',
        'ifsc_code',
        'account_number',
        'tx_charge',
        'wallet_type',
        'tds_charge',
        'amount',
        'reason',
        'status',
        
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
