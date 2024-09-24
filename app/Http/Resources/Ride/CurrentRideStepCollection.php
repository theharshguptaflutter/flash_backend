<?php

namespace App\Http\Resources\Ride;

use Illuminate\Http\Resources\Json\JsonResource;
// use Illuminate\Http\Resources\Json\ResourceCollection;

class CurrentRideStepCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'driver_id'         => $this->driver_id,
            'ride_id'           => $this->ride_id,
            'step_number'       => $this->step_number,
            'status'            => $this->status,
            'from_address'      => $this->passangerRideDetails->from_address,
            'to_address'        => $this->passangerRideDetails->to_address,
            'from_latitude'     => $this->passangerRideDetails->from_latitude,
            'from_longitude'    => $this->passangerRideDetails->from_longitude,
            'to_latitude'       => $this->passangerRideDetails->to_latitude,
            'to_longitude'      => $this->passangerRideDetails->to_longitude,
            'seat_no'           => $this->passangerRideDetails->seat_no,
            "distance"          => $this->passangerRideDetails->distance,
            "fare"              => $this->passangerRideDetails->fare,
            "total_fare"        => $this->passangerRideDetails->total_fare,
            "discount"          => $this->passangerRideDetails->discount,
            "coupon_id"         => $this->passangerRideDetails->coupon_id,
            "ride_rating"       => $this->passangerRideDetails->ride_rating,
            "paid_by"           => $this->passangerRideDetails->paid_by,
            "paid_status"       => $this->passangerRideDetails->paid_status,
            "trip_status"       => $this->passangerRideDetails->trip_status,
            "cancel_ride_by"    => $this->passangerRideDetails->cancel_ride_by,
            'cancel_reason'     => $this->passangerRideDetails->cancel_reason,
            'start_trip_date'   => $this->passangerRideDetails->start_trip_date,
            'end_trip_date'     => $this->passangerRideDetails->end_trip_date,
            'ride_time'         => $this->passangerRideDetails->ride_time,
            'otp'               => $this->passangerRideDetails->otp,
            'subscription_charge'   => $this->passangerRideDetails->subscription_charge,
            'npo'               => $this->passangerRideDetails->npo,
            'full_name'         => $this->passangerRideDetails->Passenger->full_name,
            'email'             => $this->passangerRideDetails->Passenger->email,
            'balance'           => $this->passangerRideDetails->Passenger->balance,
            'country_code'      => $this->passangerRideDetails->Passenger->country_code,
            'mobile'            => $this->passangerRideDetails->Passenger->mobile,
            'profile_picture'   => $this->passangerRideDetails->Passenger->profile_picture,
            'cur_lat'           => $this->passangerRideDetails->Passenger->cur_lat,
            'cur_long'          => $this->passangerRideDetails->Passenger->cur_long,
            'location'          => $this->passangerRideDetails->Passenger->location,
            'is_online'         => $this->passangerRideDetails->Passenger->is_online,
            'avg_rating'        => $this->passangerRideDetails->Passenger->avg_rating,
            'is_covid_accepted' => $this->passangerRideDetails->Passenger->is_covid_accepted,
            'user_type'         => $this->passangerRideDetails->Passenger->user_type,
            'login_type'        => $this->passangerRideDetails->Passenger->login_type,
            'passanger_status'  => $this->passangerRideDetails->Passenger->status,
            'ride_otp'          => $this->passangerRideDetails->Passenger->ride_otp,
            // 'ride_otp'          => $this->passangerRideDetails->Passenger->ride_otp,
        ];
    }
}
