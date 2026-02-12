<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Binary extends Model
{
    use HasFactory;
    protected $casts = [
        'right_team' => 'array',
        'left_team' => 'array',
    ];
    protected $fillable = [
        'parent',
        'user_id',
        'left',
        'left_team',
        'right',       
        'right_team',       
       
    ];

    public static function extrime($usr,$pos)
    {   
              
        if(Binary::where('user_id',$usr)->first()->$pos==null || Binary::where('user_id',$usr)->first()->$pos==''){
            return $usr;
        }else{
            return $this->extrimeLeft($usr);
        }              
    }

    /**
     * Get the user associated with the Binary
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function left(): BelongsTo
    {
        return $this->belongsTo(User::class,'left');
    }
    public function parentInfo(): BelongsTo
    {
        return $this->belongsTo(User::class,'parent');
    }
    public function right(): BelongsTo
    {
        return $this->belongsTo(User::class,'left');
    }
    // public function right_users()
    // {
    //     return User::whereIn('id', $this->right_team);
    // }
    // public function right_users(): BelongsToMany
    // {
    //     return $this->belongsToMany(User::class,Self::class,'right_team','id');
    // }
}
