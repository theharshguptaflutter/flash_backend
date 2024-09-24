<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassengerRideDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'passenger_id','driver_id','from_address','to_address','from_latitude',
        'from_longitude','to_latitude','to_longitude','seat_no','schedule_date','schedule_time','car_type','distance',
        'estimated_distance','total_distance','fare','estimated_fare','total_fare','coupon_id','discount','ride_rating','paid_by','paid_status',
        'cancel_ride_by','cancel_reason','refund_status','trip_status','start_trip_date','end_trip_date','ride_time','otp','subscription_charge','npo','card_base','card_charge','driver_earning'
    ];

    public function CarCategory()
    {
        return $this->hasOne('App\Models\CarType', 'id', 'car_type');
    }

    // public function DriverRating()
    // {
    //     return $this->hasOne('App\Models\DriverReview', 'ride_id', 'id');
    // }

    public function Driver()
    {
        return $this->belongsTo('App\Models\User', 'driver_id', 'id')->withTrashed();
    }

    public function Passenger()
    {
        return $this->belongsTo('App\Models\User', 'passenger_id', 'id');
    }

    public function getRideHistory()
    {
        return $this->hasOne(RideHistory::class, 'ride_id','id');
    }
}
