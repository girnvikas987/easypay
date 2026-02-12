<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;
class PaymentMethod extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'name',
             
        'status',
       
    ];

    public function option(): HasMany
    {
        return $this->hasMany(PaymentMethodOption::class, 'payment_method_id');
    }
}
