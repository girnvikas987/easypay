<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
     
    protected $casts = [
        'featured' => 'boolean',
        'images' => 'array',
        'status' =>TransactionStatus::class,
    ];
     protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'regular_price',
        'sale_price',
        'SKU',
        'stock_status',
        'featured',
        'quantity',
        'product_status',
        'image',
        'images',
        'category_id',
        'brand_id',
    ];
}
