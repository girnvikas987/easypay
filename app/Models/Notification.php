<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
class Notification extends Model
{
   use HasFactory;
    protected $casts = [
        'status' => 'boolean',
    ];
    protected $fillable = [
        'title',
        'type',
        'description',
        'image',
        'status',

    ];

     protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }


}
