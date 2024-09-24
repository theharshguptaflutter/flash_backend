<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassengerPlaceSet extends Model
{
    use HasFactory;
    protected $table = 'passenger_place_set';
    protected $fillable = [
        'passenger_id', 'place_name','address','latitude','longitude','icon','status','created_at','updated_at' 
   ];
}
