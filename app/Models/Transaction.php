<?php

namespace App\Models;

use App\Enums\TransactionStatus;
use App\Enums\TransactionTypes;
use App\Enums\TransactionTxTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
class Transaction extends Model
{
    use HasFactory;

    
    
    protected $fillable = [
        'user_id',
        'tx_user',
        'amount',
        'type',
        'tx_type',
        'wallet',
        'charges',
        'close_amount',
        'income',
      	'rate',
        'status',
        'tx_id',
        'level',
        'remark',
        'created_at',
        'updated_at',
    ];
    
    
 
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'status' =>TransactionStatus::class,
        'tx_type' =>TransactionTxTypes::class,
        'type' =>TransactionTypes::class  
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function tx_user_details(): BelongsTo
    {
        return $this->belongsTo(User::class,'tx_user');
    }
    
    public function wallet_type(): BelongsTo
    {
        return $this->belongsTo(WalletType::class,'wallet','slug')->where('type','wallet');
    }

}
