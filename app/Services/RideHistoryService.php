<?php

namespace App\Services;

use App\Models\PassengerRideDetail;
use App\Models\RideHistory;
use Illuminate\Support\Facades\DB;

class RideHistoryService
{
    /**
     * Store ride history
     * @param array $request validated
     * @return $rideHistory
     */
    public function store(array $request) 
    {
        return DB::transaction(function () use ($request) {
            // $rideHistory = new RideHistory();
            $rideHistory = RideHistory::create($request);
            return $rideHistory;
        }); 
    }

    /**
     * Send current ride step to driver
     * @author Subhodeep Bhattacharjee <subhodeepbhatt@technoexponent.com>
     * @param array $request validated
     * @return array response with status code
     */
    public function currentRideStep(array $request) 
    {
        return RideHistory::with('passangerRideDetails', 'passangerRideDetails.Passenger')->where('ride_id', $request['ride_id'])->latest('step_number')->first();
    }

    /**
     * When ride is complete return driver actual price
     * @author Created By => Subhodeep Bhattacharjee <subhodeepbhat@technoexponent.com>
     * @param object $request
     * @return array $response with code
    */
    public function rideComplete($request)
    {
        $start_time = date('Y-m-d')." 00:00:00";
        $end_time = date('Y-m-d')." 23:59:59";
        
        $checkTotalSubscriptionDeduction = $this->getSubscriptionCharge($request, $start_time, $end_time);
        // check tot subscription_charge
        if($checkTotalSubscriptionDeduction < 50){
            $calculateTenPercent = ($request->fare*10.00)/100;
            $calculateTotal = ($checkTotalSubscriptionDeduction+$calculateTenPercent);
            if($calculateTotal <= 50) {
                $subscription_fee = $calculateTenPercent;
            } else{ 
                $subscription_fee = (50-$checkTotalSubscriptionDeduction);
            }	
        } else {
            $response['status'] = 200;
            $response['data'] = number_format((float)$request->fare, 2, '.', ''); 
            $response['message'] = 'Ride Completed';
            // return response()->json($response);
            return $response;
        }

        $npo_charge = 1;
        if($request->payment_mode == 1) {
            $card_base = 0.50;
            $card_charge = ($request->fare*0.25)/100;
        }
        if($request->payment_mode == 2){
            $card_base = $card_charge = 0;
        }
        
        $driver_earning = ($request->fare)-($subscription_fee+$card_base+$card_charge+$npo_charge);
        PassengerRideDetail::where('id',$request->ride_id)->update([
            'driver_earning'        => $driver_earning,
            'subscription_charge'   => $subscription_fee,
            'npo'                   =>$npo_charge,
            'card_base'             =>$card_base,
            'card_charge'=>$card_charge
        ]);
        $response['status'] = 200;
        $response['data'] = number_format((float)$driver_earning, 2, '.', '');
        $response['message'] = 'Ride Completed';
        return $response;
    }

    /**
     * Get subscription charge per day per driver
     * @param object $request
     * @param dateTime $start_time
     * @param dateTime $end_time
     * @return int SUM(subsctiption_charge)
     */
    private function getSubscriptionCharge($request, $start_time, $end_time) 
    {
        return PassengerRideDetail::where('driver_id',$request->driver_id)->whereBetween('ride_time',[$start_time,$end_time])->sum('subscription_charge');
    }
}