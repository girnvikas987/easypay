<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Package extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => 'boolean',
        'pre_reqired' => 'boolean',
    ];
    protected $fillable = [
        'name',
        'slug',
        'type',
        'min',
        'max',
        'amount',
        'no_of_time',
        'status',
        'pre_reqired',
       
    ];

    /**
     * Get the user associated with the Package
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function roiSetting(): HasOne
    {
        return $this->hasOne(PlanRoi::class, 'package_id');
    }
}
