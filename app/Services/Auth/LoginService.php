<?php

namespace App\Services\Auth;

use App\Models\DriverCarAvailable;
use App\Models\DriverDetail;
use App\Models\DriverDocumentDetail;
use App\Models\User;
use App\Models\UserToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class LoginService
{
    /**
     * Check user are valid or not and login steps
     * @author Subhodeep Bhattacharjee <subhodeepbhat@technoexponent.com>
     * 
     * @param array $input
     * @param int $document_step
     * @return API response
     */
    public function login(array $input, $document_step)
    {
        $user = User::where('user_type', '=', $input['user_type'])
            ->where(function ($q) use ($input) {
                $q->where(function ($query) use ($input) {
                    $query->where('mobile', '=', $input['mobile'])
                        ->where('country_code', '=', $input['country_code']);
                });
            })->first();
        // dd($user);
        $user_token_delete = UserToken::where("user_id", $user->id)->delete();
        Auth::logoutOtherDevices($input['password']);

        if ($user !== null) {
            if (Hash::check($input['password'], $user->password)) {
                if ($user->status == 'Y') {
                    if ($input['user_type'] == "D") { // Driver

                        if (($user->step == 1) || ($user->step == 2) || ($user->step == 3) || ($user->step == 4)) {
                            if ($user->step == 1) {
                                $response = [
                                    'status' => 200,
                                    'user' => $user,
                                    'message' => 'Please complete step 2',
                                    'user_id' => encrypt($user->id),
                                    'document_step' => $document_step,
                                ];
                                // return response()->json($response, 200);
                                return $response;
                            }

                            if ($user->step == 2) {
                                $response = [
                                    'status' => 200,
                                    'user' => $user,
                                    'message' => 'Please complete step 3',
                                    'user_id' => encrypt($user->id),
                                    'document_step' => $document_step,
                                ];
                                // return response()->json($response, 200);
                                return $response;
                            }

                            if ($user->step == 4) {
                                $document_step = 5; //6; //all step complete
                            }

                            if ($user->step == 3) {
                                $permit =  DriverDocumentDetail::where('professional_driving_permit_name', "!=", null)->where('professional_driving_permit_name', "!=", "")->where('user_id', $user->id)->first();
                                if (isset($permit)) {
                                    $document_step = 2;
                                }
                                if ($document_step == 2) {
                                    $driver_photo =  DriverDocumentDetail::where('driver_photo', "!=", null)->where('driver_photo', "!=", "")->where('user_id', $user->id)->first();
                                    if (isset($driver_photo)) {
                                        $document_step = 3;
                                    }
                                    if ($document_step == 3) {
                                        $safety_screening_result =  DriverDocumentDetail::where('safety_screening_result', "!=", null)->where('safety_screening_result', "!=", "")->where('user_id', $user->id)->first();
                                        if (isset($safety_screening_result)) {
                                            $document_step = 4;
                                        }
                                        if ($document_step == 4) {
                                            $vehicle_card_double_disk =  DriverDocumentDetail::where('vehicle_card_double_disk', "!=", null)->where('vehicle_card_double_disk', "!=", "")->where('user_id', $user->id)->first();
                                            if (isset($vehicle_card_double_disk)) {
                                                $document_step = 5;
                                            }
                                            
                                            // if($document_step == 5){
                                            //     $vehicle_inspection_id =  DriverDocumentDetail::where('vehicle_inspection_id', "!=",null)->where('vehicle_inspection_id', "!=","")->where('user_id',$user->id)->first();
                                            //     if(isset($vehicle_inspection_id)){
                                            //         $document_step = 6; //all step complete
                                            //     }
                                            // }
                                        }
                                    }
                                }

                                $response = [
                                    'status' => 200,
                                    'message' => 'Please complete the registration process!',
                                    'user_id' => encrypt($user->id),
                                    'document_step' => $document_step,
                                    'user' => $user
                                ];
                                // return response()->json($response, 200);
                                return $response;
                            }
                        }
                    }

                    if ($input['user_type'] == "D") {
                        $serviceCenterCheckRecord = DriverDetail::where('user_id', $user->id)->first();
                        if (isset($serviceCenterCheckRecord)) {
                            if ($serviceCenterCheckRecord->is_admin_approve != "Y") {
                                $response = [
                                    'status' => 400,
                                    'error' => 'Your details are not yet verified by the Flash app team.'
                                ];
                                // return response()->json($response, 400);
                                return $response;
                            }
                        }

                        $carAvailableRecord = DriverCarAvailable::where('driver_id', $user->id)->first();
                        if (isset($carAvailableRecord)) {
                            $carAvailableRecord->cur_lat = isset($input['cur_lat']) ? $input['cur_lat'] : "";
                            $carAvailableRecord->cur_long = isset($input['cur_long']) ? $input['cur_long'] : "";
                            $carAvailableRecord->cur_location = isset($input['location']) ? $input['location'] : "";
                            if ($carAvailableRecord->for_hire == "N") {
                                $carAvailableRecord->is_available = "Y";
                            }
                            $carAvailableRecord->save();
                        } else {
                            $carAvailable = new DriverCarAvailable;
                            $carAvailable->driver_id = $user->id;
                            $carAvailable->cur_lat = isset($input['cur_lat']) ? $input['cur_lat'] : "";
                            $carAvailable->cur_long = isset($input['cur_long']) ? $input['cur_long'] : "";
                            $carAvailable->cur_location = isset($input['location']) ? $input['location'] : "";
                            $carAvailable->is_available = "Y";
                            $carAvailable->save();
                        }
                    }

                    $user->cur_lat = isset($input['cur_lat']) ? $input['cur_lat'] : "";
                    $user->cur_long = isset($input['cur_long']) ? $input['cur_long'] : "";
                    $user->location = isset($input['location']) ? $input['location'] : "";
                    $user->save();

                    $userTokenRecord = UserToken::where("user_id", $user->id)->first();
                    if (isset($userTokenRecord)) {
                        $userTokenRecord->device_id = $input['device_id'];
                        $userTokenRecord->device_type = $input['device_type'];
                        $userTokenRecord->fcm_token = isset($input['fcm_token']) ? $input['fcm_token'] : "";
                        $userTokenRecord->save();
                    } else {
                        $userTokenRecordCreate = new UserToken;
                        $userTokenRecordCreate->user_id = $user->id;
                        $userTokenRecordCreate->device_id = $input['device_id'];
                        $userTokenRecordCreate->device_type = $input['device_type'];
                        $userTokenRecordCreate->fcm_token = isset($input['fcm_token']) ? $input['fcm_token'] : "";
                        $userTokenRecordCreate->save();
                    }

                    if ($input['user_type'] == "D") {
                        $driverDetails = DriverDetail::where('user_id', $user->id)->first();
                        $driverPhoto = DriverDocumentDetail::where('user_id', $user->id)
                            ->where('driver_detail_id', $driverDetails->id)
                            ->first();
                        $response['driver_photo'] = isset($driverPhoto->driver_photo) ? ($driverPhoto->driver_photo) : "";
                    }

                    $accessToken = $user->createToken(config('constant.PASSPORT_TOKEN_KEY'))->accessToken;
                    if ($input['user_type'] == "D") {
                        if (isset($serviceCenterCheckRecord)) {
                            $car_type = $serviceCenterCheckRecord->vehicle_description;
                            // return response()->json(, 200);
                            return [
                                'status' => 200,
                                'document_step' => 5, //6,
                                'user' => $user,
                                'car_type' => $car_type,
                                'driver_photo' => $response['driver_photo'],
                                'user_id' => encrypt($user->id),
                                'Authorization' => 'Bearer ' . $accessToken,
                                'message' => "Login successfully."
                            ];
                        }
                    }

                    if ($input['user_type'] == "D") {
                        return [
                            'status' => 200,
                            'document_step' => 5, //6,
                            'user' => $user,
                            'user_id' => encrypt($user->id),
                            'driver_photo' => $response['driver_photo'],
                            'Authorization' => 'Bearer ' . $accessToken,
                            'message' => "Login successfully."
                        ];
                    }

                    // return response()->json([
                    //     'status' => 200,
                    //     'document_step' => 5, //6,
                    //     'user' => $user,
                    //     'user_id' => encrypt($user->id),
                    //     'Authorization' => 'Bearer ' . $accessToken,
                    //     'message' => "Login successfully."
                    // ], 200);

                    return [
                        'status' => 200,
                        'document_step' => 5, //6,
                        'user' => $user,
                        'user_id' => encrypt($user->id),
                        'Authorization' => 'Bearer ' . $accessToken,
                        'message' => "Login successfully."
                    ];
                } else if ($user->status == 'I') {
                    $response = [
                        'status' => 400,
                        'error' => 'Your account is deactivated. For more information, please contact to Flash app team.'
                    ];
                    // return response()->json($response, 400);
                    return $response;
                }
            } else {
                $response = [
                    'status' => 400,
                    'error' => 'Invalid Password!'
                ];
                // return response()->json($response, 400);
                return $response;
            }
        } else {
            $response = [
                'status' => 400,
                'error' => 'User not found!'
            ];
            // return response()->json($response, 400);
            return $response;
        }
    }

    /**
     * Destroy session with server
     * @author Subhodeep Bhattacharjee <subhodeepbhat@technoexponent.com>
     * @param {*} NA
     * @return response $response
     */
    public function logout()
    {
        $user = Auth::user();
        if (isset($user)) {
            $user_token_delete = UserToken::where("user_id", $user->id)->delete();
        }

        $response = [
            'status' => 200,
            'message' => 'logout successfully',
        ];

        return $response;
    }
}
