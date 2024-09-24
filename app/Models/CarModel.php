<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    use HasFactory;
    protected $fillable = [
        'car_make_id','name','status'
    ];

    public function car_make()
    {
        return $this->hasOne('App\Models\CarMake', 'id', 'car_make_id');
    }
}
