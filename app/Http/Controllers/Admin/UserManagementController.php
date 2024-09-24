<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Auth;
use Hash;
use Illuminate\Support\Facades\Crypt;
use League\Flysystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\DriverDetail;
use App\Models\CarMake;
use Mail;
use App\Mail\RejectDocument;
use App\Models\CarModel;
use App\Models\DriverApprovalPayment;
use App\Mail\ApproveDocument;
use App\Models\BankDetail;



class UserManagementController extends Controller
{
    public function __construct() {
        $this->middleware('AdminMiddleware');
    }

    /**
        * Description :User Listing
    */
    public function userList(Request $request){
        $per_page = 10;
        $search_data = $request->all();

        $userList = User::searchUserList($request)->where('user_type','!=','A')
        ->orderBy('id','desc')->paginate($per_page);
        $userList->appends($search_data);
        return view('admin.user.list', compact('userList','search_data'));
    }

    public function userDetails($id){
        $userRecord = User::where(['id'=>$id])->first();
        return view('admin.user.view', compact('userRecord'));
    }



    /**
        * Description :Driver Listing
    */
    public function driverList(Request $request){
        $per_page = 10;
        $search_data = $request->all();

        $driverList = User::searchQueryBuilder($request)->where(['user_type'=>'D','driver_approval'=>'A'])
        ->orderBy('id','desc')->paginate($per_page);
        $driverList->appends($search_data);
        return view('admin.driver.list', compact('driverList','search_data'));
    }

    public function driverDetails($id){ 
        $userRecord = User::where(['user_type'=>'D','id'=>$id])->first(); 
        $userBankRecord = BankDetail::where('user_id',$id)->get()->toArray();
        //print_r($userBankRecord);exit;
        $driver = DriverDetail::with('user')->with('driver_document')
            ->with('DriverVehicleCarType')
            ->with('driver_verification_document')
            ->with('document_picture')
            ->with('verification_document_picture')
            ->with('driver_vehicle_inspection_document')
            ->with('CarMake')->with('CarModel')
            ->where('user_id',$id)
            ->first();   
        return view('admin.driver.detail', compact('driver','userRecord','userBankRecord'));
    }

    public function driverAwaitingList(Request $request){
        $per_page = 10;
        $search_data = $request->all();

        $driverList = User::searchQueryBuilder($request)->with('DriverDetails')->where('users.user_type','=','D')->where('users.driver_approval','!=',"A")
        ->orderBy('users.id','desc')->paginate($per_page);
        $driverList->appends($search_data);
        return view('admin.driver.awaiting', compact('driverList','search_data'));
    }

    public function driverAwaitingView($id){
        $userRecord = User::where(['user_type'=>'D','id'=>$id])->first();
        $driver = DriverDetail::with('user')
        ->with('driver_document')
        ->with('DriverVehicleCarType')
        ->with('document_picture')
        ->with('driver_verification_document')     
        ->with('verification_document_picture')
        ->with('driver_vehicle_inspection_document')
        ->with('CarMake')->with('CarModel')
        ->where('user_id',$id)
        ->first();   
        return view('admin.driver.view', compact('driver','userRecord'));
    }

    public function driverAwaitingUpdate(Request $request, $id){
        $emailErrorMsg = "";
        $input = $request->all();      
        $validator = Validator::make($input, [
            'approval_status' => 'required',
            'driver_detail_id'=> 'required',
            //'reject_document_reason'=> 'required',
            
        ]);
        if ($validator->fails())
        {
          return redirect()->back()->withErrors("Please fill up all fields");
        } else{
          $user = User::where("id",$id)->where("user_type","=","D")->first();
          if(isset($user)) {
            $driver = DriverDetail::where(['id'=>$input['driver_detail_id'], 'user_id'=>$id])->first();
            if(isset($driver)){
                $user->driver_approval = $input['approval_status'];
                $user->save();
                if($input['approval_status'] == "A"){                    
                    $inspectionNo = strtoupper(substr(md5(time().rand(10000,99999)), 0, 10));
                    $driver->is_admin_approve = "Y";
                    $driver->inspection_date = date('Y-m-d H:i:s');
                    $driver->unique_inspection_id = $inspectionNo;
                    $driver->reject_document_reason = "";
                    $driver->is_driver_complete = "Y";
                    $driver->is_update_inspection = "N";
                    $driver->save();
                    // $msg = 'Your Account has been verified By Flash app team.';
                    // $user_email = $user->email;
                    // $user_name = $user->full_name;
                    // $content = [
                    //     'fullName'=> $user_name,
                    //     'msg' =>  $msg,
                    // ];
                    // try{ 
                    //     Mail::to($user_email)->send(new ApproveDocument($content)); 
                    // }catch(Exception $e){
                    //     $emailErrorMsg = $e->getMessage();
                    // }
                    // if($emailErrorMsg != ""){
                    //     return redirect()->back()->withErrors($emailErrorMsg);
                    // }
                    return redirect()->route('driver-list')->with('success','Driver status updated successfully.');
                }else if($input['approval_status'] == "P"){                   
                    $driver->is_admin_approve = "P";
                    $driver->inspection_date = date('Y-m-d H:i:s');
                    $driver->unique_inspection_id = "";
                    $driver->save();
                    return redirect()->route('driver-awaiting-list')->with('success','Driver status updated successfully.');
                }else{
                    $driver->is_admin_approve = "R";
                    $driver->inspection_date = date('Y-m-d H:i:s');
                    $driver->unique_inspection_id = "";
                    $driver->reject_document_reason = isset($input['reject_document_reason'])?$input['reject_document_reason']:"";
                    $driver->is_driver_complete = "N";
                    $driver->update();
                    
                    // $msg = 'Your Document is Rejected By Flash app team.';
                    // $user_email = $user->email;
                    // $user_name = $user->full_name;
                    // $reason = isset($input['reject_document_reason'])?$input['reject_document_reason']:"N/A";
                    // $content = [
                    //     'fullName'=> $user_name,
                    //     'msg' =>  $msg,
                    //      'reason' => $reason,
                    // ];
                    // try{ 
                    //     Mail::to($user_email)->send(new RejectDocument($content)); 
                    // }catch(Exception $e){
                    //     $emailErrorMsg = $e->getMessage();
                    // }
                    // if($emailErrorMsg != ""){
                    //     return redirect()->back()->withErrors($emailErrorMsg);
                    // }
                   
                    return redirect()->route('driver-awaiting-list')->with('success','Driver status updated successfully.');
                }             
            }else{
                return redirect()->back()->withErrors( 'Driver record Not Found');
            }
          }else{
            return redirect()->back()->withErrors( 'User Not Found');
          }
        }     
       
    }

    /**
        * Description :User Status change
    */

    public function statusChange(Request $request){
        $user_id = $request->input('userid');
        $isActive =  $request->input('isActive');
        $oldStatus = User::where('id', $user_id)->first();
        if($isActive == "Y"){
            $user = User::where('id',$user_id)->update([ 'status' => "Y" ]);   
            echo "Y";        
        } elseif($isActive == "I"){
                $user = User::where('id',$user_id)->update([ 'status' => "I" ]); 
                echo "Y"; 
        }else{
            echo "N";
        }
    }

    public function carMakeList(Request $request){
        $per_page = 10;
        $search_data = $request->all();
        $carMakeList = CarMake::where('status',1)->orderBy('id','desc')->paginate($per_page);
        $carMakeList->appends($search_data);
        return view('admin.car_make.list', compact('carMakeList','search_data'));
    }

    public function carMakeAdd(){
        return view('admin.car_make.add');
    }

    public function carMakeStore(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',            
        ]);
        if ($validator->fails()){
          return redirect()->back()->withErrors("Please fill up all fields");
        } else{
            $carMake = new CarMake;
            $carMake->name = $input['name'];
            $carMake->save();
            return redirect()->route('car-make-list')->with('success','Car make name added successfully.');
        }
    }

    public function carMakeView($id){
        $car_make = CarMake::where('id',$id)->first();
        return view('admin.car_make.edit', compact('car_make'));
    }

    public function carMakeUpdate(Request $request, $id){
        $input = $request->all();      
        $validator = Validator::make($input, [
            'name' => 'required',               
        ]);
        if ($validator->fails()){
          return redirect()->back()->withErrors("Please fill up all fields");
        } else{
            $result = CarMake::where('id',$id)->first();
            if(isset($result)){
                $result->name = $input['name'];
                $result->save();
                return redirect()->route('car-make-list')->with('success','Car make name updated successfully.');
            }else{
                return redirect()->back()->withErrors( 'No Record Found.');
            }
        }
    }

    public function carMakeDelete($id){
        $findCarMake = DriverDetail::where('make',$id)->first();
        $result = CarMake::where('id', $id)->first();
        if(isset($result)) {
            if(isset($findCarMake)){
                return redirect()->back()->withErrors($result->name. ' is associated with driver record.');
            }
            $car_model_record = CarModel::where('car_make_id',$id)->get()->toArray();
            if(count($car_model_record)>0){
                CarModel::where('car_make_id',$id)->delete();
            }
            CarMake::where('id',$id)->delete();
            return redirect()->route('car-make-list')->with('success','Car make name deleted successfully.');
        }else{
          return redirect()->back()->withErrors('No Record Found.');
        }
    }

    public function carModelList(Request $request){
        $per_page = 10;
        $search_data = $request->all();
        $carModelList = CarModel::with('car_make')->where('status',1)->orderBy('id','desc')->paginate($per_page);
        //echo "<pre>";print_r($carModelList);exit;
        $carModelList->appends($search_data);
        return view('admin.car_model.list', compact('carModelList','search_data'));
    }

    public function carModelAdd(){
        $carMakeList = CarMake::where('status',1)->orderBy('id','desc')->get()->toArray();
        return view('admin.car_model.add',compact('carMakeList'));
    }

    public function carModelStore(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'car_make_id' => 'required',          
        ]);
        if ($validator->fails()){
          return redirect()->back()->withErrors("Please fill up all fields");
        } else{
            $carModel = new CarModel;
            $carModel->car_make_id = $input['car_make_id'];
            $carModel->name = $input['name'];
            $carModel->save();
            return redirect()->route('car-model-list')->with('success','Car model name added successfully.');
        }
    }

    public function carModelView($id){
        $car_model = CarModel::with('car_make')->where('id',$id)->first();
        $carMakeList = CarMake::where('status',1)->orderBy('id','desc')->get()->toArray();
        //echo "<pre>";print_r($car_model);exit;
        return view('admin.car_model.edit', compact('car_model','carMakeList'));
    }

    public function carModelUpdate(Request $request, $id){
        $input = $request->all();      
        $validator = Validator::make($input, [
            'name' => 'required', 
            'car_make_id' => 'required',               
        ]);
        if ($validator->fails()){
          return redirect()->back()->withErrors("Please fill up all fields");
        } else{
            $findCarModel = DriverDetail::where('model',$id)->where('make','!=', $input['car_make_id'])->first();
            if(isset($findCarModel)){
                return redirect()->back()->withErrors('Make & Model is associated with driver record.');
            }
            $result = CarModel::where('id',$id)->first();
            if(isset($result)){
                $result->name = $input['name'];
                $result->car_make_id = $input['car_make_id'];
                $result->save();
                return redirect()->route('car-model-list')->with('success','Car model name updated successfully.');
            }else{
                return redirect()->back()->withErrors( 'No Record Found.');
            }
        }
    }

    public function carModelDelete($id){
        $findCarModel = DriverDetail::where('model',$id)->first();
        $result = CarModel::where('id', $id)->first();
        if(isset($result)) {
            if(isset($findCarModel)){
                return redirect()->back()->withErrors($result->name. ' is associated with driver record.');
            }
            CarModel::where('id',$id)->delete();
            return redirect()->route('car-model-list')->with('success','Car model name deleted successfully.');
        }else{
          return redirect()->back()->withErrors('No Record Found.');
        }
    }

    public function approvalAmountList(Request $request){
        $per_page = 10;
        $search_data = $request->all();
        $approvalList = DriverApprovalPayment::orderBy('id','desc')->paginate($per_page);
        $approvalList->appends($search_data);
        return view('admin.driver_approval_amount.list', compact('approvalList','search_data'));
    }

    public function approvalAmountView($id){
        $payment = DriverApprovalPayment::where('id',$id)->first();
        return view('admin.driver_approval_amount.edit', compact('payment'));
    }

    public function approvalAmountUpdate(Request $request, $id){
        $input = $request->all();      
        $validator = Validator::make($input, [
            'amount' => 'required', 
            'percentage' => 'required', 
            'tax' => 'required',  
            'total_amount' => 'required',             
        ]);
        if ($validator->fails()){
          return redirect()->back()->withErrors("Please fill up all fields");
        } else{
            $result = DriverApprovalPayment::where('id',$id)->first();
            if(isset($result)){
                $result->amount = $input['amount'];
                $result->percentage = $input['percentage'];
                $result->tax = $input['tax'];
                $result->total_amount = $input['total_amount'];
                $result->save();
                return redirect()->route('approval-amount-list')->with('success','Amount updated successfully.');
            }else{
                return redirect()->back()->withErrors( 'No Record Found.');
            }
        }
    }
}

?>