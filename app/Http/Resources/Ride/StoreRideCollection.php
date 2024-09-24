<?php

namespace App\Http\Resources\Ride;

use Illuminate\Http\Resources\Json\ResourceCollection;

class StoreRideCollection extends ResourceCollection
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
            'driver_id'     => $this->driver_id,
            'ride_id'       => $this->ride_id,
            'step_number'   => $this->step_number,
            'status'        => $this->status,
        ];
    }
}
