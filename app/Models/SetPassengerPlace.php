<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetPassengerPlace extends Model
{
    use HasFactory;
    protected $fillable = [
        'passenger_id', 'place_name','address','latitude','longitude','icon','status','created_at','updated_at' 
   ];
}
