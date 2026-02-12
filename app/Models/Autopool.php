<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Autopool extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'parent_id',
        'pool',
        'pool_num',
       
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function children(): HasMany
    {
        return $this->hasMany(Autopool::class,'parent_id','id');
    }

    public static function getChild($parent,$type='default',$num='1'){
        return Self::whereIn('parent_id',$parent)->where('pool',$type)->where('pool_num',$num)->get();
        //return $this->belongsToMany(Autopool::class);
    }
    public static function isExists($userid,$type='default',$num='1'){
        return Self::where('user_id',$userid)->where('pool',$type)->where('pool_num',$num)->get();
        //return $this->belongsToMany(Autopool::class);
    }
}
