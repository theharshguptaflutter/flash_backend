<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RideHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'driver_id', 'ride_id','step_number','step_name',
        'status','created_at','updated_at'
    ];

    public function passangerRideDetails()
    {
        return $this->belongsTo(PassengerRideDetail::class, 'ride_id','id');
    }
}
