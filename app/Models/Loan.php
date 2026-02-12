<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface; 
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Loan extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'loan_id',
        'amount',
        'charges',
        'wallet',
        'tx_id',
        'remark',
        'loan_status',
        'status',
        'created_at',
        'updated_at',
       
    ];
    
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function loan(): BelongsTo
    {
        return $this->belongsTo(LoanList::class,'loan_id');
    }
}
