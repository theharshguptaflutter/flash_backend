<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassangerRating extends Model
{
    use HasFactory;
    protected $fillable = [
        'passanger_id','driver_id','rating'   
    ];

}
