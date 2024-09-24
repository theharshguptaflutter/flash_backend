<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarMake extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','status'
    ];

    public function car_model()
    {
        return $this->hasMany('App\Models\CarModel', 'car_make_id', 'id');
    }
}
