<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DriverDetail;
use App\Models\User;
use App\Models\DriverVerificationDocument;
use Carbon\Carbon;
use PDF;
use DB;



class PDFController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
       
    }

    public function driverPdf(Request $request){
        $input = $request->all();
        if($request->filled('driverDetailId') && $request->filled('userId')){
            $driverDetailId = base64_decode($input['driverDetailId']);
            $userId = base64_decode($request['userId']);

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
            ->where('id',$driverDetailId)->first();
            if(isset($verificationDocList)){
                $data = [
                    'list' => $verificationDocList, 
                ];
                  
                $pdf = PDF::loadView('pdf/driver_inspection_report', $data);
                return $pdf->download(time().'inspection_report.pdf');
            }else{
                $data = [
                    'list' => $verificationDocList, 
                ];
                  
                $pdf = PDF::loadView('pdf/driver_inspection_report', $data);
                return $pdf->download(time().'inspection_report.pdf');
            }
            
        }else{
            $data = [
                'list' => $verificationDocList, 
            ];
              
            $pdf = PDF::loadView('pdf/driver_inspection_report', $data);
            return $pdf->download(time().'inspection_report.pdf');         
        }
    }

    public function driverTermCondition(){
        return view('admin.terms_conditions.driver_terms_conditions');
    }

    public function passengerTermCondition(){
        return view('admin.terms_conditions.passenger_terms_conditions');
    }

    public function privacyPolicy(){
        return view('admin.terms_conditions.privacy_policy');
    }

    public function contactUs(){
        return view('admin.contact_detail.contact');
    }

}