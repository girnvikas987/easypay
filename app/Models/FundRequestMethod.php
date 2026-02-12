<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FundRequestMethod extends Model
{
    use HasFactory;
    protected $casts = [
        'status' => 'boolean',
    ];
    protected $fillable = [
        'title',
        'name',
             
        'status',
       
    ];

    public function option(): HasMany
    {
        return $this->hasMany(FundRequestMethodOption::class, 'fund_request_method_id');
    }
}
