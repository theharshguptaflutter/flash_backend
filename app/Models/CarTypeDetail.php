<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarTypeDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'car_type_id','car_name','color'
    ];
}
