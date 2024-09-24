<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\DriverDetail;
use App\Models\DriverVerificationDocument;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Spatie\Permission\Models\Role;
use App\Models\DriverDocumentDetail;
use App\Models\DriverVehicleInspectionDetail;
use Carbon\Carbon;
use App\Models\DriverDocumentPicture;
use App\Models\DriverVerificationDocumentPicture;
use App\Models\Plan;
use App\Models\PlanDetail;
use App\Models\UserSubscription;
use App\Models\DriverTransaction;
use DateTime;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\DriverApprovalPayment;

class InspectorController extends Controller
{
    public function __construct(){
        
    }

      /**
     * Description: update profile
    */

    public function updateInspectorProfile(){
        $user = Auth::user();
        $input = request()->all();
        $rules = [
            'fullName' => 'required',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response['status'] = 400;
            $response['error'] = $validator->errors();
            return response()->json($response,400);
        }else{
            if($user){
                $user->full_name = $input['fullName'];
                $user->profile_picture = isset($input['profilePicture'])?$input['profilePicture']:$user->profile_picture;
                $user->save();
                $response['status'] = 200;
                $response['message'] = 'Profile details updated successfully.';
                $response['user_profile'] = $user;
            }else{
                $response['status'] = 400;
                $response['error'] = "Something went Wrong!";
            }
            return response()->json($response);
        }
    }

     /**
     * Description: Change password
    */
    public function changeInspectionPassword(){
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
            return response()->json($response,400);
        }else{
            if (Hash::check($input['oldPassword'], $user->password)) {
                if($input['oldPassword'] == $input['newPassword']){
                    $response['status'] = 400;
                    $response['error'] = "Old password and New password can't be same"; 
                    return response()->json($response,400); 
                }
                User::where('id',Auth::user()->id)->update([
                    'password'=>Hash::make(trim($input['newPassword'])),
                ]);
                $response['status'] = 200;
                $response['message'] = "Password Changed Successfully";
                return response()->json($response,200);
            }  else{
                $response['status'] = 400;
                $response['error'] = "Incorrect Old Password";
                return response()->json($response,400);
            }
        }
    }

    
    public function getDriverDetailsList(Request $request){
        $userId = Auth::user()->id;
        $input = $request->all();
        $rules = [
            'fullName' => 'required',
            'countryCode' => 'required',
            'phoneNo'=> 'required',
            'emailId'=> 'required',
            'idNumber'=> 'required',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response,400);
        }else{
            $userRecord = User::where(['email'=>trim($input['emailId']),'country_code'=>trim($input['countryCode']),'mobile'=>trim($input['phoneNo']),'id'=>$userId])->first();
            if(isset($userRecord)){
                if($userRecord->step != 4){ //16-08-2022   $userRecord->step != 5
                    $response['status'] = 400;
                    $response['error'] = 'User is not completed their profile.';
                    return response()->json($response, 400);
                }
                $driverDetails = DriverDetail::where(['id_number'=>$input['idNumber'],'user_id'=>$userRecord->id])->first();
                if(isset($driverDetails)){
                    if($driverDetails->is_admin_approve == "Y"){
                        $response['status'] = 400;
                        $response['error'] = 'This document already verified by Administrator';
                        return response()->json($response, 400);
                    }
                    if($driverDetails->is_driver_complete == "Y"){
                        $response['status'] = 400;
                        $response['error'] = 'Your document is under process';
                        return response()->json($response, 400);
                    }

                    $rideDetails = DriverDetail::with(['user' => function($query4){
                        $query4->select('id', 'full_name', 'email','country_code','mobile');
                    }])
                    ->with('driver_document')
                    ->with('DriverVehicleCarType')
                    ->with('document_picture')
                    ->with('verification_document_picture')
                    ->with('CarMake')->with('CarModel')
                    //->with('DriverVehicleCarType')
                    ->where('user_id',$userId)
                    ->first();
                    $response['status'] = 200;
                    $response['driverRecord'] = isset($rideDetails)?$rideDetails: [];
                    $response['message'] = "fetch successfully.";
                    return response()->json($response,200); 
                }else{
                    $response['status'] = 400;
                    $response['error'] = 'This Id Number Not Found.';
                    return response()->json($response, 400);
                }
                             
            }else{
                $response['status'] = 400;
                $response['error'] = 'This User Not Found.';
                return response()->json($response, 400);
            }            
        }
    }

    public function verifyDriverDetails(Request $request){
        $user = Auth::user();
        $userId = Auth::user()->id;
        $input = $request->all();
        $rules = [
            'owner_name' => 'required|string',
            'id_number'=> 'required',
            'make'=> 'required',
            'model' => 'required',
            'vehicle_description' => 'required',
            'year' => 'required',
            'registration_number' => 'required',
            'km_reading'=> 'required',
            'license_number'=> 'required',
            'vin_number'=> 'required',
            'exterior_color' => 'required|string',
            'interior_color'=> 'required',
            'interior_trim'=> 'required',
            'transmission' => 'required',
            'start_date_registration' => 'required',
            'end_date_road_worthy' => 'required',
            'seating_capacity' => 'required',
            'vehicle_license_expiry'=> 'required',
            'id_number_picture' => 'required',
            //'registration_picture' => 'required',
            //'license_picture' => 'required',
            //'vin_picture' => 'required',
            //'exterior_color_picture' => 'required',
            //'interior_color_picture' => 'required',
            //'first_registration_picture' => 'required',
            //'road_worthy_picture' => 'required',
            //'license_expiration_picture' => 'required',
            'km_reading_picture'=> 'required',
            'driver_license_number'=> 'required',
            'driver_id_number'=> 'required',
            'provinence' => 'required',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response,400);
        }else{
            $findIdNumber = DriverDetail::where('id_number',$input['id_number'])->where('user_id','!=',$userId)->first();
            $findRegNumber = DriverDetail::where('registration_number',$input['registration_number'])->where('user_id','!=',$userId)->first();
            $findLicenseNumber = DriverDetail::where('license_number',$input['license_number'])->where('user_id','!=',$userId)->first();
            $findVinNumber = DriverDetail::where('vin_number',$input['vin_number'])->where('user_id','!=',$userId)->first();
            $findDriverIdNumber = DriverDetail::where('driver_id_number',$input['driver_id_number'])->where('user_id','!=',$userId)->first();
            $findDriverLicenseNumber = DriverDetail::where('driver_license_number',$input['driver_license_number'])->where('user_id','!=',$userId)->first();
            if(isset($findIdNumber) && !empty($findIdNumber)){
                $response['status'] = 400;
                $response['error'] = 'This Id number already exists.';
                return response()->json($response, 400);
            }else if(isset($findRegNumber) && !empty($findRegNumber)){
                $response['status'] = 400;
                $response['error'] = 'This Registration number already exists.';
                return response()->json($response, 400);
            }else if(isset($findLicenseNumber) && !empty($findLicenseNumber)){
                $response['status'] = 400;
                $response['error'] = 'This License number already exists.';
                return response()->json($response, 400);
            }else if(isset($findVinNumber) && !empty($findVinNumber)){
                $response['status'] = 400;
                $response['error'] = 'This Vin number already exists.';
                return response()->json($response, 400);
            }else if(isset($findDriverIdNumber) && !empty($findDriverIdNumber)){
                $response['status'] = 400;
                $response['error'] = 'Driver Id number already exists.';
                return response()->json($response, 400);
            }else if(isset($findDriverLicenseNumber) && !empty($findDriverLicenseNumber)){
                $response['status'] = 400;
                $response['error'] = 'Driver License number already exists.';
                return response()->json($response, 400);
            }else{
                $driverDetails = DriverDetail::where('user_id',$userId)->first();
                if(isset($driverDetails)){
                    if($driverDetails->is_admin_approve == "R"){
                        $driverDetails->is_admin_approve = "P";
                        $driverDetails->reject_document_reason = "";
                        $driverDetails->save();
                    }
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
                    $driverDetails->driver_license_number = $input['driver_license_number']; 
                    $driverDetails->driver_id_number = $input['driver_id_number']; 
                    $driverDetails->provinence = $input['provinence']; 
                    $driverDetails->is_update_inspection = "Y";
                    $driverDetails->update();

                    $findDocPic = DriverDocumentPicture::where(['driver_detail_id'=>$driverDetails->id, 'driver_id'=>$userId])->first();
                    if(isset($findDocPic)){
                        $findDocPic->id_number_picture = $input['id_number_picture'];
                        //$findDocPic->registration_picture = $input['registration_picture'];
                        //$findDocPic->license_picture = $input['license_picture'];
                        //$findDocPic->vin_picture = $input['vin_picture'];
                        //$findDocPic->exterior_color_picture = $input['exterior_color_picture'];
                        //$findDocPic->interior_color_picture = $input['interior_color_picture'];
                        //$findDocPic->first_registration_picture = $input['first_registration_picture'];
                        //$findDocPic->road_worthy_picture = $input['road_worthy_picture'];
                        //$findDocPic->license_expiration_picture = $input['license_expiration_picture'];
                        $findDocPic->km_reading_picture = $input['km_reading_picture'];                        
                        $findDocPic->update();
                    }else{
                        $docPic = new DriverDocumentPicture;
                        $docPic->driver_detail_id = $driverDetails->id;
                        $docPic->driver_id = $userId;
                        $docPic->id_number_picture = $input['id_number_picture'];
                        //$docPic->registration_picture = $input['registration_picture'];
                        //$docPic->license_picture = $input['license_picture'];
                        //$docPic->vin_picture = $input['vin_picture'];
                        //$docPic->exterior_color_picture = $input['exterior_color_picture'];
                        //$docPic->interior_color_picture = $input['interior_color_picture'];
                        //$docPic->first_registration_picture = $input['first_registration_picture'];
                        //$docPic->road_worthy_picture = $input['road_worthy_picture'];
                        //$docPic->license_expiration_picture = $input['license_expiration_picture'];
                        $docPic->km_reading_picture = $input['km_reading_picture'];  
                        $docPic->save();
                    }

                    

                    $driverData = DriverDetail::where('is_driver_complete','N')->where('id',$driverDetails->id)->first();
                    if(isset($driverData)){
                        $driverData->is_driver_complete = "Y";
                        $driverData->save();
                    }

                    $driverResult = DriverDetail::with('driver_document')
                    ->with('driver_verification_document')
                    ->with('document_picture')
                    ->with('verification_document_picture')
                    ->with('driver_vehicle_inspection_document')
                    ->with('DriverVehicleCarType')
                    ->where('user_id',$userId)
                    ->where('id',$driverDetails->id)->first();
                    
                    $response['status'] = 200;
                    $response['message'] = 'Driver record updated successfully.';
                    $response['driverRecord'] = isset($driverResult)? $driverResult: [];
                    return response()->json($response, 200);
                }else{
                    $response['status'] = 400;
                    $response['error'] = 'Driver record not found.';
                    return response()->json($response, 400);
                }   
            }
            
        }
    }

    public function verifyImportantDocumets(Request $request){
        $user = Auth::user();
        $userId = Auth::user()->id;
        $input = $request->all();
        $rules = [
            //D-PRMT= "driving permit",D-PIC = "driver Pic", D-EVR= "driver evaluation report",D-SSR = safety sereening result
            //V-IP = "Vehicle Insurance policy",  V-CDD = "vehicle card double disk", V-INS = "vehicle Inspection"
            'type' => 'required|string',
            'driverDetailId' => 'required',
            
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response,400);
        }else{
            $driverDetails = DriverDetail::where('id',$input['driverDetailId'])->first();
            if(isset($driverDetails)){                   
                $driverDetailId = ($driverDetails->id);
                if($driverDetails->is_admin_approve == "R"){
                    $driverDetails->is_admin_approve = "P";
                    $driverDetails->reject_document_reason = "";
                    $driverDetails->save();
                }                         
                        
                $driverData = DriverDetail::where('is_driver_complete','N')->where('id',$driverDetails->id)->first();
                if(isset($driverData)){
                    $driverData->is_driver_complete = "Y";
                    $driverData->save();
                }


                
                // Driver Permit
                if($input['type'] == "D-PRMT"){        
                    $driverDocumentPermit = DriverDocumentDetail::where('user_id',$userId)
                    ->where('driver_detail_id',$driverDetailId)->first();
                    if(isset($driverDocumentPermit)){  
                        $driverDocumentPermit->professional_driving_permit_name = isset($input['driving_permit'])? $input['driving_permit']:$driverDocumentPermit->professional_driving_permit_name;
                        $driverDocumentPermit->update();
                       
                    }else{
                        $documentPermit = new DriverDocumentDetail;
                        $documentPermit->driver_detail_id = $driverDetailId;
                        $documentPermit->user_id = $userId;
                        $documentPermit->professional_driving_permit_name = isset($input['driving_permit'])? $input['driving_permit']:" ";
                        $documentPermit->save();
                    }

                    $response['status'] = 200;
                    $response['message'] = 'Document uploaded successfully.';
                    return response()->json($response,200);
                }

                // Driver Photo
                else if($input['type'] == "D-PIC"){        
                    $driverPhoto = DriverDocumentDetail::where('user_id',$userId)
                    ->where('driver_detail_id',$driverDetailId)
                    ->first();
                    if(isset($driverPhoto)){                
                        $driverPhoto->driver_photo = isset($input['driver_photo'])? $input['driver_photo']:$driverPhoto->driver_photo;
                        $driverPhoto->update();
                    }else{
                        $photoDetail = new DriverDocumentDetail;
                        $photoDetail->driver_detail_id = $driverDetailId;
                        $photoDetail->user_id = $userId;
                        $photoDetail->driver_photo = isset($input['driver_photo'])? $input['driver_photo']:" ";
                        $photoDetail->save();
                    }                   
                    $response['status'] = 200;
                    $response['message'] = 'Document uploaded successfully.';
                    return response()->json($response,200);
                }

                // evaluation report
                else if($input['type'] == "D-EVR"){        
                    $driverEvaluationReport = DriverDocumentDetail::where('user_id',$userId)
                    ->where('driver_detail_id',$driverDetailId)
                    ->first();
                    if(isset($driverEvaluationReport)){                
                        $driverEvaluationReport->driving_evaluation_report = isset($input['evaluation_report'])? $input['evaluation_report']:$driverEvaluationReport->driving_evaluation_report;
                        $driverEvaluationReport->update();
                    }else{
                        $evaluationReport = new DriverDocumentDetail;
                        $evaluationReport->driver_detail_id = $driverDetailId;
                        $evaluationReport->user_id = $userId;
                        $evaluationReport->driving_evaluation_report = isset($input['evaluation_report'])? $input['evaluation_report']:" ";
                        $evaluationReport->save();
                    }
                   
                    $response['status'] = 200;
                    $response['message'] = 'Document uploaded successfully.';
                    return response()->json($response,200);
                }

                 // Safely screening result
                 else if($input['type'] == "D-SSR"){        
                    $screeningResult = DriverDocumentDetail::where('user_id',$userId)
                    ->where('driver_detail_id',$driverDetailId)
                    ->first();
                    if(isset($screeningResult)){                
                        $screeningResult->safety_screening_result = isset($input['screening_result'])? $input['screening_result']:$screeningResult->safety_screening_result;
                        $screeningResult->update();
                    }else{
                        $screeningDetail = new DriverDocumentDetail;
                        $screeningDetail->driver_detail_id = $driverDetailId;
                        $screeningDetail->user_id = $userId;
                        $screeningDetail->safety_screening_result = isset($input['screening_result'])? $input['screening_result']:" ";
                        $screeningDetail->save();
                    }
                   
                    $response['status'] = 200;
                    $response['message'] = 'Document uploaded successfully.';
                    return response()->json($response,200);
                }

                // Vihecle insurance policy
                else if($input['type'] == "V-IP"){        
                    $driverInsurancePolicy = DriverDocumentDetail::where('user_id',$userId)
                    ->where('driver_detail_id',$driverDetailId)
                    ->first();
                    if(isset($driverInsurancePolicy)){                
                        $driverInsurancePolicy->vehicle_insurance_policy = isset($input['insurance_policy'])? $input['insurance_policy']:$driverInsurancePolicy->vehicle_insurance_policy;
                        $driverInsurancePolicy->update();
                    }else{
                        $insurancePolicy = new DriverDocumentDetail;
                        $insurancePolicy->driver_detail_id = $driverDetailId;
                        $insurancePolicy->user_id = $userId;
                        $insurancePolicy->vehicle_insurance_policy = isset($input['insurance_policy'])? $input['insurance_policy']:" ";
                        $insurancePolicy->save();
                    }                    
                    $response['status'] = 200;
                    $response['message'] = 'Document uploaded successfully.';
                    return response()->json($response,200);
                }

                // Vihecle Card double disk
                else if($input['type'] == "V-CDD"){        
                    $driverCardDoubleDisk = DriverDocumentDetail::where('user_id',$userId)
                    ->where('driver_detail_id',$driverDetailId)
                    ->first();
                    if(isset($driverCardDoubleDisk)){                
                        $driverCardDoubleDisk->vehicle_card_double_disk = isset($input['card_double_disk'])? $input['card_double_disk']:$driverCardDoubleDisk->vehicle_card_double_disk;
                        $driverCardDoubleDisk->update();
                    }else{
                        $cardDoubleDisk = new DriverDocumentDetail;
                        $cardDoubleDisk->driver_detail_id = $driverDetailId;
                        $cardDoubleDisk->user_id = $userId;
                        $cardDoubleDisk->vehicle_card_double_disk = isset($input['card_double_disk'])? $input['card_double_disk']:" ";
                        $cardDoubleDisk->save();
                    }
                    $response['status'] = 200;
                    $response['message'] = 'Document uploaded successfully.';
                    return response()->json($response,200);
                }

                 // Vihecle Inspection
                else if($input['type'] == "V-INS"){        
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
                    //         $driverInspection->vehicle_inspection_id = isset($input['inspection_id'])? $input['inspection_id']:$driverInspection->vehicle_inspection_id;
                    //         $driverInspection->locate_inspection_center_name = isset($input['center_name'])? $input['center_name']:$driverInspection->locate_inspection_center_name;
                    //         $driverInspection->vehicle_document = isset($input['vehicle_document'])? $input['vehicle_document']:$driverInspection->vehicle_document;
                    //         $driverInspection->update();

                            $inspectionDoc = DriverVehicleInspectionDetail::where('user_id',$userId)->where('driver_detail_id',$driverDetailId)->get()->toArray();
                            if(count($inspectionDoc)){
                                foreach($inspectionDoc as $keys => $vals){
                                    $deleteInspectionDoc = DriverVehicleInspectionDetail::where('id',$vals['id'])->delete();
                                }
                            }

                            if(isset($input['front']) && ($input['front'] != "")){
                                $inspectionDocument = new DriverVehicleInspectionDetail;
                                $inspectionDocument->driver_detail_id = $driverDetailId;
                                $inspectionDocument->user_id = $userId;
                                $inspectionDocument->vehicle_inspection_document = $input['front'];
                                $inspectionDocument->vehicle_document_type = "front";
                                $inspectionDocument->save();                              
                                
                            }

                            if(isset($input['back']) && ($input['back'] != "")){
                                $inspectionDocument = new DriverVehicleInspectionDetail;
                                $inspectionDocument->driver_detail_id = $driverDetailId;
                                $inspectionDocument->user_id = $userId;
                                $inspectionDocument->vehicle_inspection_document = $input['back'];
                                $inspectionDocument->vehicle_document_type = "back";
                                $inspectionDocument->save();
                            }

                            if(isset($input['left']) && ($input['left'] != "")){
                                $inspectionDocument = new DriverVehicleInspectionDetail;
                                $inspectionDocument->driver_detail_id = $driverDetailId;
                                $inspectionDocument->user_id = $userId;
                                $inspectionDocument->vehicle_inspection_document = $input['left'];
                                $inspectionDocument->vehicle_document_type = "left";
                                $inspectionDocument->save();
                                
                            }

                            if(isset($input['right']) && ($input['right'] != "")){
                                $inspectionDocument = new DriverVehicleInspectionDetail;
                                $inspectionDocument->driver_detail_id = $driverDetailId;
                                $inspectionDocument->user_id = $userId;
                                $inspectionDocument->vehicle_inspection_document = $input['right'];
                                $inspectionDocument->vehicle_document_type = "right";
                                $inspectionDocument->save();
                            }

                            if(isset($input['interiorFront']) && ($input['interiorFront'] != "")){
                                $inspectionDocument = new DriverVehicleInspectionDetail;
                                $inspectionDocument->driver_detail_id = $driverDetailId;
                                $inspectionDocument->user_id = $userId;
                                $inspectionDocument->vehicle_inspection_document = $input['interiorFront'];
                                $inspectionDocument->vehicle_document_type = "interiorFront";
                                $inspectionDocument->save();
                            } 
                            if(isset($input['interiorRear']) && ($input['interiorRear'] != "")){
                                $inspectionDocument = new DriverVehicleInspectionDetail;
                                $inspectionDocument->driver_detail_id = $driverDetailId;
                                $inspectionDocument->user_id = $userId;
                                $inspectionDocument->vehicle_inspection_document = $input['interiorRear'];
                                $inspectionDocument->vehicle_document_type = "interiorRear";
                                $inspectionDocument->save();
                            } 

                            $response['status'] = 200;
                            $response['message'] = 'Document uploaded successfully.';
                            return response()->json($response,200);                      

                    //     }
                    // }else{
                    //     $response['status'] = 400;
                    //     $response['error'] = 'Document not found';
                    //     return response()->json($response, 400);
                    // }
                    
                   
                }else{
                    $response['status'] = 400;
                    $response['error'] = "Something went Wrong!";
                    return response()->json($response, 400);
                }

            }else{
                $response['status'] = 400;
                $response['error'] = "Something went Wrong!";
                return response()->json($response, 400);
            }
            
        }
    }

    public function verifyDriverDocuments(Request $request){
        $isPaymentCompleted = "N";
        $user = Auth::user();
        $userId = Auth::user()->id;
        $input = $request->all();
        $rules = [ 
            'road_worth'=> 'required', // Y/N
            'functional_defects'=> 'required', // Y/N
            'warning_light'=> 'required', // Y/N
            'wheels'=> 'required', // Y/N
            //'steering'=> 'required', // Y/N
            'window_screen'=> 'required', // Y/N
            'head_light'=> 'required', // Y/N
            'indicator_light'=> 'required', // Y/N
            'brake_light'=> 'required', // Y/N
            'hooter'=> 'required', // Y/N
            'seat_belt'=> 'required', // Y/N
            'jack_triangle'=> 'required', // Y/N
            //'road_worthiness_picture' => 'required',
            //'functional_defect_picture' =>'required',
            //'warning_light_picture'  =>'required',
            //'wheel_picture'  =>'required',
            //'steering_picture'  =>'required',
            //'window_screen_picture' =>'required',
            //'head_light_picture' =>'required',
            //'indicator_light_picture' =>'required',
            //'brake_light_picture' =>'required',
            //'hooter_picture' =>'required',
            //'seat_belt_picture' =>'required',
            //'jack_triangle_picture' =>'required',
            'front_right_wheel_picture' =>'required',
            'front_left_wheel_picture' =>'required',
            'back_left_wheel_picture' =>'required',
            'back_right_wheel_picture' =>'required',
            //'front_seat_belt_picture' =>'required',
            //'passenger_seat_belt_picture' =>'required',
            //'rear_seat_belt_picture' =>'required'
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response,400);
        }else{
            $driverDetails = DriverDetail::where('user_id',$userId)->first();
            if(isset($driverDetails)){
                if($driverDetails->is_admin_approve == "Y"){
                    $response['status'] = 400;
                    $response['error'] = 'This document already verified by Administrator';
                    return response()->json($response, 400);
                }
                $verifyDoc = DriverVerificationDocument::where('driver_id',$userId)->where('driver_detail_id',$driverDetails->id)->first();
                if(isset($verifyDoc)){
                    $verifyDoc->driver_id = $userId;
                    $verifyDoc->is_road_worth = $input['road_worth'];
                    $verifyDoc->is_functional_defects = $input['functional_defects'];
                    $verifyDoc->is_warning_light_present = $input['warning_light'];
                    $verifyDoc->is_wheels_present = $input['wheels'];
                    //$verifyDoc->is_steering_present = $input['steering'];
                    $verifyDoc->is_window_screen_wiper = $input['window_screen'];
                    $verifyDoc->is_head_light_present = $input['head_light'];
                    $verifyDoc->is_indicator_light_present = $input['indicator_light'];
                    $verifyDoc->is_brake_light_present = $input['brake_light'];
                    $verifyDoc->is_hooter_present = $input['hooter'];
                    $verifyDoc->is_seat_belts_present = $input['seat_belt'];
                    $verifyDoc->is_spare_jack_triangle_present = $input['jack_triangle'];
                    $verifyDoc->save();

                    $findVerifyDocPic = DriverVerificationDocumentPicture::where(['driver_detail_id'=>$driverDetails->id, 'driver_id'=>$userId])->first();
                    if(isset($findVerifyDocPic)){
                       // $findVerifyDocPic->warning_light_picture = $input['warning_light_picture'];
                        //$findVerifyDocPic->wheel_picture = $input['wheel_picture'];
                        //$findVerifyDocPic->window_screen_picture = $input['window_screen_picture'];
                        //$findVerifyDocPic->head_light_picture = $input['head_light_picture'];
                        //$findVerifyDocPic->indicator_light_picture = $input['indicator_light_picture'];
                        //$findVerifyDocPic->brake_light_picture = $input['brake_light_picture'];
                        //$findVerifyDocPic->seat_belt_picture = $input['seat_belt_picture'];
                        //$findVerifyDocPic->jack_triangle_picture = $input['jack_triangle_picture'];
                        
                        $findVerifyDocPic->front_right_wheel_picture = $input['front_right_wheel_picture'];
                        $findVerifyDocPic->front_left_wheel_picture = $input['front_left_wheel_picture'];
                        $findVerifyDocPic->back_left_wheel_picture = $input['back_left_wheel_picture'];
                        $findVerifyDocPic->back_right_wheel_picture = $input['back_right_wheel_picture'];
                        // $findVerifyDocPic->front_seat_belt_picture = $input['front_seat_belt_picture'];
                        // $findVerifyDocPic->passenger_seat_belt_picture = $input['passenger_seat_belt_picture'];
                        // $findVerifyDocPic->rear_seat_belt_picture = $input['rear_seat_belt_picture'];
                        $findVerifyDocPic->save();
                    }else{
                        $verifyDocPic = new DriverVerificationDocumentPicture;
                        $verifyDocPic->driver_detail_id = $driverDetails->id;
                        $verifyDocPic->driver_id = $userId;
                        //$verifyDocPic->warning_light_picture = $input['warning_light_picture'];
                        //$verifyDocPic->wheel_picture = $input['wheel_picture'];
                        //$verifyDocPic->window_screen_picture = $input['window_screen_picture'];
                        //$verifyDocPic->head_light_picture = $input['head_light_picture'];
                        //$verifyDocPic->indicator_light_picture = $input['indicator_light_picture'];
                        //$verifyDocPic->brake_light_picture = $input['brake_light_picture'];
                        //$verifyDocPic->seat_belt_picture = $input['seat_belt_picture'];
                        //$verifyDocPic->jack_triangle_picture = $input['jack_triangle_picture'];
                        $verifyDocPic->front_right_wheel_picture = $input['front_right_wheel_picture'];
                        $verifyDocPic->front_left_wheel_picture = $input['front_left_wheel_picture'];
                        $verifyDocPic->back_left_wheel_picture = $input['back_left_wheel_picture'];
                        $verifyDocPic->back_right_wheel_picture = $input['back_right_wheel_picture'];
                        // $verifyDocPic->front_seat_belt_picture = $input['front_seat_belt_picture'];
                        // $verifyDocPic->passenger_seat_belt_picture = $input['passenger_seat_belt_picture'];
                        // $verifyDocPic->rear_seat_belt_picture = $input['rear_seat_belt_picture'];
                        $verifyDocPic->save();
                    }

                    $driverData = DriverDetail::where('is_driver_complete','N')->where('id',$driverDetails->id)->first();
                    if(isset($driverData)){
                        $driverData->is_driver_complete = "Y";
                        $driverData->save();
                    }
                    $driverResult = DriverDetail::where('id',$driverDetails->id)->first();
                    if(isset($driverResult)){
                        if($driverResult->is_admin_approve == "R"){
                            $driverResult->is_admin_approve = "P";
                            $driverResult->reject_document_reason = "";
                            $driverResult->save();
                        }
                        $paymentCompleted = $driverResult->is_payment_completed;
                        if($paymentCompleted == "Y"){
                            $isPaymentCompleted = "Y";
                        }else{
                            $isPaymentCompleted = "N";
                        }
                    }

                    // mail send to admin, driver, service center that will contain the inspection report
                    $response['status'] = 200;
                    $response['is_payment_completed'] = $isPaymentCompleted;
                    $approvalAmount = DriverApprovalPayment::select('*')->first();
                    if($approvalAmount){
                        $response['driver_inspection_amount'] = $approvalAmount->amount;
                        $response['driver_inspection_tax'] = $approvalAmount->tax;
                        $response['driver_total_amount'] = $approvalAmount->total_amount;
                        $response['driver_tax_percentage'] = $approvalAmount->percentage;
                    }else{
                        $response['driver_inspection_amount'] = 0.00;
                        $response['driver_inspection_tax'] = 0.00;
                        $response['driver_total_amount'] = 0.00;
                        $response['driver_tax_percentage'] = 0.00;
                    }
                    
                    $response['message'] = "Driver record updated successfully.";
                    return response()->json($response,200);
                }else{
                    $verificationDoc = new DriverVerificationDocument;
                    $verificationDoc->driver_detail_id = $driverDetails->id;
                    $verificationDoc->driver_id = $userId;
                    $verificationDoc->is_road_worth = $input['road_worth'];
                    $verificationDoc->is_functional_defects = $input['functional_defects'];
                    $verificationDoc->is_warning_light_present = $input['warning_light'];
                    $verificationDoc->is_wheels_present = $input['wheels'];
                    //$verificationDoc->is_steering_present = $input['steering'];
                    $verificationDoc->is_window_screen_wiper = $input['window_screen'];
                    $verificationDoc->is_head_light_present = $input['head_light'];
                    $verificationDoc->is_indicator_light_present = $input['indicator_light'];
                    $verificationDoc->is_brake_light_present = $input['brake_light'];
                    $verificationDoc->is_hooter_present = $input['hooter'];
                    $verificationDoc->is_seat_belts_present = $input['seat_belt'];
                    $verificationDoc->is_spare_jack_triangle_present = $input['jack_triangle'];
                    if($verificationDoc->save()){

                        $findVerifyDocPic = DriverVerificationDocumentPicture::where(['driver_detail_id'=>$driverDetails->id, 'driver_id'=>$userId])->first();
                        if(isset($findVerifyDocPic)){
                           // $findVerifyDocPic->warning_light_picture = $input['warning_light_picture'];
                            //$findVerifyDocPic->wheel_picture = $input['wheel_picture'];
                            //$findVerifyDocPic->window_screen_picture = $input['window_screen_picture'];
                            //$findVerifyDocPic->head_light_picture = $input['head_light_picture'];
                            //$findVerifyDocPic->indicator_light_picture = $input['indicator_light_picture'];
                            //$findVerifyDocPic->brake_light_picture = $input['brake_light_picture'];
                            //$findVerifyDocPic->seat_belt_picture = $input['seat_belt_picture'];
                            //$findVerifyDocPic->jack_triangle_picture = $input['jack_triangle_picture'];
                            $findVerifyDocPic->front_right_wheel_picture = $input['front_right_wheel_picture'];
                            $findVerifyDocPic->front_left_wheel_picture = $input['front_left_wheel_picture'];
                            $findVerifyDocPic->back_left_wheel_picture = $input['back_left_wheel_picture'];
                            $findVerifyDocPic->back_right_wheel_picture = $input['back_right_wheel_picture'];
                            // $findVerifyDocPic->front_seat_belt_picture = $input['front_seat_belt_picture'];
                            // $findVerifyDocPic->passenger_seat_belt_picture = $input['passenger_seat_belt_picture'];
                            // $findVerifyDocPic->rear_seat_belt_picture = $input['rear_seat_belt_picture'];
                            $findVerifyDocPic->save();
                        }else{
                            $verifyDocPic = new DriverVerificationDocumentPicture;
                            $verifyDocPic->driver_detail_id = $driverDetails->id;
                            $verifyDocPic->driver_id = $userId;
                            //$verifyDocPic->warning_light_picture = $input['warning_light_picture'];
                            //$verifyDocPic->wheel_picture = $input['wheel_picture'];
                            //$verifyDocPic->window_screen_picture = $input['window_screen_picture'];
                            //$verifyDocPic->head_light_picture = $input['head_light_picture'];
                            //$verifyDocPic->indicator_light_picture = $input['indicator_light_picture'];
                            //$verifyDocPic->brake_light_picture = $input['brake_light_picture'];
                            //$verifyDocPic->seat_belt_picture = $input['seat_belt_picture'];
                            //$verifyDocPic->jack_triangle_picture = $input['jack_triangle_picture'];

                            $verifyDocPic->front_right_wheel_picture = $input['front_right_wheel_picture'];
                            $verifyDocPic->front_left_wheel_picture = $input['front_left_wheel_picture'];
                            $verifyDocPic->back_left_wheel_picture = $input['back_left_wheel_picture'];
                            $verifyDocPic->back_right_wheel_picture = $input['back_right_wheel_picture'];
                            // $verifyDocPic->front_seat_belt_picture = $input['front_seat_belt_picture'];
                            // $verifyDocPic->passenger_seat_belt_picture = $input['passenger_seat_belt_picture'];
                            // $verifyDocPic->rear_seat_belt_picture = $input['rear_seat_belt_picture'];
                            $verifyDocPic->save();
                        }
                        $driverDetails->is_update_inspection = "Y";
                        $driverDetails->save();
                    }

                    $driverData = DriverDetail::where('is_driver_complete','N')->where('id',$driverDetails->id)->first();
                    if(isset($driverData)){
                        $driverData->is_driver_complete = "Y";
                        $driverData->save();
                    }

                    $driverResult = DriverDetail::where('id',$driverDetails->id)->first();
                    if(isset($driverResult)){
                        if($driverResult->is_admin_approve == "R"){
                            $driverResult->is_admin_approve = "P";
                            $driverResult->reject_document_reason = "";
                            $driverResult->save();
                        }
                        $paymentCompleted = $driverResult->is_payment_completed;
                        if($paymentCompleted == "Y"){
                            $isPaymentCompleted = "Y";
                        }else{
                            $isPaymentCompleted = "N";
                        }
                    }

                    // mail send to admin, driver, service center that will contain the inspection report
                    $response['status'] = 200;
                    $response['is_payment_completed'] = $isPaymentCompleted;
                    $approvalAmount = DriverApprovalPayment::select('*')->first();
                    if($approvalAmount){
                        $response['driver_inspection_amount'] = $approvalAmount->amount;
                        $response['driver_inspection_tax'] = $approvalAmount->tax;
                        $response['driver_total_amount'] = $approvalAmount->total_amount;
                        $response['driver_tax_percentage'] = $approvalAmount->percentage;
                    }else{
                        $response['driver_inspection_amount'] = 0.00;
                        $response['driver_inspection_tax'] = 0.00;
                        $response['driver_total_amount'] = 0.00;
                        $response['driver_tax_percentage'] = 0.00;
                    }
                    $response['message'] = "Driver record uploaded Successfully";
                    return response()->json($response,200);
                }
            }else{
                $response['status'] = 400;
                $response['error'] = 'Driver record not found.';
                return response()->json($response, 400);
            }
        }
    }


    public function payGateInitiate(){
        $userId = Auth::user()->id;
        $userData = Auth::user();
        $user_email = $userData->email;
        $approvalAmount = DriverApprovalPayment::where('id',1)->first();
        if($approvalAmount){
            $driver_total_amount = $approvalAmount->total_amount *100;
        }else{
            $driver_total_amount = 0.00;
        }
        $DateTime = new \DateTime();
        $data = array(
            'PAYGATE_ID'       => env('PAYGATE_ID'),
            'REFERENCE'        => uniqid('pgtest_'),
            'AMOUNT'           => $driver_total_amount,
            'CURRENCY'         => 'ZAR', //https: //docs.paygate.co.za/#country-codes
            'RETURN_URL'       => route('payment_response'),
            'TRANSACTION_DATE' => $DateTime->format('Y-m-d H:i:s'),
            'LOCALE'           => 'en-za', //https: //docs.paygate.co.za/#locale-codes
            'COUNTRY'          => 'ZAF', // https: //docs.paygate.co.za/#country-codes
            'EMAIL'            => $user_email, //'sulata@mailinator.com', // static
            'NOTIFY_URL'       => route('payment_notify'),
        );

        $checksum = md5(implode('', $data) . env('PAYGATE_SECRET'));
        $data['CHECKSUM'] = $checksum;        
        $response = Http::asForm()->post(env('PAYGATE_INITIATE_URL'), $data);
	    //dd($response->body());
        parse_str($response->body(), $output);
        if (!empty($output)) {
	        // return $output;
            $driverResult = DriverDetail::where('user_id',$userId)->first();
            $result = new DriverTransaction;
            $result->user_id = $userId;
            $result->driver_detail_id = $driverResult->id;
            $result->pay_request_id = $output['PAY_REQUEST_ID'];
            $result->paygate_id = $output['PAYGATE_ID'];
            $result->reference = $output['REFERENCE'];
            $result->checksum = $output['CHECKSUM'];
            $result->transaction_status = 'initiated';
            $result->save();
            return response()->json(['status' => 200, 'output'=>$output,'user_id'=>$userId, 'message' => 'Payment initiated successfully.']);

            //return view('payment.form', compact('output'));
        }

        // You can show some error messages here or redirect to another URL
        //return "Something went wrong";

        $response['status'] = 400;
        $response['error'] = "Something went Wrong!";
        return response()->json($response, 400);
    }


    // public function verifyCheckOutPayment(Request $request){
    //     $userId = Auth::user()->id;
    //     $input = request()->all();
    //     $rules = [
    //         //'user_id'=> 'required',
    //         //'plan_id'=> 'required',
    //         'amount'=> 'required',
    //     ];
    //     $validator = Validator::make($input, $rules);
    //     if ($validator->fails()) {
    //         $response = [
    //             'status' => 400,
    //             'error' => $validator->errors()
    //         ];
    //         return response()->json($response, 400);
    //     }else{
    //         $findUser = User::where('id',$userId)->first();    
    //         if($findUser){
    //             $driverDetails = DriverDetail::where('user_id',$userId)->first();
    //             if(isset($driverDetails)){
    //                 if($driverDetails->is_admin_approve == "Y"){
    //                     $response['status'] = 400;
    //                     $response['error'] = 'This document already verified by Administrator';
    //                     return response()->json($response, 400);
    //                 }

    //                 $driverDetails->driver_complete_date = date('Y-m-d H:i:s');
    //                 $driverDetails->is_driver_complete = "Y";
    //                 $driverDetails->is_update_inspection = "Y"; //only show in home page                    
    //                 $driverDetails->save();

    //                 $subRecord = UserSubscription::where('user_id',$userId)->first();
    //                 if(!isset($subRecord)){
    //                     $subscription = new UserSubscription;
    //                     $subscription->user_id = $userId;
    //                     $subscription->amount = $input['amount'];
    //                     $subscription->event_type = 1;
    //                     $subscription->is_runing = 1;
    //                     $subscription->status = 1;
    //                     $subscription->save();

    //                     $driverDetails->is_payment_completed = "Y";
    //                     $driverDetails->save();
    //                 }                   
    //                 $driverData = DriverDetail::where('is_driver_complete','N')->where('id',$driverDetails->id)->first();
    //                 if(isset($driverData)){
    //                     $driverData->is_driver_complete = "Y";
    //                     $driverData->save();
    //                 }
    //                 $driverResult = DriverDetail::where('id',$driverDetails->id)->first();
    //                 if($driverResult->is_admin_approve == "R"){
    //                     $driverResult->is_admin_approve = "P";
    //                     $driverResult->reject_document_reason = "";
    //                     $driverResult->save();
    //                 }
    //             }               
 
    //             $response['status'] = 200;
    //             $response['message'] = "Payment done successfully.";
    //             return response()->json($response, 200);
    //         }else{
    //             $response['status'] = 400;
    //             $response['error'] = "Something went Wrong!";
    //             return response()->json($response, 400);
    //         }                    
    //     }
    // }


    /*
    Description: Inspection home page listing
    */
    public function allInspectionList(){
        $userId = Auth::user()->id;
        $pastInspectionList = DriverDetail::with(['user' => function($query4){
                $query4->select('id', 'full_name', 'email','country_code','mobile');
            }])
            ->where('user_id',$userId)
        ->where('is_admin_approve','=','Y')->get()->toArray();

        $manageInspectionList = DriverDetail::with(['user' => function($query4){
            $query4->select('id', 'full_name', 'email','country_code','mobile');
        }])
        ->where('user_id',$userId)
        ->where('is_update_inspection','=','Y')
        ->get()->toArray();      
    
        $response['status'] = 200;
        $response['manageInspectionList'] = $manageInspectionList; 
        $response['pastInspectionList'] = $pastInspectionList;       
        $response['completedInspectionCount'] = count($pastInspectionList);
        $response['TotalInspectionCount'] = count($manageInspectionList);
        $response['message'] = "Inspection list fetch Successfully";
        return response()->json($response,200);
    }
    
  

    public function viewInspectionDetails(Request $request){
        $userId = Auth::user()->id;
        $input = $request->all();
        $rules = [
            'driverDetailId'=> 'required',  
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response,400);
        }else{
            $verificationDocList = DriverDetail::with(['user' => function($query4){
                $query4->select('id', 'full_name', 'email','country_code','mobile');
            }])
            ->with('driver_document')
            ->with('DriverVehicleCarType')
            ->with('document_picture')
            ->with('driver_verification_document')
            ->with('verification_document_picture')
            ->with('driver_vehicle_inspection_document')
            ->with('CarMake')->with('CarModel')
            ->where('user_id',$userId)
            ->where('id',$input['driverDetailId'])->first();

            if(isset($verificationDocList)){
                $response['status'] = 200;
                $response['viewDriverList'] = $verificationDocList;
                $response['message'] = "fetch successfully.";
                return response()->json($response,200); 
            }else{
                $response['status'] = 400;
                $response['error'] = 'This Driver Not Found.';
                return response()->json($response, 400);
            }    
        }

    }


    public function downloadInspectionReport(Request $request){
        
        $request->validate([
            'driverDetailId' => ['required'],
        ]);
        $driverDetailId = base64_encode($request->driverDetailId);
        $userId = base64_encode(Auth::user()->id);
        $url = route('download-driver-pdf').'?driverDetailId='.$driverDetailId.'&userId='.$userId;
        $path = public_path();
        $response['status'] = 200;
        $response['url'] = $url;
        return response()->json($response,200);
    }



    public function updateManageInspectionDriverDetail(Request $request){
        $userId = Auth::user()->id;
        $input = $request->all();
        $rules = [
            'driverDetailId' => 'required',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = [
                'status' => 400,
                'error' => $validator->errors()
            ];
            return response()->json($response,400);
        }else{
            $rideDetails = DriverDetail::with(['user' => function($query4){
                $query4->select('id', 'full_name', 'email','country_code','mobile');
            }])
            ->with('driver_document')
            ->with('DriverVehicleCarType')
            ->with('document_picture')
            ->with('verification_document_picture')
            ->with('CarMake')->with('CarModel')
            ->with('DriverVehicleCarType')
            ->where('user_id',$userId)
            ->where('id',$input['driverDetailId'])->first();
            if(isset($rideDetails)){
                $response['status'] = 200;
                $response['driverRecord'] = $rideDetails;
                $response['message'] = "fetch successfully.";
                return response()->json($response,200); 
            }else{
                $response['status'] = 400;
                $response['error'] = 'This Driver Not Found.';
                return response()->json($response, 400);
            }      
        }
    }
    
    

}
