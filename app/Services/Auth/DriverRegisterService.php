<?php

namespace App\Services\Auth;

use App\Models\DriverDetail;
use App\Models\DriverDocumentDetail;
use App\Models\User;
use App\Models\UserSubscription;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class DriverRegisterService
{
    /**
     * Register new driver first step process
     * @author Subhodeep Bhattacharjee <subhodeepbhat@technoexponent.com>
     * 
     * @param array $input
     * @return array $response
     */
    public function registerStepOne(array $input)
    {
        $findUserEmail = User::select('email')->where('email', $input['email'])->withTrashed()->first();
        $findUsermobile = User::select('mobile')->where('mobile', $input['mobile'])->where('country_code', $input['country_code'])->withTrashed()->first();
        if (isset($findUsermobile) && !empty($findUsermobile)) {
            $response['status'] = 400;
            $response['error'] = 'This phone number already exists.';
            return response()->json($response, 400);
        } else if (isset($findUserEmail) && !empty($findUserEmail)) {
            $response['status'] = 400;
            $response['error'] = 'This Email already exists.';
            return response()->json($response, 400);
        } else {
            $input['password'] = Hash::make($input['password']);
            $input['user_type'] = 'D'; //Driver
            $input['status'] = 'Y';
            //$input['driver_approval'] = 'A';
            $input['step'] = 1;
            $user = User::create($input);
            $userId = encrypt($user->id);
            $response['status'] = 200;
            $response['userId'] = $userId;
            $response['message'] = 'Registration Successful step 1';
            // return response()->json($response, 200);
            return $response;
        }
    }

    /**
     * Register new driver second step process
     * @author Subhodeep Bhattacharjee <subhodeepbhat@technoexponent.com>
     * 
     * @param array $input
     * @return array $response
     */
    public function registerStepTwo(array $input)
    {
        $findIdNumber = DriverDetail::where('id_number', $input['id_number'])->first();
        $findRegNumber = DriverDetail::where('registration_number', $input['registration_number'])->first();
        $findLicenseNumber = DriverDetail::where('license_number', $input['license_number'])->first();
        $findVinNumber = DriverDetail::where('vin_number', $input['vin_number'])->first();
        $findDriverIdNumber = DriverDetail::where('driver_id_number', $input['driver_id_number'])->first();
        $findDriverLicenseNumber = DriverDetail::where('driver_license_number', $input['driver_license_number'])->first();
        if (isset($findIdNumber) && !empty($findIdNumber)) {
            $response['status'] = 400;
            $response['error'] = 'This Id number already exists.';
            // return response()->json($response, 400);
            return $response;
        } else if (isset($findDriverIdNumber) && !empty($findDriverIdNumber)) {
            $response['status'] = 400;
            $response['error'] = 'This Driver id number already exists.';
            // return response()->json($response, 400);
            return $response;
        } else if (isset($findRegNumber) && !empty($findRegNumber)) {
            $response['status'] = 400;
            $response['error'] = 'This Registration number already exists.';
            // return response()->json($response, 400);
            return $response;
        } else if (isset($findLicenseNumber) && !empty($findLicenseNumber)) {
            $response['status'] = 400;
            $response['error'] = 'This License number already exists.';
            // return response()->json($response, 400);
            return $response;
        } else if (isset($findVinNumber) && !empty($findVinNumber)) {
            $response['status'] = 400;
            $response['error'] = 'This Vin number already exists.';
            // return response()->json($response, 400);
            return $response;
        } else if (isset($findDriverLicenseNumber) && !empty($findDriverLicenseNumber)) {
            $response['status'] = 400;
            $response['error'] = 'Driver License number already exists.';
            // return response()->json($response, 400);
            return $response;
        } else {
            $userId = decrypt($input['user_id']);
            $findUser = User::where('id', $userId)->first();
            if (isset($findUser) && !empty($findUser)) {
                $driverDetails = DriverDetail::where('user_id', $userId)->first();
                if (isset($driverDetails)) {
                    $driverDetails->owner_name = $input['owner_name'];
                    $driverDetails->id_number = $input['id_number'];
                    $driverDetails->driver_id_number = $input['driver_id_number'];
                    $driverDetails->make = $input['make'];
                    $driverDetails->model = $input['model'];
                    $driverDetails->vehicle_description = $input['vehicle_description'];
                    $driverDetails->year = $input['year'];
                    $driverDetails->registration_number = $input['registration_number'];
                    $driverDetails->km_reading = $input['km_reading'];
                    $driverDetails->license_number = $input['license_number'];
                    $driverDetails->vin_number = $input['vin_number'];
                    $driverDetails->seating_capacity = $input['seating_capacity'];
                    $driverDetails->driver_license_number = $input['driver_license_number'];
                    $driverDetails->update();
                } else {
                    //$input['user_id'] = $userId;
                    $driverDetails = new DriverDetail;
                    $driverDetails->user_id = $userId;
                    $driverDetails->owner_name = $input['owner_name'];
                    $driverDetails->id_number = $input['id_number'];
                    $driverDetails->driver_id_number = $input['driver_id_number'];
                    $driverDetails->make = $input['make'];
                    $driverDetails->model = $input['model'];
                    $driverDetails->vehicle_description = $input['vehicle_description'];
                    $driverDetails->year = $input['year'];
                    $driverDetails->registration_number = $input['registration_number'];
                    $driverDetails->km_reading = $input['km_reading'];
                    $driverDetails->license_number = $input['license_number'];
                    $driverDetails->vin_number = $input['vin_number'];
                    $driverDetails->seating_capacity = $input['seating_capacity'];
                    $driverDetails->driver_license_number = $input['driver_license_number'];
                    $driverDetails->save();
                    // $user = DriverDetail::create($input);
                    $findUser->step = 2;
                    $findUser->update();
                }

                $user_id = encrypt($findUser->id);
                $response['status'] = 200;
                $response['userId'] = $user_id;
                $response['message'] = 'Registration Successful for step 2';
                // return response()->json($response, 200);
                return $response;
            } else {
                $response['status'] = 400;
                $response['error'] = 'Registration not done for step 2';
                // return response()->json($response, 400);
                return $response;
            }
        }
    }

    /**
     * Register new driver third step process
     * @author Subhodeep Bhattacharjee <subhodeepbhat@technoexponent.com>
     * 
     * @param array $input
     * @return array $response
     */
    public function registerStepThree(array $input)
    {
        $userId = decrypt($input['user_id']);
        $findUser = User::where('id', $userId)->first();
        $driverDetails = DriverDetail::where('user_id', $userId)->first();
        if (isset($driverDetails) && (isset($findUser))) {
            $driverDetails->exterior_color = $input['exterior_color'];
            $driverDetails->interior_color = $input['interior_color'];
            $driverDetails->interior_trim = $input['interior_trim'];
            $driverDetails->transmission = $input['transmission'];
            $driverDetails->start_date_registration = $input['start_date_registration'];
            $driverDetails->end_date_road_worthy = $input['end_date_road_worthy'];
            $driverDetails->vehicle_license_expiry = $input['vehicle_license_expiry'];
            $driverDetails->provinence = $input['provinence'];
            $driverDetails->update();

            $findUser->step = 3;
            $findUser->update();

            $user_id = encrypt($findUser->id);

            $response['status'] = 200;
            $response['userId'] = $user_id;
            $response['message'] = 'Registration Successful step 3';
            // return response()->json($response, 200);
            return $response;
        } else {
            $response['status'] = 400;
            $response['error'] = 'Registration not done for step 3';
            // return response()->json($response, 400);
            return $response;
        }
    }

    /**
     * Register new driver four step process
     * @author Subhodeep Bhattacharjee <subhodeepbhat@technoexponent.com>
     * 
     * @param array $input
     * @return array $response
     */
    public function registerStepFour(array $input)
    {
        $userId = decrypt($input['user_id']);
        $findUser = User::where('id', $userId)->first();
        $driverDetails = DriverDetail::where('user_id', $userId)->first();
        if (isset($driverDetails) && (isset($findUser))) {
            $driverDetailId = ($driverDetails->id);
            // Driver Permit
            if ($input['type'] == "D-PRMT") {
                $driverDocumentPermit = DriverDocumentDetail::where('user_id', $userId)
                    ->where('driver_detail_id', $driverDetailId)
                    ->first();
                if (isset($driverDocumentPermit)) {
                    $driverDocumentPermit->professional_driving_permit_name = isset($input['driving_permit']) ? $input['driving_permit'] : " ";
                    $driverDocumentPermit->update();
                } else {
                    $documentPermit = new DriverDocumentDetail;
                    $documentPermit->driver_detail_id = $driverDetailId;
                    $documentPermit->user_id = $userId;
                    $documentPermit->professional_driving_permit_name = isset($input['driving_permit']) ? $input['driving_permit'] : " ";
                    $documentPermit->save();
                }
                $user_id = encrypt($findUser->id);
                $response['status'] = 200;
                $response['userId'] = $user_id;
                $response['message'] = 'Document uploaded successfully.';
                // return response()->json($response, 200);
                return $response;
            }

            // Driver Photo
            else if ($input['type'] == "D-PIC") {
                $driverPhoto = DriverDocumentDetail::where('user_id', $userId)
                    ->where('driver_detail_id', $driverDetailId)
                    ->first();
                if (isset($driverPhoto)) {
                    $driverPhoto->driver_photo = isset($input['driver_photo']) ? $input['driver_photo'] : " ";
                    $driverPhoto->update();
                } else {
                    $photoDetail = new DriverDocumentDetail;
                    $photoDetail->driver_detail_id = $driverDetailId;
                    $photoDetail->user_id = $userId;
                    $photoDetail->driver_photo = isset($input['driver_photo']) ? $input['driver_photo'] : " ";
                    $photoDetail->save();
                }
                $user_id = encrypt($findUser->id);
                $response['status'] = 200;
                $response['userId'] = $user_id;
                $response['message'] = 'Document uploaded successfully.';
                // return response()->json($response, 200);
                return $response;
            }

            // evaluation report
            else if ($input['type'] == "D-EVR") {
                $driverEvaluationReport = DriverDocumentDetail::where('user_id', $userId)
                    ->where('driver_detail_id', $driverDetailId)
                    ->first();
                if (isset($driverEvaluationReport)) {
                    $driverEvaluationReport->driving_evaluation_report = isset($input['evaluation_report']) ? $input['evaluation_report'] : " ";
                    $driverEvaluationReport->update();
                } else {
                    $evaluationReport = new DriverDocumentDetail;
                    $evaluationReport->driver_detail_id = $driverDetailId;
                    $evaluationReport->user_id = $userId;
                    $evaluationReport->driving_evaluation_report = isset($input['evaluation_report']) ? $input['evaluation_report'] : " ";
                    $evaluationReport->save();
                }
                $user_id = encrypt($findUser->id);
                $response['status'] = 200;
                $response['userId'] = $user_id;
                $response['message'] = 'Document uploaded successfully.';
                // return response()->json($response, 200);
                return $response;
            }

            // Safely screening result
            else if ($input['type'] == "D-SSR") {
                $screeningResult = DriverDocumentDetail::where('user_id', $userId)
                    ->where('driver_detail_id', $driverDetailId)
                    ->first();
                if (isset($screeningResult)) {
                    $screeningResult->safety_screening_result = isset($input['screening_result']) ? $input['screening_result'] : " ";
                    $screeningResult->update();
                } else {
                    $screeningDetail = new DriverDocumentDetail;
                    $screeningDetail->driver_detail_id = $driverDetailId;
                    $screeningDetail->user_id = $userId;
                    $screeningDetail->safety_screening_result = isset($input['screening_result']) ? $input['screening_result'] : " ";
                    $screeningDetail->save();
                }
                $user_id = encrypt($findUser->id);
                $response['status'] = 200;
                $response['userId'] = $user_id;
                $response['message'] = 'Document uploaded successfully.';
                // return response()->json($response, 200);
                return $response;
            }

            // Vihecle insurance policy
            else if ($input['type'] == "V-IP") {
                $driverInsurancePolicy = DriverDocumentDetail::where('user_id', $userId)
                    ->where('driver_detail_id', $driverDetailId)
                    ->first();
                if (isset($driverInsurancePolicy)) {
                    $driverInsurancePolicy->vehicle_insurance_policy = isset($input['insurance_policy']) ? $input['insurance_policy'] : " ";
                    $driverInsurancePolicy->update();
                } else {
                    $insurancePolicy = new DriverDocumentDetail;
                    $insurancePolicy->driver_detail_id = $driverDetailId;
                    $insurancePolicy->user_id = $userId;
                    $insurancePolicy->vehicle_insurance_policy = isset($input['insurance_policy']) ? $input['insurance_policy'] : " ";
                    $insurancePolicy->save();
                }

                $user_id = encrypt($findUser->id);
                $response['status'] = 200;
                $response['userId'] = $user_id;
                $response['message'] = 'Document uploaded successfully.';
                // return response()->json($response, 200);
                return $response;
            }

            // Vihecle Card double disk
            else if ($input['type'] == "V-CDD") {
                $driverCardDoubleDisk = DriverDocumentDetail::where('user_id', $userId)
                    ->where('driver_detail_id', $driverDetailId)
                    ->first();
                if (isset($driverCardDoubleDisk)) {
                    $driverCardDoubleDisk->vehicle_card_double_disk = isset($input['card_double_disk']) ? $input['card_double_disk'] : " ";
                    $driverCardDoubleDisk->update();
                } else {
                    $cardDoubleDisk = new DriverDocumentDetail;
                    $cardDoubleDisk->driver_detail_id = $driverDetailId;
                    $cardDoubleDisk->user_id = $userId;
                    $cardDoubleDisk->vehicle_card_double_disk = isset($input['card_double_disk']) ? $input['card_double_disk'] : " ";
                    $cardDoubleDisk->save();
                }

                $user_id = encrypt($findUser->id);
                $response['status'] = 200;
                $response['userId'] = $user_id;
                $response['message'] = 'Document uploaded successfully.';
                // return response()->json($response, 200);
                return $response;
            }

            //Vihecle Inspection
            else if ($input['type'] == "V-INS") {
                // $driverInspection = DriverDocumentDetail::where('user_id',$userId)
                // ->where('driver_detail_id',$driverDetailId)
                // ->first();
                // if(isset($driverInspection)){ 
                //     $insId = isset($input['inspection_id'])? $input['inspection_id']:" ";
                //     $inspectionId = DriverDocumentDetail::where('vehicle_inspection_id',$insId)->where('user_id','!=',$userId)->first();
                //     if(isset($inspectionId) && !empty($inspectionId)){
                //         $response['status'] = 400;
                //         $response['error'] = 'Inspection Id already exists.';
                //         return response()->json($response, 400);
                //     }else{
                //         $driverInspection->vehicle_inspection_id = isset($input['inspection_id'])? $input['inspection_id']:" ";
                //         $driverInspection->locate_inspection_center_name = isset($input['center_name'])? $input['center_name']:" ";
                //         $driverInspection->vehicle_document = isset($input['vehicle_document'])? $input['vehicle_document']:" ";
                //         $driverInspection->update();                    

                //     }
                // }else{
                //     $inspection = new DriverDocumentDetail;
                //     $inspection->driver_detail_id = $driverDetailId;
                //     $inspection->user_id = $userId;
                //     $inspection->vehicle_inspection_id = isset($input['inspection_id'])? $input['inspection_id']:" ";
                //     $inspection->locate_inspection_center_name = isset($input['center_name'])? $input['center_name']:" ";
                //     $inspection->vehicle_document = isset($input['vehicle_document'])? $input['vehicle_document']:" ";
                //     $inspection->save();
                // }

                $findUser->step = 4;
                $findUser->update();

                $msg = "Thanks for Registration.";
                $user_email = $findUser->email;
                $content = [
                    'fullName' => $findUser->full_name,
                    'msg' =>  $msg
                ];

                //Mail::to($user_email)->send(new RegistrationMsg($content)); 
                //  16-08-2022 start added
                $role = Role::findByName('driver', 'api');
                $findUser->assignRole($role);

                $user_id = encrypt($findUser->id);
                $response['status'] = 200;
                $response['userId'] = $user_id;
                $response['message'] = 'Document uploaded successfully.';
                // return response()->json($response, 200);
                return $response;
            } else {
                $response['status'] = 400;
                $response['error'] = "Something went Wrong!";
                // return response()->json($response, 400);
                return $response;
            }
        } else {
            $response['status'] = 400;
            $response['error'] = "Something went Wrong!";
            // return response()->json($response, 400);
            return $response;
        }
    }

    /**
     * Register new driver five step process
     * @author Subhodeep Bhattacharjee <subhodeepbhat@technoexponent.com>
     * 
     * @param array $input
     * @return array $response
     */
    public function registerStepFive(array $input)
    {
        $userId = decrypt($input['user_id']);
        $findUser = User::where('id', $userId)->first();
        if ($findUser) {
            $role = Role::findByName('driver', 'api');
            $findUser->assignRole($role);

            $subscription = new UserSubscription();
            $subscription->user_id = $userId;
            $subscription->plan_id = $input['plan_id'];
            $subscription->amount = $input['amount'];
            $subscription->event_type = 1;
            $subscription->is_runing = 1;
            $subscription->status = 1;
            $subscription->save();
            $findUser->step = 5;
            $findUser->update();
            $response['status'] = 200;
            $response['message'] = "Registration successfully done.";
            // return response()->json($response, 200);
            return $response;
        } else {
            $response['status'] = 400;
            $response['error'] = "Something went Wrong!";
            // return response()->json($response, 400);
            return $response;
        }
    }
}
