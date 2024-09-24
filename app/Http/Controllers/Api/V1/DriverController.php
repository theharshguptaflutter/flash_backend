<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Earning\MyEarningRequest;
use App\Http\Requests\Ride\RideCompleteRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\DriverDetail;
use App\Models\DriverTransaction;
use App\Models\PassangerRating;
use App\Models\PassengerRideDetail;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use \DateTime;
use App\Models\UserToken;
use App\Models\BankDetail;
use App\Services\DriverEarningService;
use App\Services\RideHistoryService;

class DriverController extends Controller
{
    protected $rideHistoryService;
    protected $driverEarningService;
    public function __construct(RideHistoryService $rideHistoryService, DriverEarningService $driverEarningService)
    {
        $this->rideHistoryService = $rideHistoryService;
        $this->driverEarningService = $driverEarningService;
    }


    /**
     * Description: update profile
     */

    public function updateProfile()
    {
        $user = Auth::user();
        $input = request()->all();
        $rules = [
            'fullName' => 'required',
            //'profilePicture' => 'required'
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
    public function changeDriverPassword()
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

    /**
     * Description: Change password
     */
    // public function fetchDriverCarDetails()
    // {
    //     $user = Auth::user();
    //     $userId = Auth::user()->id;
    //     $rideDetails = DriverDetail::with('CarMake')->with('CarModel')->with('DriverVehicleCarType')->with('driver_document')->with('driver_vehicle_inspection_document')->where('user_id', $userId)->first();
    //     $response['status'] = 200;
    //     $response['ride'] = isset($rideDetails) ? $rideDetails : [];
    //     $response['message'] = "fetch successfully.";
    //     return response()->json($response, 200);
    // }

    public function fetchDriverRideHistory()
    {
        $user = Auth::user();
        $userId = Auth::user()->id;
        $rideDetails = PassengerRideDetail::with('Driver')->with('Driver.DriverDetails.driver_document')->with('CarCategory')->with('Passenger')->with('CarCategory.car_type_details')->where('driver_id', $userId)->orderBy('updated_at', 'DESC')->get();
        $response['status'] = 200;
        $response['pastRideList'] = isset($rideDetails) ? $rideDetails : [];
        $response['message'] = "fetch successfully.";
        return response()->json($response, 200);
    }

    public function calculateBilling(Request $request)
    {
        $id = $request->id;
        $fare = $request->fare;
        $timeOfTravel = $request->timeOfTravel;
        PassengerRideDetail::where('id', $id)->update(['total_fare' => $fare, 'end_trip_date' => $timeOfTravel]);
        $response['status'] = 200;
        $response['data'] = number_format((float)$fare, 2, '.', '');
        $response['success'] = "Trip Complete";
        return response()->json($response, 200);
    }

    /**
     * Description: Update Ride Details
     */
    public function updateDriverCarDetails()
    {
        $user = Auth::user();
        $userId = Auth::user()->id;
        $input = request()->all();
        $rules = [
            'owner_name' => 'required|string',
            'id_number' => 'required',
            'make' => 'required',
            'model' => 'required',
            'vehicle_description' => 'required',
            'year' => 'required',
            'registration_number' => 'required',
            'km_reading' => 'required',
            'license_number' => 'required',
            'vin_number' => 'required',
            'exterior_color' => 'required|string',
            'interior_color' => 'required',
            'interior_trim' => 'required',
            'transmission' => 'required',
            'start_date_registration' => 'required',
            'end_date_road_worthy' => 'required',
            'seating_capacity' => 'required',
            'vehicle_license_expiry' => 'required',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response, 400);
        } else {
            $findIdNumber = DriverDetail::where('id_number', $input['id_number'])->where('user_id', '!=', $userId)->first();
            $findRegNumber = DriverDetail::where('registration_number', $input['registration_number'])->where('user_id', '!=', $userId)->first();
            $findLicenseNumber = DriverDetail::where('license_number', $input['license_number'])->where('user_id', '!=', $userId)->first();
            $findVinNumber = DriverDetail::where('vin_number', $input['vin_number'])->where('user_id', '!=', $userId)->first();
            if (isset($findIdNumber) && !empty($findIdNumber)) {
                $response['status'] = 400;
                $response['error'] = 'This Id number already exists.';
                return response()->json($response, 400);
            } else if (isset($findRegNumber) && !empty($findRegNumber)) {
                $response['status'] = 400;
                $response['error'] = 'This Registration number already exists.';
                return response()->json($response, 400);
            } else if (isset($findLicenseNumber) && !empty($findLicenseNumber)) {
                $response['status'] = 400;
                $response['error'] = 'This License number already exists.';
                return response()->json($response, 400);
            } else if (isset($findVinNumber) && !empty($findVinNumber)) {
                $response['status'] = 400;
                $response['error'] = 'This Vin number already exists.';
                return response()->json($response, 400);
            } else {
                $driverDetails = DriverDetail::where('user_id', $userId)->first();
                if (isset($driverDetails)) {
                    $driverDetails->owner_name = $input['owner_name'];
                    $driverDetails->id_number = $input['id_number'];
                    $driverDetails->make = $input['make'];
                    $driverDetails->model = $input['model'];
                    $driverDetails->vehicle_description = $input['vehicle_description'];
                    $driverDetails->year = $input['year'];
                    $driverDetails->registration_number = $input['registration_number'];
                    $driverDetails->km_reading = $input['km_reading'];
                    $driverDetails->license_number = $input['license_number'];
                    $driverDetails->vin_number = $input['vin_number'];

                    $driverDetails->exterior_color = $input['exterior_color'];
                    $driverDetails->interior_color = $input['interior_color'];
                    $driverDetails->interior_trim = $input['interior_trim'];
                    $driverDetails->transmission = $input['transmission'];
                    $driverDetails->start_date_registration = $input['start_date_registration'];
                    $driverDetails->end_date_road_worthy = $input['end_date_road_worthy'];
                    $driverDetails->seating_capacity = $input['seating_capacity'];
                    $driverDetails->vehicle_license_expiry = $input['vehicle_license_expiry'];

                    $driverDetails->update();

                    $response['status'] = 200;
                    $response['message'] = 'Ride Details updated successfully.';
                    return response()->json($response, 200);
                } else {
                    $response['status'] = 400;
                    $response['error'] = 'Ride Details not updated successfully.';
                    return response()->json($response, 400);
                }
            }
        }
    }

    /** 
     * Return today earning amounts
    */
    public function todayEarning()
    {
        $response = $this->driverEarningService->todayTotalEarn();
        return response()->json($response, 200);
    }

    public function onlineStatusCheck(Request $request)
    {
        $user = Auth::user();
        $userId = Auth::user()->id;
        $input = $request->all();
        $rules = [
            'online' => 'required',  // Y/N
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response, 400);
        } else {
            $user->is_online = $input['online'];
            $user->save();
            $response['status'] = 200;
            $response['message'] = "Online Status Changed Successfully";
            $response['userData'] = $user;
            return response()->json($response, 200);
        }
    }

    public function getCustomerForRating(Request $request)
    {
        $user = Auth::user();
        $userId = Auth::user()->id;
        $passangerList = PassengerRideDetail::select('passenger_id')->where('driver_id', $userId)->groupBy('passenger_id')->get();
        $i = 0;
        foreach ($passangerList as $passanger) {
            $passanger = User::find($passanger->passenger_id);
            $ratingByDriver = PassangerRating::select('rating')->where('passanger_id', $passanger->passenger_id)->where('driver_id', $userId)->get();
            $passangerListDetail[$i] = $passanger;
            if (count($ratingByDriver) > 0) {
                $passangerListDetail[$i]['ratingByDriver'] = $ratingByDriver->rating;
            }
            $passangerListDetail[$i]['ratingByDriver'] = 0;
            $i = $i + 1;
        }
        $response['status'] = 200;
        $response['message'] = "Covid Status Changed Successfully";
        $response['passangerList'] = $passangerListDetail;
        return response()->json($response, 200);
    }

    public function covidStatusCheck(Request $request)
    {
        $user = Auth::user();
        $userId = Auth::user()->id;
        $input = $request->all();
        $rules = [
            'covidAccepted' => 'required',  // Y/N
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response, 400);
        } else {
            $user->is_covid_accepted = $input['covidAccepted'];
            $user->save();
            $response['status'] = 200;
            $response['message'] = "Covid Status Changed Successfully";
            $response['userData'] = $user;
            return response()->json($response, 200);
        }
    }

    public function pastRideList(Request $request)
    {
        $current_date  = Carbon::now()->format('Y-m-d');
        $user = Auth::user();
        $userId = Auth::user()->id;
        $pastRideList = PassengerRideDetail::with(['Passenger' => function ($query) {
            $query->select('id', 'full_name', 'profile_picture');
        }])->where(['driver_id' => $userId, 'trip_status' => 2])
            //->where('schedule_date','<',$current_date)
            ->orderBy('end_trip_date', 'desc')
            ->get()->toArray();
        $response['status'] = 200;
        $response['message'] = "Past ride fetch Successfully";
        $response['pastRideList'] = $pastRideList;
        return response()->json($response, 200);
    }
    public function giveRatingToCustomer(Request $request)
    {
        $checkRating = PassangerRating::where('passanger_id', $request->passanger_id)->where('driver_id', $request->driver_id)->get()->toArray();
        if (count($checkRating) > 0) {
            $updateRating = PassangerRating::where('passanger_id', $request->passanger_id)->where('driver_id', $request->driver_id)->update(['rating' => $request->rating]);
            if ($updateRating) {
                $response['status'] = 200;
                $response['message'] = 'Rating updated successfully.';
                return response()->json($response);
            } else {
                $response['status'] = 400;
                $response['error'] = "Rating not added successfully";
                return response()->json($response, 400);
            }
        } else {
            $rating = new PassangerRating;
            $rating->passanger_id = $request->passanger_id;
            $rating->driver_id = $request->driver_id;
            $rating->rating = $request->rating;
            if ($rating->save()) {
                $response['status'] = 200;
                $response['message'] = 'Rating added successfully.';
                return response()->json($response);
            } else {
                $response['status'] = 400;
                $response['error'] = "Rating not added successfully";
                return response()->json($response, 400);
            }
        }
    }

    public function addRatingForPassenger(Request $request)
    {
        $userId = Auth::user()->id;
        $newArr = [];
        $input = $request->all();
        $rules = [
            'rideId' => 'required',
            'rating' => 'required|numeric|min:1|max:5',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response, 400);
        } else {
            $rideData = PassengerRideDetail::where(['id' => $input['rideId']])->first();
            if (isset($rideData)) {
                $rideData->ride_rating = $input['rating'];
                $rideData->save();
                $passengerReviewRating = PassengerRideDetail::select('ride_rating')->where('passenger_id', $rideData->passenger_id)->get()->toArray();
                if (count($passengerReviewRating) > 0) {
                    foreach ($passengerReviewRating as $keys => $vals) {
                        array_push($newArr, $vals['ride_rating']);
                    }
                }

                $countArr = count($passengerReviewRating);
                $sumRating = array_sum($newArr);
                $averageRating = $sumRating / $countArr;

                $passengerRecord =  User::where('id', $rideData->passenger_id)->first();
                if (isset($passengerRecord)) {
                    $passengerRecord->avg_rating = $averageRating;
                    $passengerRecord->save();
                }
                $response['status'] = 200;
                $response['message'] = 'Rating added successfully.';
                return response()->json($response);
            } else {
                $response['status'] = 400;
                $response['error'] = "Ride not found";
                return response()->json($response, 400);
            }
        }
    }

    /**
     * Call when ride complete and return object with driver actual and total fare
     */
    public function completeRide(RideCompleteRequest $request)
    {
        $response = $this->rideHistoryService->rideComplete($request);
        return response()->json($response);
    }

    public function rateCustomerList()
    {
        $current_date  = Carbon::now()->format('Y-m-d');
        $user = Auth::user();
        $userId = Auth::user()->id;
        $pastRideList = PassengerRideDetail::with(['Passenger' => function ($query) {
            $query->select('id', 'full_name', 'profile_picture');
        }])->where(['driver_id' => $userId, 'trip_status' => 2])
            ->where('ride_rating', '=', 0)
            ->orderBy('end_trip_date', 'desc')
            ->get()->toArray();
        $response['status'] = 200;
        $response['message'] = "Ride rate fetch Successfully";
        $response['pastRideList'] = $pastRideList;
        return response()->json($response, 200);
    }

    public function myEarningRecord(MyEarningRequest $request)
    {
        if($request->validated()) {
            $response = $this->driverEarningService->myEarningList($request);
            return response()->json($response);
        } else {
            $response['status'] = 400;
            $response['message'] = 'Bad request';
            $response['data'] = [];
            return response()->json($response);
        }

        
    }

    public function addBankRecord(Request $request)
    {
        $userId = Auth::user()->id;
        $newArr = [];
        $input = $request->all();
        $rules = [
            'bank_name' => 'required',
            'holder_name' => 'required',
            'account_number' => 'required',
            'branch_code' => 'required',
            'swift_code' => 'required',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response, 400);
        } else {
            $result = BankDetail::where('user_id', $userId)->where('account_number', $input['account_number'])->first();
            if (isset($result)) {
                $response['status'] = 400;
                $response['error'] = "Bank Account number already added.";
                return response()->json($response, 400);
            } else {
                $bankRecord = new BankDetail;
                $bankRecord->user_id = $userId;
                $bankRecord->bank_name = $input['bank_name'];
                $bankRecord->holder_name = $input['holder_name'];
                $bankRecord->account_number = $input['account_number'];
                $bankRecord->branch_code = $input['branch_code'];
                $bankRecord->swift_code = $input['swift_code'];
                if ($bankRecord->save()) {
                    $response['status'] = 200;
                    $response['message'] = 'Bank Details added successfully.';
                    return response()->json($response);
                } else {
                    $response['status'] = 400;
                    $response['error'] = "Bank Details not  added successfully";
                    return response()->json($response, 400);
                }
            }
        }
    }

    public function bankDetailList()
    {
        $userId = Auth::user()->id;
        $userBankRecord = BankDetail::where('user_id', $userId)->get()->toArray();
        $response['status'] = 200;
        $response['message'] = "Bank Record fetch Successfully";
        $response['bankRecord'] = $userBankRecord;
        return response()->json($response, 200);
    }

    public function setAsPrimary(Request $request)
    {
        $userId = Auth::user()->id;
        $input = $request->all();
        $rules = [
            'id' => 'required',
            'user_id' => 'required',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response, 400);
        } else {
            if ($input['user_id'] == $userId) {
                $affectedRows = BankDetail::where('user_id', $userId)->update(array('set_as_primary' => 'N'));
                $userBankRecord = BankDetail::where('id', $input['id'])->where('user_id', $userId)->first();
                if (isset($userBankRecord)) {
                    $userBankRecord->set_as_primary = 'Y';
                    $userBankRecord->save();
                    $response['status'] = 200;
                    $response['message'] = 'Set As Primary updated successfully.';
                    return response()->json($response);
                } else {
                    $response['status'] = 400;
                    $response['error'] = "Something went wrong.";
                    return response()->json($response, 400);
                }
            } else {
                $response['status'] = 400;
                $response['error'] = "Something went wrong.";
                return response()->json($response, 400);
            }
        }
    }

    public function deleteBankRecord(Request $request)
    {
        $user = Auth::user();
        $userId = Auth::user()->id;
        $input = $request->all();
        $rules = [
            'id' => 'required',
            'user_id' => 'required',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response, 400);
        } else {
            if ($input['user_id'] == $userId) {
                $userBankRecord = BankDetail::where('id', $input['id'])->where('user_id', $userId)->first();
                if (isset($userBankRecord)) {
                    $deleteBank = BankDetail::where("id", $userBankRecord->id)->delete();
                    $response['status'] = 200;
                    $response['message'] = 'Bank Detail deleted successfully.';
                    return response()->json($response);
                } else {
                    $response['status'] = 400;
                    $response['error'] = "Something went wrong.";
                    return response()->json($response, 400);
                }
            } else {
                $response['status'] = 400;
                $response['error'] = "Something went wrong.";
                return response()->json($response, 400);
            }
        }
    }

    public function driverDelete(Request $request)
    {
        $userId = Auth::user()->id;
        $driverRecord = DriverDetail::where('user_id', $userId)->first();
        if ($driverRecord) {
            DriverDetail::where('user_id', $userId)->delete();
            User::where('id', $userId)->delete();
            $response['status'] = 200;
            $response['message'] = 'Deleted successfully.';
            return response()->json($response);
        } else {
            $response['status'] = 400;
            $response['error'] = "No record found!";
            return response()->json($response, 400);
        }
    }
    public function passengerBilling(Request $request)
    {
    }
    public function driverEarning(Request $request)
    {
        $user = Auth::user();
        $userId = Auth::user()->id;
        $today = date('Y-m-d');
        $start_time = $today . " 00:00:00";
        $end_time = $today . " 23:59:59";
        $total_earning = PassengerRideDetail::select('total_fare')->where('driver_id', $userId)->whereBetween('ride_time', [$start_time, $end_time])->sum('total_fare');
        $total_ride = PassengerRideDetail::where('driver_id', $userId)->whereBetween('ride_time', [$start_time, $end_time])->where('total_fare', '!=', '0.00')->count();

        $response['status'] = 200;
        $response['message'] = "My earning";
        $response['today_earning'] = "$" . $total_earning;
        $response['today_ride'] = $total_ride;
        return response()->json($response, 200);
    }
}
