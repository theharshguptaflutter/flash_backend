<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Models\DriverReview;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use App\Models\PassengerRideDetail;
use App\Models\TaxiRideDriverLocationCron;
use App\Models\User;
use DateTime;


class RideController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){

    }

    // public function addDriverReviewddd(){
    //     if((Auth::user())){            
    //         if(Auth::user()->hasRole('customer')){
    //             $query = "SELECT ut.userId, ut.taxiTypeId, ut.currLat, ut.currLng, ut.currLocality, ut.available, ut.forHire, u.driverApproval, (3959 * acos(cos(radians('" + lat + "')) * cos(radians(currLat)) * cos( radians(currLng) - radians('" + lng + "')) + sin(radians('" + lat + "')) * sin(radians(currLat)))) AS distance FROM " + USER_TAXI_TABLE + " AS ut JOIN "+USER_TABLE+" AS u ON ut.userId = u.id WHERE ut.id="+onTaxiRide.userTaxiId+" GROUP BY ut.userId";
    //         }
    //     }
    // }

    //let query = "SELECT ut.userId, ut.taxiTypeId, ut.currLat, ut.currLng, ut.currLocality, ut.available, ut.forHire, u.driverApproval, (3959 * acos(cos(radians('" + lat + "')) * cos(radians(currLat)) * cos( radians(currLng) - radians('" + lng + "')) + sin(radians('" + lat + "')) * sin(radians(currLat)))) AS distance FROM " + USER_TAXI_TABLE + " AS ut JOIN "+USER_TABLE+" AS u ON ut.userId = u.id WHERE ut.available='Y' AND ut.forHire='Y' AND u.driverApproval = 'Y' AND u.is_online = 'Y' GROUP BY ut.userId HAVING distance<=50";

    /**
     * Description: Driver Available for Ride
    */

    public function getDriverRideList() { 
        if((Auth::user())){            
            if((Auth::user()->hasRole('customer')) || (Auth::user()->hasRole('driver'))){

                

            }else{
                $response['status'] = 400;
                $response['error'] = "Invalid User!";
                return response()->json($response,400);
            }
        }else{
            $response['status'] = 400;
            $response['error'] = "Invalid User!";
            return response()->json($response,400);
        }

    }

    

    public function addDriverReview() {
        if((Auth::user())){            
            if(Auth::user()->hasRole('customer')){
                $newArr = [];
                $input = request()->all();
                $rules = [
                    'driverId' => 'required',
                    'rideId' => 'required',
                    'rating' => 'required|numeric|min:1|max:5',
                    'comment '=> 'nullable',
                    'improveType' => 'nullable',
                    'tipAmount' => 'nullable',
                    'charityAmount '=> 'nullable',
                ];
                $validator = Validator::make($input, $rules);
                if ($validator->fails()) {
                    $response = [
                        'status' => 400,
                        'error' => $validator->errors()
                    ];
                    return response()->json($response,400);
                }else{
                    $review = DriverReview::where("ride_id",$input['rideId'])->where("driver_id",$input['driverId'])->where("passenger_id",Auth::user()->id)->first();
                    if(isset($review)){
                        $response['status'] = 400;
                        $response['error'] = "Review already submitted for this ride .";
                        return response()->json($response,400);
                    }
                    $driverReview = new DriverReview;
                    $driverReview->passenger_id = Auth::user()->id; 
                    $driverReview->driver_id = $input['driverId'];
                    $driverReview->ride_id = $input['rideId'];
                    $driverReview->rating = $input['rating'];
                    $driverReview->comment = isset($input['comment'])?$input['comment']:"";
                    $driverReview->improve_type = isset($input['improveType'])?$input['improveType']:"";
                    $driverReview->tip_amount = isset($input['tipAmount'])?$input['tipAmount']:0.00;
                    $driverReview->charity_amount = isset($input['charityAmount'])?$input['charityAmount']:0.00;
                    $driverReview->save();

                    $driverReviewRating = DriverReview::select('rating')->where('driver_id',$input['driverId'])->get()->toArray();
                    if(count($driverReviewRating)>0){
                        foreach($driverReviewRating as $keys => $vals){
                            array_push($newArr,$vals['rating']);
                        }
                    }
                    
                    $countArr = count($driverReviewRating);
                    $sumRating = array_sum($newArr);
                    $averageRating = $sumRating/$countArr;

                    $driverRecord =  User::where('id',$input['driverId'])->first();
                    if(isset($driverRecord)){
                        $driverRecord->avg_rating = $averageRating;
                        $driverRecord->save();
                    }               

                    $response['status'] = 200;
                    $response['message'] = 'Review added successfully.';
                    return response()->json($response);
                }
            }else{
                $response['status'] = 400;
                $response['error'] = "Invalid User!";
                return response()->json($response,400);
            }
        }else{
            $response['status'] = 400;
            $response['error'] = "Invalid User!";
            return response()->json($response,400);
        }
    }

    public function passengerRideListingNew(){

    }

    public function passengerRideListing(){
        date_default_timezone_set('Asia/Kolkata');

        //echo date( 'd-m-Y h:i:s', time ());

        $current_date  = Carbon::now()->format('Y-m-d'); 
        $cutrrent_time = Carbon::now()->format('G:i');
        //echo $cutrrent_time ; exit;
        
        //$monthDate = Carbon::parse($current_date)->addDays(30)->format('Y-m-d');      
        // $start = new Carbon('first day of next month');
        // $startFornextOneMonth = $start->format('Y-m-d');
        // $end = new Carbon('last day of next month');
        // $endFornextOneMonth = $end->format('Y-m-d');
        //echo $start;echo "=========================="; echo $end;exit;
        // date_default_timezone_set('America/New_York');
        // $currTime = time();
        // echo $newDateTime = date('H:i', $currTime);

        $driverArr = [];
        $rideArr = [];
        if((Auth::user())){            
            if(Auth::user()->hasRole('customer')){
                $passengerUpcomingRide = PassengerRideDetail::with(['CarCategory' => function($query){
                        $query->select('id', 'name');
                    }])
                    ->with(['Driver' => function($query){
                        $query->select('id','full_name','profile_picture','avg_rating');
                    }])->select('id','passenger_id','driver_id','from_address','to_address','seat_no','schedule_date','schedule_time','car_type',
                    'distance','estimated_distance','total_distance','fare','estimated_fare','total_fare','coupon_id','paid_by','trip_status','start_trip_date','end_trip_date')->where('passenger_id', Auth::user()->id)->where('schedule_date','>=',$current_date)
                    ->where('schedule_time','>=',$cutrrent_time) //check
                    ->orderBy('schedule_date','asc')
                    ->get()->toArray();

                $passengerCompletedRide = PassengerRideDetail::with(['CarCategory' => function($query){
                    $query->select('id', 'name');
                }])
                ->with(['Driver' => function($query){
                    $query->select('id','full_name','profile_picture','avg_rating');
                }])->select('id','passenger_id','driver_id','from_address','to_address','seat_no','schedule_date','schedule_time','car_type',
                'distance','estimated_distance','total_distance','fare','estimated_fare','total_fare','coupon_id','paid_by','trip_status','start_trip_date','end_trip_date')
                ->where('passenger_id', Auth::user()->id)->where('schedule_date','<=',$current_date )->where('trip_status',2)
                ->orderBy('schedule_date','asc')
                ->get()->toArray();


                $passengerDeleteRide = PassengerRideDetail::with(['CarCategory' => function($query){
                    $query->select('id', 'name');
                }])
                ->with(['Driver' => function($query){
                    $query->select('id','full_name','profile_picture','avg_rating');
                }])->select('id','passenger_id','driver_id','from_address','to_address','seat_no','schedule_date','schedule_time','car_type',
                'distance','estimated_distance','total_distance','fare','estimated_fare','total_fare','coupon_id','paid_by','trip_status','start_trip_date','end_trip_date')->where('passenger_id', Auth::user()->id)->where('trip_status',3)
                ->orderBy('schedule_date','asc')
                ->get()->toArray();

                $response['status'] = 200;
                $response['upcomingRide'] = $passengerUpcomingRide;
                $response['completedRide'] = $passengerCompletedRide;
                $response['deletedRide'] = $passengerDeleteRide;
                $response['message'] = 'Individual passenger ride fetch successfully.';
                return response()->json($response);                    
            }else{
                $response['status'] = 400;
                $response['error'] = "Invalid User!";
                return response()->json($response,400);
            }
        }else{
            $response['status'] = 400;
            $response['error'] = "Invalid User!";
            return response()->json($response,400);
        }

    }


}
