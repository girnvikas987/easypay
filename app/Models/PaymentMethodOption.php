<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentMethodOption extends Model
{
    use HasFactory;
     
    protected $fillable = [
        'title',
        'name',
        'payment_method_id',        
        'isRequired',        
        'status',
       
    ];

    public function payment_method(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
