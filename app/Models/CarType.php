<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','color','hex_code','model','number_plate','year'
    ];

    public function car_type_details()
    {
        return $this->hasMany('App\Models\CarTypeDetail', 'car_type_id', 'id');
    }
}
