<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVerification extends Model
{
    use HasFactory;

    // protected $table='user_otp';
    protected $fillable = [
        'user_id','mobile_verify_code','verification_type'
    ];
}
