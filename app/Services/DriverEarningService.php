<?php

namespace App\Services;

use App\Models\DriverTransaction;
use App\Models\PassengerRideDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DateTime;

class DriverEarningService
{
    /**
     * When ride is complete return driver actual price
     * @author Created By => Subhodeep Bhattacharjee <subhodeepbhat@technoexponent.com>
     * @param object $request
     * @return array $response with code
     */
    public function myEarningList($request)
    {
        $userId = Auth::user()->id;
        $today = date('Y-m-d');

        // Fetch today earning lists
        $start_today = $today . " 00:00:00";
        $end_today = $today . " 23:59:59";
        $todayEarningList = PassengerRideDetail::where('driver_id', $userId)->where('total_fare', '!=', 0)->whereBetween('ride_time', [$start_today, $end_today])->get();
        $todayList['ride_count'] = count($todayEarningList);
        $todayList['total_price'] = 0;
        $todayList['total_time_in_min'] = 0;
        foreach ($todayEarningList as $value) {
            $todayList['total_price'] = round(($todayList['total_price'] + $value->total_fare), 2);
            $start_datetime = new DateTime($value->ride_time);
            $end_datetime = new DateTime($value->end_trip_date);
            $diff = $start_datetime->diff($end_datetime);
            $todayList['total_time_in_min'] = $todayList['total_time_in_min'] + $diff->i;
        }

        // Fetch earning lists as per start_date and end_date
        $start_date = date('Y-m-d', strtotime($request->start_date));
        $end_date = date('Y-m-d', strtotime($request->end_date));
        $subscription_fees = PassengerRideDetail::where('driver_id', $userId)->where('total_fare', '!=', 0)->whereBetween('ride_time', [$start_date, $end_date])->sum('subscription_charge');
        $driver_earning = PassengerRideDetail::where('driver_id', $userId)->where('total_fare', '!=', 0)->whereBetween('ride_time', [$start_date, $end_date])->sum('driver_earning');
        $dateDiff = abs(round((strtotime($request->end_date) - strtotime($request->start_date)) / 86400));
        $daysData = [];
        $total_trip = $time_online = $total_distance = 0;
        for ($i = 0; $i <= $dateDiff; $i++) {
            $daysData[$i]['date'] = $start_date;
            $start_time = $start_date . " 00:00:00";
            $end_time = $start_date . " 23:59:59";
            $thatDayEarningList = PassengerRideDetail::where('driver_id', $userId)->where('total_fare', '!=', 0)->whereBetween('ride_time', [$start_time, $end_time])->get();
            $daysData[$i]['total_price'] = $daysData[$i]['total_time_in_min'] = 0;
            $total_trip = $total_trip + count($thatDayEarningList);
            if (count($thatDayEarningList) > 0) {
                foreach ($thatDayEarningList as $thatEarning) {
                    $daysData[$i]['total_price'] = round(($daysData[$i]['total_price'] + $thatEarning->total_fare), 2);
                    $start_ridetime = new DateTime($thatEarning->ride_time);
                    $end_ridetime = new DateTime($thatEarning->end_trip_date);
                    $diff = $start_ridetime->diff($end_ridetime);
                    $daysData[$i]['total_time_in_min'] = $daysData[$i]['total_time_in_min'] + $diff->i;
                    $time_online = $time_online + $daysData[$i]['total_time_in_min'] + $diff->i;
                    $total_distance = round(($total_distance + $thatEarning->distance), 2);
                }
            } else {
                $daysData[$i]['total_price'] = 0;
                $daysData[$i]['total_time_in_min'] = 0;
            }
            $date = date_create($start_date);
            date_add($date, date_interval_create_from_date_string("1 days"));
            $start_date = date_format($date, "Y-m-d");
        }
        $driver_payment_history = DriverTransaction::where('user_id', $userId)->where('amount', '!=', null)->first();
        $array['todayList'] = $todayList;
        $array['total_trip'] = $total_trip;
        $array['driver_earning'] = round($driver_earning, 2);
        $array['total_distance'] = $total_distance;
        $array['time_online'] = $time_online;
        $array['subscription_amount'] = ($driver_payment_history) ? round(($driver_payment_history->amount + $subscription_fees), 2) : round(($subscription_fees), 2);
        $array['daysData'] = $daysData;

        $response['status'] = 200;
        $response['message'] = 'Earning Page Details';
        $response['data'] = $array;

        return $response;
    }

    /**
     * Return driver today earnings only
     * @param {NA}
     * @return array $response
     */
    public function todayTotalEarn()
    {
        $user = Auth::user();
        $userId = Auth::user()->id;
        $today = date('Y-m-d');
        $start_time = $today . " 00:00:00";
        $end_time = $today . " 23:59:59";
        //$total = PassengerRideDetail::select('total_fare')->where('driver_id',$userId)->whereBetween('ride_time',[$start_time,$end_time])->sum('total_fare');
        $total = PassengerRideDetail::select('driver_earning')->where('driver_id', $userId)->whereBetween('ride_time', [$start_time, $end_time])->sum('driver_earning');
        
        $response['status'] = 200;
        $response['message'] = "Today Total earning";
        $response['amount'] = number_format((float)$total, 2, '.', '');

        return $response;
    }
}
