<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'device_id', 'device_type','fcm_token','created_at','updated_at' 
   ];

    // public function UserTokens()
    // {
    //     return $this->hasMany('App\Models\UserToken', 'user_id', 'id');
    // }
    
    public function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
