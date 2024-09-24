<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'passenger_id','driver_id','ride_id','rating','comment','improve_type','tip_amount','charity_amount'
    ];
}
