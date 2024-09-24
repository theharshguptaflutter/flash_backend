<?php

namespace App\Services\Auth;

use App\Models\DriverCarAvailable;
use App\Models\DriverDetail;
use App\Models\DriverDocumentDetail;
use App\Models\User;
use App\Models\UserToken;
use App\Models\UserVerification;
use Exception;
use Twilio\Rest\Client;

/**
 * Send,veryfy and resend OTP related methods VerifyOtpService
 */
class VerifyOtpService
{
    /**
     * Verify OTP
     * @author Subhodeep Bhattacharjee <subhodeepbhat@technoexponent.com>
     * 
     * @param array $input
     * @return API response
     */
    public function verifyOTP(array $input)
    {
        $user = User::where('mobile', $input['mobile'])->where('country_code', $input['country_code'])->where('user_type', $input['user_type'])->first();
        if (isset($user) && ($user != null)) {
            if ($user->status == "Y") {
                if ($input['type'] == "L") { //Login
                    $user->cur_lat = isset($input['cur_lat']) ? $input['cur_lat'] : "";
                    $user->cur_long = isset($input['cur_long']) ? $input['cur_long'] : "";
                    $user->location = isset($input['location']) ? $input['location'] : "";
                    $user->save();
                    if ($user->user_type == "D") {  // driver
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

                    $otp_status = UserVerification::where('user_id', $user->id)->where('mobile_verify_code', $input['otp'])->where('verification_type', 'L')->first();
                    if (isset($otp_status) && ($otp_status != "")) {
                        $otp_status_delete = UserVerification::where("id", $otp_status->id)->delete();
                        $accessToken = $user->createToken(config('constant.PASSPORT_TOKEN_KEY'))->accessToken;

                        if ($input['user_type'] == "D") {
                            $driverDetails = DriverDetail::where('user_id', $user->id)->first();
                            $driverPhoto = DriverDocumentDetail::where('user_id', $user->id)
                                ->where('driver_detail_id', $driverDetails->id)
                                ->first();
                            $response['driver_photo'] = isset($driverPhoto->driver_photo) ? ($driverPhoto->driver_photo) : "";
                        }

                        $response['status'] = 200;
                        $response['user'] = $user;
                        $response['Authorization'] = 'Bearer ' . $accessToken;
                        $response['message'] = "Verify Successfully.";
                        // return response()->json($response, 200);
                        return $response;
                    } else {
                        $response = [
                            'status' => 400,
                            'error' => 'Invalid OTP!'
                        ];
                        // return response()->json($response, 400);
                        return $response;
                    }
                } else if ($input['type'] == "F") {   //Forgot Password
                    $otp_status = UserVerification::where('user_id', $user->id)->where('mobile_verify_code', $input['otp'])->where('verification_type', 'O')->first();
                    if (isset($otp_status) && ($otp_status != "")) {
                        $userId = encrypt($user->id);
                        $otp_status_delete = UserVerification::where("id", $otp_status->id)->delete();
                        $response = [
                            'status' => 200,
                            'userId' => $userId,
                            'message' => 'OTP Verify Successfully.',
                        ];
                        // return response()->json($response, 200);
                        return $response;
                    } else {
                        $response = [
                            'status' => 400,
                            'error' => 'Invalid OTP!'
                        ];
                        // return response()->json($response, 400);
                        return $response;
                    }
                }
            } else if ($user->status == 'I') {
                $response = [
                    'status' => 400,
                    'error' => 'Your account is deactivated. For more information, please contact to Flash app team.'
                ];
                // return response()->json($response, 400);
                return $response;
            } else {
                $response = [
                    'status' => 400,
                    'error' => 'please contact to Flash app team.'
                ];
                // return response()->json($response, 400);
                return $response;
            }
        } else {
            $response = [
                'status' => 400,
                'error' => 'Invalid Mobile No!'
            ];
            // return response()->json($response, 400);
            return $response;
        }
    }

    /**
     * Send OTP
     * @author Subhodeep Bhattacharjee <subhodeepbhat@technoexponent.com>
     * @param array $input
     * @return response with OTP
     */
    public function verifyPhoneNumber(array $input)
    {
        $api_error = "";
        $user = User::where('user_type', '=', $input['user_type'])
            ->where(function ($q) use ($input) {
                $q->where(function ($query) use ($input) {
                    $query->where('mobile', '=', $input['mobile'])
                        ->where('country_code', '=', $input['country_code']);
                });
            })->first();

        if ($user !== null) {
            if ($user->status == 'Y') {
                if ($input['user_type'] == "D") { //Driver
                    $document_step = 1;
                    if (($user->step == 1) || ($user->step == 2) || ($user->step == 3) || ($user->step == 4)) {
                        if ($user->step == 4) {
                            $document_step = 5; //6;
                        }

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
                }
                $otp = mt_rand(11111, 99999);
                $msg = $otp . ' is the OTP to login into your Flash account. Don\'t share it with anyone.';
                $user_ph = $input['country_code'] . $input['mobile'];

                try {
                    $sid = config('constant.TWILLO_SID');
                    $token = config('constant.TWILLO_TOKEN');
                    $from = "+18506080953";

                    $twilio = new Client($sid, $token);
                    $twilio->messages
                        ->create(
                            $user_ph, // to
                            ["body" => $msg, "from" => $from]
                        );
                } catch (\Exception $e) {
                    $api_error = $e->getMessage();
                }

                if ($api_error == "") {

                    $otp_status = UserVerification::where('user_id', $user->id)->where('verification_type', 'L')->get()->toArray();
                    if (count($otp_status) > 0) {
                        foreach ($otp_status as $key => $otpVal) {
                            $otp_status_delete = UserVerification::where("id", $otpVal['id'])->delete();
                        }
                    }

                    $otpRecord = UserVerification::create([
                        'user_id' => $user->id,
                        'mobile_verify_code' => $otp,
                        'verification_type' => 'L'

                    ]);

                    $userId = $user->id;
                    $response['status'] = 200;
                    $response['document_step'] = 5; //6;
                    $response['user'] = $user;
                    $response['message'] = 'OTP send to your Mobile No';
                    // return response()->json($response, 200);
                    return $response;
                } else {
                    $response['status'] = 400;
                    $response['error'] =  $api_error;
                    // return response()->json($response, 400);
                    return $response;
                }
            } else if ($user->status == 'I') {
                $response = [
                    'status' => 400,
                    'error' => 'Your account is deactivated. For more information, please contact to Flash app team.'
                ];
                // return response()->json($response, 400);
                return $response;
            } else {
                $response = [
                    'status' => 400,
                    'error' => 'Invalid login credentials!'
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
     * Resend OTP
     * @author Subhodeep Bhattacharjee <subhodeepbhat@technoexponent.com>
     * @param array $input
     * @return response New OTP
     */
    public function resendNewOtp(array $input)
    {
        $user = User::where('user_type', '=', $input['user_type'])->where(function ($q) use ($input) {
            $q->where(function ($query) use ($input) {
                $query->where('mobile', '=', $input['mobile'])
                    ->where('country_code', '=', $input['country_code']);
            });
        })->first();
        
        $api_error = "";
        if ($user !== null) {
            if ($user->status == 'Y') {
                $otp = mt_rand(11111, 99999);
                $user_ph = $input['country_code'] . $input['mobile'];
                if ($input['type'] == "F") {   //Forgot Password    
                    $msg = $otp . ' is the OTP for forgot password into your Flash account. Don\'t share it with anyone.';
                    try {
                        $sid = config('constant.TWILLO_SID');
                        $token = config('constant.TWILLO_TOKEN');
                        $from = "+18506080953";
                        $twilio = new Client($sid, $token);
                        $twilio->messages
                            ->create(
                                $user_ph, // to
                                ["body" => $msg, "from" => $from]
                            );
                    } catch (Exception $e) {
                        $api_error = $e->getMessage();
                    }
                    if ($api_error == "") {
                        $otp_status = UserVerification::where('user_id', $user->id)->where('verification_type', 'O')->delete();
                        $otpRecord = UserVerification::create([
                            'user_id' => $user->id,
                            'mobile_verify_code' => $otp,
                            'verification_type' => 'O'

                        ]);
                        $userId = $user->id;
                        $response['status'] = 200;
                        // $response['userId'] = $userId;
                        $response['message'] = 'OTP send to your Mobile No';
                        // return response()->json($response, 200);

                        return $response;
                    } else {
                        $response['status'] = 400;
                        $response['error'] =  $api_error;
                        // return response()->json($response, 400);
                        return $response;
                    }
                } else if ($input['type'] == "L") {   //Login 
                    $msg = $otp . ' is the OTP to login into your Flash account. Don\'t share it with anyone.';
                    try {
                        $sid = config('constant.TWILLO_SID');
                        $token = config('constant.TWILLO_TOKEN');
                        $from = "+18506080953";
                        $twilio = new Client($sid, $token);
                        $twilio->messages
                            ->create(
                                $user_ph, // to
                                ["body" => $msg, "from" => $from]
                            );
                    } catch (Exception $e) {
                        $api_error = $e->getMessage();
                    }
                    if ($api_error == "") {
                        $otp_status = UserVerification::where('user_id', $user->id)->where('verification_type', 'L')->delete();
                        $otpRecord = UserVerification::create([
                            'user_id' => $user->id,
                            'mobile_verify_code' => $otp,
                            'verification_type' => 'L'

                        ]);
                        $userId = $user->id;
                        $response['status'] = 200;
                        // $response['userId'] = $userId;
                        $response['message'] = 'OTP send to your Mobile No';
                        // return response()->json($response, 200);
                        return $response;
                    } else {
                        $response['status'] = 400;
                        $response['error'] =  $api_error;
                        // return response()->json($response, 400);
                        return $response;
                    }
                }
            } else if ($user->status == 'I') {
                $response = [
                    'status' => 400,
                    'error' => 'Your account is deactivated. For more information, please contact to Flash app team.'
                ];
                // return response()->json($response, 400);
                return $response;
            } else {
                $response = [
                    'status' => 400,
                    'error' => 'This mobile number not register yet.'
                ];
                // return response()->json($response, 400);
                return $response;
            }
        } else {
            $response = [
                'status' => 400,
                'error' => 'Mobile No not valid!.'
            ];
            // return response()->json($response, 400);
            return $response;
        }
    }
}
