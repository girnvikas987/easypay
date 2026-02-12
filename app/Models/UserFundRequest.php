<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\TransactionStatus;
use DateTimeInterface;
class UserFundRequest extends Model
{
    use HasFactory;
    protected $casts = [        
        'status' =>TransactionStatus::class,        
    ];
    protected $fillable = [
        'user_id', 
        'utr_number',
        'amount',
        'screenshot',
        'trans_Id',
        'txs_status',
        'status',
    ];
    
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fund_request_method(): BelongsTo
    {
        return $this->belongsTo(FundRequestMethod::class,'fund_request_method_id');
    }
    
    public function fund_request_method_option(): BelongsTo
    {
        return $this->belongsTo(FundRequestMethodOption::class,'fund_request_method_option_id');
    }
}
