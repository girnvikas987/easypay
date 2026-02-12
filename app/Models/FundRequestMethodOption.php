<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FundRequestMethodOption extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'name',
        'image',
        'fund_request_method_id',
              
        'status',
       
    ];

    public function fund_request_method(): BelongsTo
    {
        return $this->belongsTo(FundRequestMethod::class);
    }
}
