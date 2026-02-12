<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CryptoApiPayment extends Model
{
    use HasFactory;

    protected $casts = [
        'end_at' => 'datetime',         
    ];

    protected $fillable = [
        'user_id',
        'tx_id',
        'token_id',
        'amount',
        'wallet_address',
        'status',
        'hash',
        'end_at',
             
       
    ];
}
