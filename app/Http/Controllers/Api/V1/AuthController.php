<?php


namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Login\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Country;
use App\Models\UserVerification;
use App\Models\DriverDetail;
use App\Models\DriverDocumentDetail;
use Twilio\Rest\Client;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Spatie\Permission\Models\Role;
use App\Models\CarMake;
use App\Models\CarModel;
use DateTime;
use App\Models\Plan;
use App\Models\PlanDetail;
use App\Models\UserSubscription;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Mail;
use App\Mail\OtpSend;
use App\Models\DriverVehicleInspectionDetail;
use Illuminate\Support\Facades\Storage;
use App\Models\CarType;
use App\Models\CarTypeDetail;
use App\Models\DriverCarAvailable;
use App\Models\DriverVerificationDocument;
use App\Models\UserToken;
use App\Mail\RegistrationMsg;
use App\Services\AuthService;
use App\Services\NotificationService;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
    */
    protected $authService;
    protected $notificationService;

    public function __construct(
        AuthService $authService, 
        NotificationService $notificationService
    ) {
        $this->authService = $authService;
        $this->notificationService = $notificationService;
        $this->middleware('guest');
    }

    /**
     * Description: Phone Code Record
    */
    public function mailSend()
    {
        $input = request()->all();
        $rules = [
            'email' => 'required',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response, 400);
        } else {
            $data["email"] = "sulata@technoexponent.com";
            $data["fullName"] = "sulata samanta";
            $data["title"] = "Payment Invoice";
            $data["body"] = "Your Invoice attach below.";

            $html = '<h1>Hi, welcome Virat!</h1>';
            Mail::send([], [], function ($message) use ($html) {
                $message->to('sulata@technoexponent.com', 'Tutorials Point')
                    ->subject('Laravel Basic Testing Mail')
                    ->from('no-reply@flashappza.com', 'Virat Gandhi')
                    ->setBody($html, 'text/html'); //html body
                //or
                //->setBody('Hi, welcome Virat!'); //for text body
            });
            $response['status'] = 200;
            $response['message'] = 'Successfully sent the email';
            return response()->json($response);
        }
    }

    public function sendNotifications(Request $request)
    {
        // $user = UserToken::where('user_id', $request->id)->get()->toArray();
        // $ftoken = $user[0]['fcm_token'];

        // $noti = array("body" => $request->message, "title" => $request->message, "sound" => "default");
        // $token = isset($ftoken) ? $ftoken : "";
        // if ($token != null && $token != "") {

        //     $data = array(
        //         "sound" => "default",
        //         "body" => $request->message,
        //         "title" => $request->message,
        //         "content_available" => true,
        //         "priority" => "high",
        //         // "passengerRecord"=> $authUser,
        //         // "rideDetails"=> $initiateRideRecord,
        //     );
        //     $message_status = $this->sendNotification($token, $data, $noti);
        // }
        
        $message_status = $this->notificationService->sendNotifications($request);
        return $message_status;
    }

    public function sendDriverNotifications(Request $request)
    {
        $message_status = $this->notificationService->sendDriverNotifications($request);
        return $message_status;

        // $user = UserToken::where('user_id', $request->id)->get()->toArray();
        // $ftoken = $user[0]['fcm_token'];

        // $noti = array("body" => $request->message, "title" => $request->message, "sound" => "default");
        // $token = isset($ftoken) ? $ftoken : "";
        // if ($token != null && $token != "") {

        //     $data = array(
        //         "sound" => "default",
        //         "body" => $request->message,
        //         "title" => $request->message,
        //         "content_available" => true,
        //         "priority" => "high",
        //         // "passengerRecord"=> $authUser,
        //         // "rideDetails"=> $initiateRideRecord,
        //     );
        //     $message_status = $this->sendDriversNotification($token, $data, $noti);
        // }
    }

    public function getPhoneCode()
    {
        $response = [];
        $phoneCode = Country::select('id', 'phonecode')->orderBy('phonecode', 'asc')->get()->toArray();
        if (count($phoneCode) > 0) {
            $response['data'] = $phoneCode;
            $response['status'] = 200;
            $response['message'] = 'data Fetch successfully';
        } else {
            $response['status'] = 400;
            $response['error'] = "Something went Wrong!";
        }
        return response()->json($response);
    }

    /**
     * Description: Car Make Record
    */
    public function fetchCarMakeRecord()
    {
        $response = [];
        $carMake = CarMake::with('car_model')->where('status', 1)->get()->toArray();
        if (count($carMake) > 0) {
            $response['data'] = $carMake;
            $response['status'] = 200;
            $response['message'] = 'data Fetch successfully';
        } else {
            $response['status'] = 400;
            $response['error'] = "Something went Wrong!";
        }
        return response()->json($response);
    }

    /**
     * Description: Car Model Record
    */
    public function fetchCarModelRecord()
    {
        $response = [];
        $carModel = CarMake::with('car_model')->where('status', 1)->get()->toArray();
        if (count($carModel) > 0) {
            $response['data'] = $carModel;
            $response['status'] = 200;
            $response['message'] = 'data Fetch successfully';
        } else {
            $response['status'] = 400;
            $response['error'] = "Something went Wrong!";
        }
        return response()->json($response);
    }

    /**
     * Description: Registration for Passenger/Customer
     */
    public function customerRegister()
    {
        $input = request()->all();
        $rules = [
            'full_name' => 'required|string',
            'mobile' => 'required',
            'country_code' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
            'confirmed_password' => 'required|same:password',
            // 'fcmToken' => 'required',
            // 'device_id' => 'required',
            // 'device_type' => 'required|in:I,A'
        ];
        $messages = [
            'unique' => "This :attribute is already registered"
        ];
        $validator = Validator::make($input, $rules, $messages);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response, 400);
        } else {
            $findUsermobile = User::select('mobile')->where('mobile', $input['mobile'])->where('country_code', $input['country_code'])->first();
            $findUserEmail = User::select('email')->where('email', $input['email'])->first();
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
                $input['user_type'] = 'P';
                $input['status'] = 'Y';
                $user = User::create($input);

                $role = Role::findByName('customer', 'api');
                $user->assignRole($role);

                $accessToken = $user->createToken(config('constant.PASSPORT_TOKEN_KEY'))->accessToken;
                return response()->json([
                    'status' => 200,
                    'message' => 'Registration Successful',
                    'Authorization' => 'Bearer ' . $accessToken
                ], 200);
            }
        }
    }


    /**
     * Handles incoming Passenger Registration Requests
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function driverRegisterStepOne()
    {
        $input = request()->all();
        $rules = [
            'full_name' => 'required|string',
            'mobile' => 'required',
            'country_code' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
            'confirmed_password' => 'required|same:password',
        ];
        $messages = [
            'unique' => "This :attribute is already registered"
        ];
        $validator = Validator::make($input, $rules, $messages);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response, 400);
        } else {

            $response = $this->authService->registerFirstStep($input);
            // return response()->json($response, 200);
            return response()->json($response, $response['status']);

            // $findUserEmail = User::select('email')->where('email', $input['email'])->withTrashed()->first();
            // $findUsermobile = User::select('mobile')->where('mobile', $input['mobile'])->where('country_code', $input['country_code'])->withTrashed()->first();
            // if (isset($findUsermobile) && !empty($findUsermobile)) {
            //     $response['status'] = 400;
            //     $response['error'] = 'This phone number already exists.';
            //     return response()->json($response, 400);
            // } else if (isset($findUserEmail) && !empty($findUserEmail)) {
            //     $response['status'] = 400;
            //     $response['error'] = 'This Email already exists.';
            //     return response()->json($response, 400);
            // } else {
            //     $input['password'] = Hash::make($input['password']);
            //     $input['user_type'] = 'D'; //Driver
            //     $input['status'] = 'Y';
            //     //$input['driver_approval'] = 'A';
            //     $input['step'] = 1;
            //     $user = User::create($input);
            //     $userId = encrypt($user->id);
            //     $response['status'] = 200;
            //     $response['userId'] = $userId;
            //     $response['message'] = 'Registration Successful step 1';
            //     return response()->json($response, 200);
            // }
        }
    }

    /**
     * Handles incoming Passenger Registration Requests
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function driverRegisterStepTwo()
    {
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
            'user_id' => 'required',
            'driver_id_number' => 'required',
            'driver_license_number' => 'required',
            'seating_capacity' => 'required',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response, 400);
        } else {

            $response = $this->authService->registerSecondStep($input);
            return response()->json($response, $response['status']);

            // $findIdNumber = DriverDetail::where('id_number', $input['id_number'])->first();
            // $findRegNumber = DriverDetail::where('registration_number', $input['registration_number'])->first();
            // $findLicenseNumber = DriverDetail::where('license_number', $input['license_number'])->first();
            // $findVinNumber = DriverDetail::where('vin_number', $input['vin_number'])->first();
            // $findDriverIdNumber = DriverDetail::where('driver_id_number', $input['driver_id_number'])->first();
            // $findDriverLicenseNumber = DriverDetail::where('driver_license_number', $input['driver_license_number'])->first();
            // if (isset($findIdNumber) && !empty($findIdNumber)) {
            //     $response['status'] = 400;
            //     $response['error'] = 'This Id number already exists.';
            //     return response()->json($response, 400);
            // } else if (isset($findDriverIdNumber) && !empty($findDriverIdNumber)) {
            //     $response['status'] = 400;
            //     $response['error'] = 'This Driver id number already exists.';
            //     return response()->json($response, 400);
            // } else if (isset($findRegNumber) && !empty($findRegNumber)) {
            //     $response['status'] = 400;
            //     $response['error'] = 'This Registration number already exists.';
            //     return response()->json($response, 400);
            // } else if (isset($findLicenseNumber) && !empty($findLicenseNumber)) {
            //     $response['status'] = 400;
            //     $response['error'] = 'This License number already exists.';
            //     return response()->json($response, 400);
            // } else if (isset($findVinNumber) && !empty($findVinNumber)) {
            //     $response['status'] = 400;
            //     $response['error'] = 'This Vin number already exists.';
            //     return response()->json($response, 400);
            // } else if (isset($findDriverLicenseNumber) && !empty($findDriverLicenseNumber)) {
            //     $response['status'] = 400;
            //     $response['error'] = 'Driver License number already exists.';
            //     return response()->json($response, 400);
            // } else {
            //     $userId = decrypt($input['user_id']);
            //     $findUser = User::where('id', $userId)->first();
            //     if (isset($findUser) && !empty($findUser)) {
            //         $driverDetails = DriverDetail::where('user_id', $userId)->first();
            //         if (isset($driverDetails)) {
            //             $driverDetails->owner_name = $input['owner_name'];
            //             $driverDetails->id_number = $input['id_number'];
            //             $driverDetails->driver_id_number = $input['driver_id_number'];
            //             $driverDetails->make = $input['make'];
            //             $driverDetails->model = $input['model'];
            //             $driverDetails->vehicle_description = $input['vehicle_description'];
            //             $driverDetails->year = $input['year'];
            //             $driverDetails->registration_number = $input['registration_number'];
            //             $driverDetails->km_reading = $input['km_reading'];
            //             $driverDetails->license_number = $input['license_number'];
            //             $driverDetails->vin_number = $input['vin_number'];
            //             $driverDetails->seating_capacity = $input['seating_capacity'];
            //             $driverDetails->driver_license_number = $input['driver_license_number'];
            //             $driverDetails->update();
            //         } else {
            //             //$input['user_id'] = $userId;
            //             $driverDetails = new DriverDetail;
            //             $driverDetails->user_id = $userId;
            //             $driverDetails->owner_name = $input['owner_name'];
            //             $driverDetails->id_number = $input['id_number'];
            //             $driverDetails->driver_id_number = $input['driver_id_number'];
            //             $driverDetails->make = $input['make'];
            //             $driverDetails->model = $input['model'];
            //             $driverDetails->vehicle_description = $input['vehicle_description'];
            //             $driverDetails->year = $input['year'];
            //             $driverDetails->registration_number = $input['registration_number'];
            //             $driverDetails->km_reading = $input['km_reading'];
            //             $driverDetails->license_number = $input['license_number'];
            //             $driverDetails->vin_number = $input['vin_number'];
            //             $driverDetails->seating_capacity = $input['seating_capacity'];
            //             $driverDetails->driver_license_number = $input['driver_license_number'];
            //             $driverDetails->save();
            //             // $user = DriverDetail::create($input);
            //             $findUser->step = 2;
            //             $findUser->update();
            //         }

            //         $user_id = encrypt($findUser->id);
            //         $response['status'] = 200;
            //         $response['userId'] = $user_id;
            //         $response['message'] = 'Registration Successful for step 2';
            //         return response()->json($response, 200);
            //     } else {
            //         $response['status'] = 400;
            //         $response['error'] = 'Registration not done for step 2';
            //         return response()->json($response, 400);
            //     }
            // }
        }
    }

    /**
     * Handles incoming Passenger Registration Requests
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function driverRegisterStepThree()
    {
        $input = request()->all();
        $rules = [
            'exterior_color' => 'required|string',
            'interior_color' => 'required',
            'interior_trim' => 'required',
            'transmission' => 'required',
            'start_date_registration' => 'required',
            'end_date_road_worthy' => 'required',
            'provinence' => 'required',
            'vehicle_license_expiry' => 'required',
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

            $response = $this->authService->registerThirdStep($input);
            return response()->json($response, $response['status']);

            // $userId = decrypt($input['user_id']);
            // $findUser = User::where('id', $userId)->first();
            // $driverDetails = DriverDetail::where('user_id', $userId)->first();
            // if (isset($driverDetails) && (isset($findUser))) {
            //     $driverDetails->exterior_color = $input['exterior_color'];
            //     $driverDetails->interior_color = $input['interior_color'];
            //     $driverDetails->interior_trim = $input['interior_trim'];
            //     $driverDetails->transmission = $input['transmission'];
            //     $driverDetails->start_date_registration = $input['start_date_registration'];
            //     $driverDetails->end_date_road_worthy = $input['end_date_road_worthy'];
            //     $driverDetails->vehicle_license_expiry = $input['vehicle_license_expiry'];
            //     $driverDetails->provinence = $input['provinence'];
            //     $driverDetails->update();

            //     $findUser->step = 3;
            //     $findUser->update();

            //     $user_id = encrypt($findUser->id);

            //     $response['status'] = 200;
            //     $response['userId'] = $user_id;
            //     $response['message'] = 'Registration Successful step 3';
            //     return response()->json($response, 200);
            // } else {
            //     $response['status'] = 400;
            //     $response['error'] = 'Registration not done for step 3';
            //     return response()->json($response, 400);
            // }
        }
    }

    /**
     * Description: Registration step 4  for Driver
    */
    public function driverRegisterStepFour()
    {
        $errorException = "";
        $input = request()->all();
        $rules = [
            //D-PRMT= "driving permit",D-PIC = "driver Pic", D-EVR= "driver evaluation report",D-SSR = safety sereening result
            //V-IP = "Vehicle Insurance policy",  V-CDD = "vehicle card double disk", V-INS = "vehicle Inspection"
            'type' => 'required|string',
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

            $response = $this->authService->registerFourthStep($input);
            return response()->json($response, $response['status']);

            // $userId = decrypt($input['user_id']);
            // $findUser = User::where('id', $userId)->first();
            // $driverDetails = DriverDetail::where('user_id', $userId)->first();
            // if (isset($driverDetails) && (isset($findUser))) {
            //     $driverDetailId = ($driverDetails->id);
            //     // Driver Permit
            //     if ($input['type'] == "D-PRMT") {
            //         $driverDocumentPermit = DriverDocumentDetail::where('user_id', $userId)
            //             ->where('driver_detail_id', $driverDetailId)
            //             ->first();
            //         if (isset($driverDocumentPermit)) {
            //             $driverDocumentPermit->professional_driving_permit_name = isset($input['driving_permit']) ? $input['driving_permit'] : " ";
            //             $driverDocumentPermit->update();
            //         } else {
            //             $documentPermit = new DriverDocumentDetail;
            //             $documentPermit->driver_detail_id = $driverDetailId;
            //             $documentPermit->user_id = $userId;
            //             $documentPermit->professional_driving_permit_name = isset($input['driving_permit']) ? $input['driving_permit'] : " ";
            //             $documentPermit->save();
            //         }
            //         $user_id = encrypt($findUser->id);
            //         $response['status'] = 200;
            //         $response['userId'] = $user_id;
            //         $response['message'] = 'Document uploaded successfully.';
            //         return response()->json($response, 200);
            //     }

            //     // Driver Photo
            //     else if ($input['type'] == "D-PIC") {
            //         $driverPhoto = DriverDocumentDetail::where('user_id', $userId)
            //             ->where('driver_detail_id', $driverDetailId)
            //             ->first();
            //         if (isset($driverPhoto)) {
            //             $driverPhoto->driver_photo = isset($input['driver_photo']) ? $input['driver_photo'] : " ";
            //             $driverPhoto->update();
            //         } else {
            //             $photoDetail = new DriverDocumentDetail;
            //             $photoDetail->driver_detail_id = $driverDetailId;
            //             $photoDetail->user_id = $userId;
            //             $photoDetail->driver_photo = isset($input['driver_photo']) ? $input['driver_photo'] : " ";
            //             $photoDetail->save();
            //         }
            //         $user_id = encrypt($findUser->id);
            //         $response['status'] = 200;
            //         $response['userId'] = $user_id;
            //         $response['message'] = 'Document uploaded successfully.';
            //         return response()->json($response, 200);
            //     }

            //     // evaluation report
            //     else if ($input['type'] == "D-EVR") {
            //         $driverEvaluationReport = DriverDocumentDetail::where('user_id', $userId)
            //             ->where('driver_detail_id', $driverDetailId)
            //             ->first();
            //         if (isset($driverEvaluationReport)) {
            //             $driverEvaluationReport->driving_evaluation_report = isset($input['evaluation_report']) ? $input['evaluation_report'] : " ";
            //             $driverEvaluationReport->update();
            //         } else {
            //             $evaluationReport = new DriverDocumentDetail;
            //             $evaluationReport->driver_detail_id = $driverDetailId;
            //             $evaluationReport->user_id = $userId;
            //             $evaluationReport->driving_evaluation_report = isset($input['evaluation_report']) ? $input['evaluation_report'] : " ";
            //             $evaluationReport->save();
            //         }
            //         $user_id = encrypt($findUser->id);
            //         $response['status'] = 200;
            //         $response['userId'] = $user_id;
            //         $response['message'] = 'Document uploaded successfully.';
            //         return response()->json($response, 200);
            //     }

            //     // Safely screening result
            //     else if ($input['type'] == "D-SSR") {
            //         $screeningResult = DriverDocumentDetail::where('user_id', $userId)
            //             ->where('driver_detail_id', $driverDetailId)
            //             ->first();
            //         if (isset($screeningResult)) {
            //             $screeningResult->safety_screening_result = isset($input['screening_result']) ? $input['screening_result'] : " ";
            //             $screeningResult->update();
            //         } else {
            //             $screeningDetail = new DriverDocumentDetail;
            //             $screeningDetail->driver_detail_id = $driverDetailId;
            //             $screeningDetail->user_id = $userId;
            //             $screeningDetail->safety_screening_result = isset($input['screening_result']) ? $input['screening_result'] : " ";
            //             $screeningDetail->save();
            //         }
            //         $user_id = encrypt($findUser->id);
            //         $response['status'] = 200;
            //         $response['userId'] = $user_id;
            //         $response['message'] = 'Document uploaded successfully.';
            //         return response()->json($response, 200);
            //     }

            //     // Vihecle insurance policy
            //     else if ($input['type'] == "V-IP") {
            //         $driverInsurancePolicy = DriverDocumentDetail::where('user_id', $userId)
            //             ->where('driver_detail_id', $driverDetailId)
            //             ->first();
            //         if (isset($driverInsurancePolicy)) {
            //             $driverInsurancePolicy->vehicle_insurance_policy = isset($input['insurance_policy']) ? $input['insurance_policy'] : " ";
            //             $driverInsurancePolicy->update();
            //         } else {
            //             $insurancePolicy = new DriverDocumentDetail;
            //             $insurancePolicy->driver_detail_id = $driverDetailId;
            //             $insurancePolicy->user_id = $userId;
            //             $insurancePolicy->vehicle_insurance_policy = isset($input['insurance_policy']) ? $input['insurance_policy'] : " ";
            //             $insurancePolicy->save();
            //         }

            //         $user_id = encrypt($findUser->id);
            //         $response['status'] = 200;
            //         $response['userId'] = $user_id;
            //         $response['message'] = 'Document uploaded successfully.';
            //         return response()->json($response, 200);
            //     }

            //     // Vihecle Card double disk
            //     else if ($input['type'] == "V-CDD") {
            //         $driverCardDoubleDisk = DriverDocumentDetail::where('user_id', $userId)
            //             ->where('driver_detail_id', $driverDetailId)
            //             ->first();
            //         if (isset($driverCardDoubleDisk)) {
            //             $driverCardDoubleDisk->vehicle_card_double_disk = isset($input['card_double_disk']) ? $input['card_double_disk'] : " ";
            //             $driverCardDoubleDisk->update();
            //         } else {
            //             $cardDoubleDisk = new DriverDocumentDetail;
            //             $cardDoubleDisk->driver_detail_id = $driverDetailId;
            //             $cardDoubleDisk->user_id = $userId;
            //             $cardDoubleDisk->vehicle_card_double_disk = isset($input['card_double_disk']) ? $input['card_double_disk'] : " ";
            //             $cardDoubleDisk->save();
            //         }

            //         $user_id = encrypt($findUser->id);
            //         $response['status'] = 200;
            //         $response['userId'] = $user_id;
            //         $response['message'] = 'Document uploaded successfully.';
            //         return response()->json($response, 200);
            //     }

            //     //Vihecle Inspection
            //     else if ($input['type'] == "V-INS") {
            //         // $driverInspection = DriverDocumentDetail::where('user_id',$userId)
            //         // ->where('driver_detail_id',$driverDetailId)
            //         // ->first();
            //         // if(isset($driverInspection)){ 
            //         //     $insId = isset($input['inspection_id'])? $input['inspection_id']:" ";
            //         //     $inspectionId = DriverDocumentDetail::where('vehicle_inspection_id',$insId)->where('user_id','!=',$userId)->first();
            //         //     if(isset($inspectionId) && !empty($inspectionId)){
            //         //         $response['status'] = 400;
            //         //         $response['error'] = 'Inspection Id already exists.';
            //         //         return response()->json($response, 400);
            //         //     }else{
            //         //         $driverInspection->vehicle_inspection_id = isset($input['inspection_id'])? $input['inspection_id']:" ";
            //         //         $driverInspection->locate_inspection_center_name = isset($input['center_name'])? $input['center_name']:" ";
            //         //         $driverInspection->vehicle_document = isset($input['vehicle_document'])? $input['vehicle_document']:" ";
            //         //         $driverInspection->update();                    

            //         //     }
            //         // }else{
            //         //     $inspection = new DriverDocumentDetail;
            //         //     $inspection->driver_detail_id = $driverDetailId;
            //         //     $inspection->user_id = $userId;
            //         //     $inspection->vehicle_inspection_id = isset($input['inspection_id'])? $input['inspection_id']:" ";
            //         //     $inspection->locate_inspection_center_name = isset($input['center_name'])? $input['center_name']:" ";
            //         //     $inspection->vehicle_document = isset($input['vehicle_document'])? $input['vehicle_document']:" ";
            //         //     $inspection->save();
            //         // }

            //         $findUser->step = 4;
            //         $findUser->update();

            //         $msg = "Thanks for Registration.";
            //         $user_email = $findUser->email;
            //         $content = [
            //             'fullName' => $findUser->full_name,
            //             'msg' =>  $msg
            //         ];

            //         //Mail::to($user_email)->send(new RegistrationMsg($content)); 
            //         //  16-08-2022 start added
            //         $role = Role::findByName('driver', 'api');
            //         $findUser->assignRole($role);

            //         $user_id = encrypt($findUser->id);
            //         $response['status'] = 200;
            //         $response['userId'] = $user_id;
            //         $response['message'] = 'Document uploaded successfully.';
            //         return response()->json($response, 200);
            //     } else {
            //         $response['status'] = 400;
            //         $response['error'] = "Something went Wrong!";
            //         return response()->json($response, 400);
            //     }
            // } else {
            //     $response['status'] = 400;
            //     $response['error'] = "Something went Wrong!";
            //     return response()->json($response, 400);
            // }
        }
    }

    /**
     * Description: get plan record  for Driver
     */
    // public function fetchPlanDetail(){
    //     $response = [];
    //     $plan = Plan::where('status',1)->first();
    //     if(isset($plan)){
    //         $planDetails = Plan::with('PlanDetails')->where('status',1)->first();            
    //         $response['status'] = 200;
    //         $response['message'] = 'data Fetch successfully';
    //         $response['plan'] = isset($planDetails)?$planDetails: [];
    //     }else{
    //         $response['status'] = 400;
    //         $response['error'] = "Something went Wrong!";
    //     }
    //     return response()->json($response);
    // }


    // public function payGateData(){
    //     $amount = 0;
    //     $plan = Plan::where('status',1)->first();
    //     if(isset($plan)){
    //         $amount = $plan->amount;
    //     }
    //     $dateTime = new \DateTime();
    //     $secrete = config('constant.PAYGATE_SECRET');
    //     $data = array(
    //         'VERSION'          => 21,
    //         'PAYGATE_ID'       => config('constant.PAYGATE_ID'), 
    //         'REFERENCE'        => uniqid('pgtest_'),
    //         'AMOUNT'           => $amount,
    //         'CURRENCY'         => 'ZAR', 
    //         'RETURN_URL'       => 'http://localhost', //route('payment_response'),
    //         'TRANSACTION_DATE' => $dateTime->format('Y-m-d H:i:s'),
    //         'SUBS_START_DATE'  => now(),
    //         'SUBS_END_DATE'    => now(),
    //         'SUBS_FREQUENCY'   => 228,
    //         'PROCESS_NOW'      => 'NO',
    //         'PROCESS_NOW_AMOUNT' => '',
    //     );
    //     $checksum = md5(implode('|', $data) . $secrete);

    //     $data['CHECKSUM'] = $checksum;

    //     $response = [
    //         'status' => 200,
    //         'message' => $data,
    //     ];
    //     return response()->json($response, 200);
    // }

    /**
     * Description: Registration step 5  for Driver
    */

    //  16-08-2022 start

    public function driverRegisterStepFive()
    {
        $input = request()->all();
        $rules = [
            'user_id' => 'required',
            'plan_id' => 'required',
            'amount' => 'required',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response, 400);
        } else {

            $response = $this->authService->registerFifthStep($input);
            return response()->json($response, $response['status']);

            // $userId = decrypt($input['user_id']);
            // $findUser = User::where('id', $userId)->first();
            // if ($findUser) {
            //     $role = Role::findByName('driver', 'api');
            //     $findUser->assignRole($role);

            //     $subscription = new UserSubscription;
            //     $subscription->user_id = $userId;
            //     $subscription->plan_id = $input['plan_id'];
            //     $subscription->amount = $input['amount'];
            //     $subscription->event_type = 1;
            //     $subscription->is_runing = 1;
            //     $subscription->status = 1;
            //     $subscription->save();
            //     $findUser->step = 5;
            //     $findUser->update();
            //     $response['status'] = 200;
            //     $response['message'] = "Registration successfully done.";
            //     return response()->json($response, 200);
            // } else {
            //     $response['status'] = 400;
            //     $response['error'] = "Something went Wrong!";
            //     return response()->json($response, 400);
            // }
        }
    }

    //  16-08-2022end

    /**
     * Description: Login with Mobile for Passenger/Driver
    */
    public function login()
    {
        $document_step = 1;
        $input = request()->all();

        $rules = [
            'mobile' => 'required',
            'country_code' => 'required',
            'password' => 'required|min:6',
             'user_type' => 'required', //P OR D
            // 'fcm_token' => 'required',
             'device_id' => 'required',
             'device_type' => 'required|in:I,A',
            'cur_lat' => 'required',
            'cur_long' => 'required',
            //'location' => 'required'
        ];

        $validator = Validator::make($input, $rules,
        $messages = [
            'cur_lat.required'  => 'Current latitude longitude is required.',
            'cur_long.required' => 'Current latitude longitude is required.',
        ]);
        
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response, 400);
        } else {
            if (!isset($input['cur_lat']) || !isset($input['cur_long'])) {
                $response = [
                    'status' => 400,
                    'error' => [
                        'cur_lat' => 'Location is required.'
                    ]
                ];

                return response()->json($response, 400);

            } else {
                if (($input['cur_lat'] == null) || ($input['cur_long'] == null)) {

                    $response = [
                        'status' => 400,
                        'error' => 'Cab data not coming in customers'
                    ];
                    
                    return response()->json($response, 400);
                }
            }

            $response = $this->authService->varifyLogin($input, $document_step);
            return response()->json($response, $response['status']);
        }
    }

    /**
     * Description:Verify Phone for Passenger/Customer
    */
    public function verifyPhone()
    {
        $api_error = "";
        $input = request()->all();
        $rules = [
            'mobile' => 'required',
            'country_code' => 'required',
            'user_type' => 'required',
        ];

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response, 400);
        } else {
            $response = $this->authService->verifyPhone($input);
            return response()->json($response, $response['status']);
        }
    }

    /**
     * Description:Verify Otp for Login and Forgot password Passenger/Customer
     */

    public function otpVerify()
    {
        $input = request()->all();
        $rules = [
            'mobile' => 'required',
            'country_code' => 'required',
            'otp' => 'required|numeric',
            'type' => 'required',
            'user_type' => 'required',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response, 400);
        } else {
            $response = $this->authService->verifyOtp($input);
            return response()->json($response, $response['status']);
        }
    }

    /**
     * Description:Forgot Password for Passenger/Customer
    */
    public function forgotPassword()
    {
        $api_error = "";
        $input = request()->all();
        $rules = [
            'mobile' => 'required|numeric',
            'country_code' => 'required',
            'user_type' => 'required',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response, 400);
        } else {
            $user = User::where('mobile', $input['mobile'])->where('country_code', $input['country_code'])->where('user_type', $input['user_type'])->where('status', 'Y')->first();
            if (isset($user) && ($user != null)) {
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
                            return response()->json($response, 200);
                        }

                        if ($user->step == 2) {
                            $response = [
                                'status' => 200,
                                'user' => $user,
                                'message' => 'Please complete step 3',
                                'user_id' => encrypt($user->id),
                                'document_step' => $document_step,
                            ];
                            return response()->json($response, 200);
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
                            return response()->json($response, 200);
                        }
                    }
                }
                $otp = mt_rand(11111, 99999);
                $msg = $otp . ' is the OTP to forgot password into your Flash account. Don\'t share it with anyone.';

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
                    $userId = encrypt($user->id);
                    $response['status'] = 200;
                    $response['user_id'] = $userId;
                    $response['user'] = $user;
                    $response['document_step'] = 5; //6;
                    $response['message'] = 'OTP send to your Mobile No';
                    return response()->json($response, 200);
                } else {
                    $response['status'] = 400;
                    $response['error'] =  $api_error;
                    return response()->json($response, 400);
                }
            } else {
                $response['status'] = 400;
                $response['error'] = "Your account is not registered yet";
                return response()->json($response, 400);
            }
        }
    }

    /**
     * Description: Resend OTP for ForgotPassword and login
    */
    public function resendOTP(Request $request)
    {
        $api_error = "";
        $input = request()->all();
        $rules = [
            'type' => 'required',
            'mobile' => 'required',
            'country_code' => 'required',
            'user_type' => 'required',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response, 400);
        } else {
            $response = $this->authService->resendOtp($input);
            return response()->json($response, $response['status']);
        }
    }


    /**
     * Description: Password reset for ForgotPassword
    */
    public function resetPassword()
    {
        $input = request()->all();
        $rules = [
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
            'userId' => 'required',
            'user_type' => 'required',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response, 400);
        } else {
            $userId = decrypt($input['userId']);
            $user = User::where('id', $userId)->where('user_type', $input['user_type'])->where('status', 'Y')->first();
            if (isset($user)) {
                if (Hash::check($input['password'], $user->password)) {
                    $response['status'] = 400;
                    $response['error'] = "Old password and New password can't be same";
                    return response()->json($response, 400);
                }
                User::where('id', $userId)->update([
                    'password' => Hash::make($input['password'])
                ]);
                $response['status'] = 200;
                $response['message'] = 'Password Reset Successfully';
                return response()->json($response, 200);
            } else {
                $response['status'] = 400;
                $response['error'] = "Invalid User!";
                return response()->json($response, 400);
            }
        }
    }

    /**
     * Description: Login with Email for Inspection
    */
    public function inspectionLogin()
    {
        $input = request()->all();
        $rules = [
            //'email' => 'required|email',
            'mobile' => 'required',
            'country_code' => 'required',
            'password' => 'required',
            'user_type' => 'required', //S = service center or D = driver
            'device_id' => 'required',
            'device_type' => 'required|in:I,A'
        ];

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response, 400);
        } else {
            //$user = User::where('user_type', '=', $input['user_type'])->where('email', '=', $input['email'])->first();
            $user = User::where('user_type', '=', $input['user_type'])
                ->where(function ($q) use ($input) {
                    $q->where(function ($query) use ($input) {
                        $query->where('mobile', '=', $input['mobile'])
                            ->where('country_code', '=', $input['country_code']);
                    });
                })->first();
            if ($user !== null) {
                if (Hash::check($input['password'], $user->password)) {
                    if ($user->status == 'Y') {
                        if ($input['user_type'] == "S") { //driver                     

                            $accessToken = $user->createToken(config('constant.PASSPORT_TOKEN_KEY'))->accessToken;
                            $userRecord = User::with(['DriverDetails' => function ($query) {
                                $query->with(['driver_document']);
                            }])->where('id', $user->id)->first();
                            return response()->json([
                                'status' => 200,
                                'user' => $userRecord,
                                'user_id' => encrypt($user->id),
                                'Authorization' => 'Bearer ' . $accessToken,
                                'message' => "Login successfully."
                            ], 200);
                        } else {
                            $response = [
                                'status' => 400,
                                'error' => 'Invalid login credentials.'
                            ];
                            return response()->json($response, 400);
                        }
                    } else if ($user->status == 'I') {
                        $response = [
                            'status' => 400,
                            'error' => 'Your account is deactivated. For more information, please contact to Flash app team.'
                        ];
                        return response()->json($response, 400);
                    }
                } else {
                    $response = [
                        'status' => 400,
                        'error' => 'Invalid Password!'
                    ];
                    return response()->json($response, 400);
                }
            } else {
                $response = [
                    'status' => 400,
                    'error' => 'User not found'
                ];
                return response()->json($response, 400);
            }
        }
    }


    /**
     * Description:Forgot Password for Inspection
    */
    public function forgotPasswordForInspection()
    {
        $emailErrorMsg = "";
        $input = request()->all();
        $rules = [
            'email' => 'required|email',
            'user_type' => 'required',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response, 400);
        } else {
            $user = User::where('email', $input['email'])->where('', $input['user_type'])->where('status', 'Y')->first();
            if (isset($user) && ($user != null)) {
                $otp = mt_rand(11111, 99999);
                $user_email = $user->email;
                $user_name = $user->full_name;
                $content = [
                    'fullName' => $user_name,
                    'otp' =>  $otp
                ];
                try {
                    Mail::to($user_email)->send(new OtpSend($content));
                } catch (Exception $e) {
                    $emailErrorMsg = $e->getMessage();
                }
                if ($emailErrorMsg == "") {
                    $otp_status = UserVerification::where('user_id', $user->id)->where('verification_type', 'O')->delete();
                    $otpRecord = UserVerification::create([
                        'user_id' => $user->id,
                        'mobile_verify_code' => $otp,
                        'verification_type' => 'O'
                    ]);
                    $userId = encrypt($user->id);
                    $response['status'] = 200;
                    $response['user_id'] = $userId;
                    $response['email'] = $user_email;
                    $response['message'] = 'OTP send to your Email';
                    return response()->json($response, 200);
                } else {
                    $response['status'] = 400;
                    $response['error'] =  $emailErrorMsg;
                    return response()->json($response, 400);
                }
            } else {
                $response['status'] = 400;
                $response['error'] = "Your account is not registered yet";
                return response()->json($response, 400);
            }
        }
    }

    /**
     * Description:Verify Otp for Forgot password for Inspection
    */
    public function otpVerifyForInspection()
    {
        $input = request()->all();
        $rules = [
            'email' => 'required',
            'otp' => 'required|numeric',
            'user_type' => 'required',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response, 400);
        } else {
            $user = User::where('email', $input['email'])->where('user_type', $input['user_type'])->where('status', 'Y')->first();
            if (isset($user) && ($user != null)) {
                $otp_status = UserVerification::where('user_id', $user->id)->where('mobile_verify_code', $input['otp'])->where('verification_type', 'O')->first();
                if (isset($otp_status) && ($otp_status != "")) {
                    $userId = encrypt($user->id);
                    $otp_status_delete = UserVerification::where("id", $otp_status->id)->delete();
                    $response = [
                        'status' => 200,
                        'userId' => $userId,
                        'message' => 'OTP Verify Successfully.',
                    ];
                    return response()->json($response, 200);
                } else {
                    $response = [
                        'status' => 400,
                        'error' => 'Invalid OTP!'
                    ];
                    return response()->json($response, 400);
                }
            } else {
                $response = [
                    'status' => 400,
                    'error' => 'Invalid Email!'
                ];
                return response()->json($response, 400);
            }
        }
    }

    /**
     * Description: Resend OTP for ForgotPassword for Inspection
    */
    public function resendOTPForInspection(Request $request)
    {
        $emailErrorMsg = "";
        $input = request()->all();
        $rules = [
            'email' => 'required|email',
            'user_type' => 'required',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response, 400);
        } else {
            $user = User::where('user_type', '=', $input['user_type'])->where(function ($q) use ($input) {
                $q->where(function ($query) use ($input) {
                    $query->where('email', '=', $input['email']);
                });
            })->first();

            if ($user !== null) {
                if ($user->status == 'Y') {
                    $otp = mt_rand(11111, 99999);
                    $user_email = $user->email;
                    $user_name = $user->full_name;
                    $content = [
                        'fullName' => $user_name,
                        'otp' =>  $otp
                    ];
                    try {
                        Mail::to($user_email)->send(new OtpSend($content));
                    } catch (Exception $e) {
                        $emailErrorMsg = $e->getMessage();
                    }
                    if ($emailErrorMsg == "") {
                        $otp_status = UserVerification::where('user_id', $user->id)->where('verification_type', 'O')->delete();
                        $otpRecord = UserVerification::create([
                            'user_id' => $user->id,
                            'mobile_verify_code' => $otp,
                            'verification_type' => 'O'
                        ]);
                        $userId = encrypt($user->id);
                        $response['status'] = 200;
                        $response['user_id'] = $userId;
                        $response['email'] = $user_email;
                        $response['message'] = 'OTP send to your Email';
                        return response()->json($response, 200);
                    } else {
                        $response['status'] = 400;
                        $response['error'] =  $emailErrorMsg;
                        return response()->json($response, 400);
                    }
                } else if ($user->status == 'I') {
                    $response = [
                        'status' => 400,
                        'error' => 'Your account is deactivated. For more information, please contact to Flash app team.'
                    ];
                    return response()->json($response, 400);
                } else {
                    $response = [
                        'status' => 400,
                        'error' => 'This mobile number not register yet.'
                    ];
                    return response()->json($response, 400);
                }
            } else {
                $response = [
                    'status' => 400,
                    'error' => 'Mobile No not valid!.'
                ];
                return response()->json($response, 400);
            }
        }
    }


    /**
     * Description: Password reset for ForgotPassword for Inspection
    */
    public function resetPasswordForInspection()
    {
        $input = request()->all();
        $rules = [
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
            'userId' => 'required',
            'user_type' => 'required',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response, 400);
        } else {
            $userId = decrypt($input['userId']);
            $user = User::where('id', $userId)->where('user_type', $input['user_type'])->where('status', 'Y')->first();
            if (isset($user)) {
                if (Hash::check($input['password'], $user->password)) {
                    $response['status'] = 400;
                    $response['error'] = "Old password and New password can't be same";
                    return response()->json($response, 400);
                }
                User::where('id', $userId)->update([
                    'password' => Hash::make($input['password'])
                ]);
                $response['status'] = 200;
                $response['message'] = 'Password Reset Successfully';
                return response()->json($response, 200);
            } else {
                $response['status'] = 400;
                $response['error'] = "Invalid User!";
                return response()->json($response, 400);
            }
        }
    }

    public function carCategory()
    {
        $response = [];
        $car = CarType::select('*')->get()->toArray();
        if (count($car) > 0) {
            $response['status'] = 200;
            $response['message'] = 'data Fetch successfully';
            $response['carType'] = $car;
        } else {
            $response['status'] = 400;
            $response['error'] = "Something went Wrong!";
        }
        return response()->json($response);
    }

    public function carCategoryDetails()
    {
        $response = [];
        $car = CarTypeDetail::select('*')->get()->toArray();
        if (count($car) > 0) {
            $response['status'] = 200;
            $response['message'] = 'data Fetch successfully';
            $response['carType'] = $car;
        } else {
            $response['status'] = 400;
            $response['error'] = "Something went Wrong!";
        }
        return response()->json($response);
    }

    public function logout()
    {
        // $user = Auth::user();
        // if (isset($user)) {
        //     $user_token_delete = UserToken::where("user_id", $user->id)->delete();
        // }
        // //Auth::logout();

        // $response = [
        //     'status' => 200,
        //     'message' => 'logout successfully',
        // ];

        $response = $this->authService->logout();
        return response()->json($response, $response['status']);
    }
}
