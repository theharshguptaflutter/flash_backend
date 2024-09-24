<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\PaymentStoreRequest;
use Illuminate\Http\Request;
use Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\PassengerPlaceSet;
use Exception;
use App\Services\FCMService;
use Illuminate\Support\Facades\Crypt;
use Spatie\Permission\Models\Role;
use App\Models\PassengerRideDetail;
use App\Models\PassangerTransaction;
use App\Models\DriverCarAvailable;
use App\Models\DriverDetail;
use App\Models\Notification;
use App\Models\UserToken;
use App\Services\PaymentService;
use App\Traits\ApiTrait;
use Illuminate\Support\Facades\Http;
use App\Traits\Firebase;
use Illuminate\Foundation\Auth\AuthenticatesUsers;



class PassengerController extends Controller
{
    use Firebase, AuthenticatesUsers, ApiTrait;
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Description: update profile
     */
    public function sendNotification1(Request $request)
    {
        $user = UserToken::where('user_id', $request->id)->get()->toArray();
        $token = $user[0]['fcm_token'];
        $notification = [
            'title' => 'title',
            'body' => 'body of message.'
        ];
        $extraNotificationData = ["message" => $notification, "moredata" => 'dd'];

        $fcmNotification = [
            //'registration_ids' => $tokenList, //multple token array
            'to'        => $token, //single token
            'notification' => $notification,
            'data' => $extraNotificationData
        ];

        return $this->firebaseNotification($fcmNotification);
    }
    public function sendNotifications(Request $request)
    {
        $user = UserToken::where('user_id', $request->id)->get()->toArray();
        $ftoken = $user[0]['fcm_token'];

        $noti = array("body" => "Test mess detail", "title" => "Test Message", "sound" => "default");
        $token = isset($ftoken) ? $ftoken : "";
        if ($token != null && $token != "") {

            $data = array(
                "sound" => "default",
                "body" => "Test mess detail",
                "title" => "Test Message",
                "content_available" => true,
                "priority" => "high",
                // "passengerRecord"=> $authUser,
                // "rideDetails"=> $initiateRideRecord,
            );
            $message_status = $this->sendNotification($token, $data, $noti);
        }
        return $message_status;
    }

    public function updateProfile()
    {
        $user = Auth::user();
        $input = request()->all();
        $rules = [
            'fullName' => 'required',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response['status'] = 400;
            $response['error'] = $validator->errors();
            return response()->json($response, 400);
        } else {
            if ($user) {
                $user->full_name = $input['fullName'];
                $user->profile_picture = isset($input['profilePicture']) ? $input['profilePicture'] : $user->profile_picture;
                $user->save();
                $response['status'] = 200;
                $response['message'] = 'Profile details updated successfully.';
                $response['user_profile'] = $user;
            } else {
                $response['status'] = 400;
                $response['error'] = "Something went Wrong!";
            }
            return response()->json($response);
        }
    }

    /**
     * Description: Change password
     */
    public function changePassengerPassword()
    {
        $user = Auth::user();
        $input = request()->all();
        $rules = [
            'oldPassword' => 'required',
            'newPassword' => 'required|min:6',
            'confirmPassword' => 'required|same:newPassword',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response, 400);
        } else {
            if (Hash::check($input['oldPassword'], $user->password)) {
                if ($input['oldPassword'] == $input['newPassword']) {
                    $response['status'] = 400;
                    $response['error'] = "Old password and New password can't be same";
                    return response()->json($response, 400);
                }
                User::where('id', Auth::user()->id)->update([
                    'password' => Hash::make(trim($input['newPassword'])),
                ]);
                $response['status'] = 200;
                $response['message'] = "Password Changed Successfully";
                return response()->json($response, 200);
            } else {
                $response['status'] = 400;
                $response['error'] = "Incorrect Old Password";
                return response()->json($response, 400);
            }
        }
    }
    public function firebase(Request $request)
    {
        $user = UserToken::where('user_id', $request->id)->get()->toArray();
        $fcmData = FCMService::send(
            $user[0]['fcm_token'],
            [
                'title' => 'yTest Message',
                'body' => 'your body',
            ]
        );
        return [$fcmData, $user];
    }

    public function addCurrentLocation()
    {
        $user = Auth::user();
        $userId = Auth::user()->id;
        $input = request()->all();
        $rules = [
            'lat' => 'required',
            'long' => 'required',
            'location' => 'required',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response, 400);
        } else {
            $passenger = User::where(['id' => $userId, 'user_type' => "P"])->first();
            if (isset($passenger) && ($passenger != "")) {
                $passenger->cur_lat = $input['lat'];
                $passenger->cur_long = $input['long'];
                $passenger->location = $input['location'];
                $passenger->save();
                $response['status'] = 200;
                $response['message'] = "Current location added Successfully";
                return response()->json($response, 200);
            } else {
                $response['status'] = 400;
                $response['error'] = "Current location not added Successfully";
                return response()->json($response, 400);
            }
        }
    }
    public function fetchDriverRideHistory()
    {
        $user = Auth::user();
        $userId = Auth::user()->id;
        $rideDetails = PassengerRideDetail::with('Driver')->with('Driver.DriverDetails.driver_document')->with('CarCategory')->with('Passenger')->with('CarCategory.car_type_details')->where('passenger_id', $userId)->where('total_fare', '!=', 0)->where('ride_rating', '>', 0)->orderBy('updated_at', 'DESC')->get();
        $cancelRideDetails = PassengerRideDetail::with('Driver')->with('Driver.DriverDetails.driver_document')->with('CarCategory')->with('Passenger')->with('CarCategory.car_type_details')->where('passenger_id', $userId)->where('cancel_ride_by', '>', '0')->orderBy('id', 'DESC')->get();
        $response['status'] = 200;
        $response['upcomingRide'] = [];
        $response['completedRide'] = isset($rideDetails) ? $rideDetails : [];
        $response['deletedRide'] = isset($cancelRideDetails) ? $cancelRideDetails : [];
        $response['message'] = "fetch successfully.";
        return response()->json($response, 200);
    }


    public function addRideInfoDetails()
    {
        $userIdArr = [];
        $list = [];
        $userId = Auth::user()->id;
        $input = request()->all();
        $rules = [
            'fromAddress' => 'required',
            'toAddress' => 'required',
            'fromLat' => 'required',
            'fromLng' => 'required',
            'toLat' => 'required',
            'toLng' => 'required',
            'seat' => 'required',
            'scheduleDate' => 'required',
            'scheduleTime' => 'required',
            'carType' => 'required',
            'distance' => 'required',
            'fare' => 'required',
            'estimatedFare' => 'nullable',
            'rideId' => 'nullable',
            //'couponId' => 'nullable',
            //'paidBy' => 'required', //1 = cash, 2= card           

        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response, 400);
        } else {
            $rideId = isset($input['rideId']) ? $input['rideId'] : "";
            if (isset($rideId) && ($rideId != "" && $rideId != null)) {
                $rideRecord = PassengerRideDetail::where('id', $rideId)->where('passenger_id', $userId)->first();
                if ($rideRecord) {

                    $latitude = $input['fromLat']; //$initiateRideRecord->from_latitude; // "37.78583400";  //your current lat
                    $longitude = $input['fromLng']; //$initiateRideRecord->from_longitude; //"-122.40641700"; //your current long
                    $distance = 5;
                    $queryRecord = DB::select("SELECT *, (((acos(sin((" . $latitude . "*pi()/180)) * sin((`cur_lat`*pi()/180)) + cos((" . $latitude . "*pi()/180)) * cos((`cur_lat`*pi()/180)) * cos(((" . $longitude . "- `cur_long`) * pi()/180)))) * 180/pi()) * 60 * 1.1515 * 1.609344) as distance FROM `users` having distance <= " . $distance . " LIMIT 5");

                    if (isset($queryRecord) && count($queryRecord) > 0) {
                        foreach ($queryRecord as $keys => $vals) {
                            $userType = $vals->user_type;
                            if ($userType == "D") {
                                // status checking
                                array_push($userIdArr, $vals->id);
                            }
                        }
                    }
                    if (count($userIdArr) > 0) {
                        $list = User::select('*', DB::raw("(MAX(avg_rating)) as high_rating "))->whereIn('id', $userIdArr)
                            ->groupBy('id')->get()->toArray();
                        if (count($list) > 0) {
                            $driver_id = $list[0]['id'];
                            $carAvailable = DriverCarAvailable::where('driver_id', $driver_id)->where('is_available', 'Y')->first();
                            if ($carAvailable) {
                                $rideRecord->driver_id = $driver_id;
                                $rideRecord->from_address = $input['fromAddress'];
                                $rideRecord->to_address = $input['toAddress'];
                                $rideRecord->from_latitude = $input['fromLat'];
                                $rideRecord->from_longitude = $input['fromLng'];
                                $rideRecord->to_latitude = $input['toLat'];
                                $rideRecord->to_longitude = $input['toLng'];
                                $rideRecord->seat_no = $input['seat'];
                                $rideRecord->schedule_date = $input['scheduleDate'];
                                $rideRecord->schedule_time = $input['scheduleTime'];
                                $rideRecord->car_type = $input['carType'];
                                $rideRecord->distance = $input['distance'];
                                $rideRecord->total_distance = $input['distance'];
                                $rideRecord->fare =  $input['fare'];
                                $rideRecord->total_fare = $input['fare'];
                                $rideRecord->trip_status = 5; //Initiate trip
                                $rideRecord->save();

                                // DriverCarAvailable condition
                                $driverRecord = DriverDetail::with(['user' => function ($query4) {
                                    $query4->select('id', 'full_name', 'email', 'profile_picture', 'country_code', 'mobile', 'avg_rating');
                                }])
                                    ->with('DriverVehicleCarType')
                                    ->with('CarMake')->with('CarModel')
                                    ->where('user_id', $driver_id)
                                    ->first();
                                $response['status'] = 200;
                                $response['driverRecord'] = $driverRecord;
                                $response['rideRecord'] = $rideRecord;
                                $response['message'] = 'Trip updated successfully.';
                                return response()->json($response);
                            } else {
                                $response['status'] = 400;
                                $response['error'] = "Driver not available in your area!";
                                return response()->json($response, 400);
                            }
                        } else {
                            $response['status'] = 400;
                            $response['error'] = "Driver not available in your area!";
                            return response()->json($response, 400);
                        }
                    } else {
                        $response['status'] = 400;
                        $response['error'] = "Driver not available in your area!";
                        return response()->json($response, 400);
                    }
                } else {
                    $response['status'] = 400;
                    $response['error'] = "Trip not updated successfully.";
                    return response()->json($response, 400);
                }
            } else {
                $latitude = $input['fromLat']; //$initiateRideRecord->from_latitude; // "37.78583400";  //your current lat
                $longitude = $input['fromLng']; //$initiateRideRecord->from_longitude; //"-122.40641700"; //your current long
                $distance = 5;
                $queryRecord = DB::select("SELECT *, (((acos(sin((" . $latitude . "*pi()/180)) * sin((`cur_lat`*pi()/180)) + cos((" . $latitude . "*pi()/180)) * cos((`cur_lat`*pi()/180)) * cos(((" . $longitude . "- `cur_long`) * pi()/180)))) * 180/pi()) * 60 * 1.1515 * 1.609344) as distance FROM `users` having distance <= " . $distance . " LIMIT 5");

                if (isset($queryRecord) && count($queryRecord) > 0) {
                    foreach ($queryRecord as $keys => $vals) {
                        $userType = $vals->user_type;

                        if ($userType == "D") {
                            // status checking
                            array_push($userIdArr, $vals->id);
                        }
                    }
                }
                if (count($userIdArr) > 0) {
                    $list = User::select('*', DB::raw("(MAX(avg_rating)) as high_rating "))->whereIn('id', $userIdArr)
                        ->groupBy('id')->get()->toArray();
                    if (count($list) > 0) {
                        $driver_id = $list[0]['id'];
                        $carAvailable = DriverCarAvailable::where('driver_id', $driver_id)->where('is_available', 'Y')->first();
                        if ($carAvailable) {
                            $passengerRide = new PassengerRideDetail;
                            $passengerRide->passenger_id = $userId;
                            $passengerRide->driver_id = $driver_id;
                            $passengerRide->from_address = $input['fromAddress'];
                            $passengerRide->to_address = $input['toAddress'];
                            $passengerRide->from_latitude = $input['fromLat'];
                            $passengerRide->from_longitude = $input['fromLng'];
                            $passengerRide->to_latitude = $input['toLat'];
                            $passengerRide->to_longitude = $input['toLng'];
                            $passengerRide->seat_no = $input['seat'];
                            $passengerRide->schedule_date = $input['scheduleDate'];
                            $passengerRide->schedule_time = $input['scheduleTime'];
                            $passengerRide->car_type = $input['carType'];
                            $passengerRide->distance = $input['distance'];
                            $passengerRide->total_distance = $input['distance'];
                            $passengerRide->fare =  $input['fare'];
                            $passengerRide->total_fare = $input['fare'];
                            $passengerRide->trip_status = 5; //Initiate trip
                            $passengerRide->save();

                            // DriverCarAvailable condition
                            $driverRecord = DriverDetail::with(['user' => function ($query4) {
                                $query4->select('id', 'full_name', 'email', 'profile_picture', 'country_code', 'mobile', 'avg_rating');
                            }])
                                ->with('driver_document')
                                ->with('DriverVehicleCarType')
                                ->with('CarMake')->with('CarModel')
                                ->where('user_id', $driver_id)
                                ->first();
                            $response['status'] = 200;
                            $response['driverRecord'] = $driverRecord;
                            $response['rideRecord'] = $passengerRide;
                            $response['message'] = 'Trip added successfully.';
                            return response()->json($response);
                        } else {
                            $response['status'] = 400;
                            $response['error'] = "Driver not available in your area!";
                            return response()->json($response, 400);
                        }
                    } else {
                        $response['status'] = 400;
                        $response['error'] = "Driver not available in your area!";
                        return response()->json($response, 400);
                    }
                } else {
                    $response['status'] = 400;
                    $response['error'] = "Driver not available in your area!";
                    return response()->json($response, 400);
                }
            }
        }
    }
    public function notificationList(Request $request)
    {
        $userid = Auth::user()->id;
        $rideDetails = Notification::where('receiver_id', $userid)->where('created_at', '!=', null)->orderBy('created_at', 'DESC')->get();
        $response['status'] = 200;
        $response['data'] = $rideDetails;
        $response['success'] = "Notification List";
        return response()->json($response, 200);
    }
    public function calculateBilling(Request $request)
    {
        $id = $request->id;
        $rideDetails = PassengerRideDetail::find($id);
        $response['status'] = 200;
        $response['data'] = $rideDetails->total_fare;
        $response['success'] = "Calculated Fare";
        return response()->json($response, 200);
    }

    public function addPaymentDetails()
    {
        $userIdArr = [];
        $list = [];
        $authUser = Auth::user();
        $userId = Auth::user()->id;
        $input = request()->all();
        $rules = [
            'rideId' => 'required',
            'couponId' => 'nullable',
            'paidBy' => 'required', //1 = cash, 2= card           

        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response, 400);
        } else {
            $otp = rand(100, 999) . mt_rand(00, 99);
            $initiateRideRecord = PassengerRideDetail::select('*')->where('id', $input['rideId'])->where('passenger_id', $userId)->first();
            if (isset($initiateRideRecord)) {
                $initiateRideRecord->paid_by = $input['paidBy'];
                $initiateRideRecord->trip_status = 6; //Request for trip
                if ($initiateRideRecord->save()) {
                    $passenger = User::where('id', $userId)->first();
                    if (isset($passenger)) {
                        $passenger->ride_otp = $otp;
                        $passenger->save();
                    }
                    $driver = User::where('id', $initiateRideRecord->driver_id)->first();
                    if (isset($driver)) {
                        $driver->ride_otp = $otp;
                        $driver->save();
                    }

                    //notification                    
                    $recever_detail = UserToken::where('user_id', $initiateRideRecord->driver_id)->first();
                    $login_type = isset($recever_detail->device_type) ? $recever_detail->device_type : 'A';
                    $msg = $authUser->full_name . ' requesting a trip from ' . $initiateRideRecord->from_address . ' to ' . $initiateRideRecord->to_address;
                    $title = 'Ride Request';

                    $nitificationRecord = Notification::where('ride_id', $initiateRideRecord->id)->first();
                    if ($nitificationRecord) {
                        $nitificationRecord->sender_id = $userId;
                        $nitificationRecord->receiver_id = $initiateRideRecord->driver_id;
                        $nitificationRecord->is_read = 0;
                        $nitificationRecord->save();
                    } else {
                        $notification_data['sender_id'] = $userId;
                        $notification_data['receiver_id'] = $initiateRideRecord->driver_id;
                        $notification_data['notification_type'] = 1; //request trip notification
                        $notification_data['is_read'] = 0;
                        $notification_data['ride_id'] = $initiateRideRecord->id;
                        Notification::create($notification_data);
                    }


                    $noti = array("body" => $msg, "title" => $title, "sound" => "default");
                    $token = isset($recever_detail->fcm_token) ? $recever_detail->fcm_token : "";
                    if ($token != null && $token != "") {

                        $data = array(
                            "sound" => "default",
                            "body" => $msg,
                            "title" => $title,
                            "content_available" => true,
                            "priority" => "high",
                            "passengerRecord" => $authUser,
                            "rideDetails" => $initiateRideRecord,
                        );
                        $message_status = $this->sendNotification($token, $data, $noti);
                    }

                    // notification
                    $response['status'] = 200;
                    $response['passengerOtp'] = isset($passenger->ride_otp) ? $passenger->ride_otp : 0;
                    $response['rideRecord'] = $initiateRideRecord;
                    $response['message'] = 'Payment done successfully.';
                    return response()->json($response);
                } else {
                    $response['status'] = 400;
                    $response['error'] = "Payment not done!!";
                    return response()->json($response, 400);
                }
            } else {
                $response['status'] = 400;
                $response['error'] = "Record not found";
                return response()->json($response, 400);
            }
        }
    }



    function sendNotification($tokens, $data, $notification = NULL)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array(
            'notification' => $notification,
            'registration_ids' => array($tokens),
            'priority' => 'high',
            'data' => $data
        );

        $headers = array(
            'Authorization:key = ' . env('FCM_PASSENGER_SERVER_KEY'),
            'Content-Type: application/json'
        );
        return $this->getCurlData($url, json_encode($fields), $headers);
    }

    public function getCurlData($url, $poststr, $headers = NULL)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $poststr);
        if ($headers != NULL) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }
        //curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
        $curlData = curl_exec($curl);
        if (curl_errno($curl)) {
            return curl_error($curl);
        } else {
            curl_close($curl);
            return $curlData;
        }
    }

    public function addNewPlace(Request $request)
    {
        $user = Auth::user();
        $userId = Auth::user()->id;
        $input = $request->all();
        $rules = [
            'placeName' => 'required',
            'address' => 'required',
            'lat' => 'required',
            'long' => 'required',
            'icon' => 'required',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response, 400);
        } else {
            $checkplaces = PassengerPlaceSet::where(['latitude' => $input['lat'], 'longitude' => $input['long']])->first();
            if (isset($checkplaces)) {
                $response['status'] = 400;
                $response['error'] = "You already added this address.";
                return response()->json($response, 400);
            } else {
                $place = new PassengerPlaceSet;
                $place->passenger_id = $userId;
                $place->place_name = $input['placeName'];
                $place->address = $input['address'];
                $place->latitude = $input['lat'];
                $place->longitude = $input['long'];
                $place->icon = $input['icon'];
                $place->save();
                $response['status'] = 200;
                $response['message'] = "Place added successfully.";
                return response()->json($response, 200);
            }
        }
    }

    public function deletePlace(Request $request)
    {
        $user = Auth::user();
        $userId = Auth::user()->id;
        $input = $request->all();
        $rules = [
            'placeId' => 'required',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response, 400);
        } else {
            $checkplaces = PassengerPlaceSet::where(['id' => $input['placeId'], 'passenger_id' => $userId])->first();
            if (isset($checkplaces)) {
                $deletePlace = PassengerPlaceSet::where("id", $input['placeId'])->delete();
                $response['status'] = 200;
                $response['message'] = 'Place deleted successfully.';
                return response()->json($response);
            } else {
                $response['status'] = 400;
                $response['error'] = "Passenger Place not found";
                return response()->json($response, 400);
            }
        }
    }

    public function editPlace(Request $request)
    {
        $user = Auth::user();
        $userId = Auth::user()->id;
        $input = $request->all();
        $rules = [
            'placeId' => 'required',
            'placeName' => 'required',
            'address' => 'required',
            'lat' => 'required',
            'long' => 'required',
            'icon' => 'required',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response, 400);
        } else {
            $checkplaces = PassengerPlaceSet::where(['id' => $input['placeId'], 'passenger_id' => $userId])->first();
            if (isset($checkplaces)) {
                $checkplaces->place_name = $input['placeName'];
                $checkplaces->address = $input['address'];
                $checkplaces->latitude = $input['lat'];
                $checkplaces->longitude = $input['long'];
                $checkplaces->icon = $input['icon'];
                $checkplaces->save();
                $response['status'] = 200;
                $response['message'] = "Place updated successfully.";
                return response()->json($response, 200);
            } else {
                $response['status'] = 400;
                $response['error'] = "Place not updated successfully";
                return response()->json($response, 400);
            }
        }
    }

    public function newPlaceList()
    {
        $user = Auth::user();
        $userId = Auth::user()->id;
        $placeList = PassengerPlaceSet::select('id', 'passenger_id', 'place_name', 'address', 'latitude', 'longitude', 'icon')->where('passenger_id', $userId)->where('status', 'A')->get()->toArray();
        $response['status'] = 200;
        $response['placeList'] = $placeList;
        $response['message'] = "fetch successfully.";
        return response()->json($response, 200);
    }
    
    /**
     * Integrate payment gateway 
     * @param App\Http\Requests\Payment\PaymentStoreRequest $request
     * @return apiResponse $response with messane ans error code
     */
    public function payGateInitiate(PaymentStoreRequest $request)
    {
        $response = $this->paymentService->initCustomerPayment($request->validated());
        
        if($response == 'Something went Wrong') {
            return $this->apiResponse(400, __('Something went Wrong'), [], [], []);
        }

        return $this->apiResponse(200, __('Payment initiated successfully'), $response, [], []);
    }


    public function pg_response(Request $request)
    {
        $transaction = PassangerTransaction::where('pay_request_id', $request->PAY_REQUEST_ID)
            ->first();

        if (empty($transaction)) {
            return "Something went wrong";
        } else {
            $response['status'] = 200;
            $response['error'] = "Payment Done";
            $response['transaction'] = $transaction;
            return response()->json($response, 200);
        }
    }
}
