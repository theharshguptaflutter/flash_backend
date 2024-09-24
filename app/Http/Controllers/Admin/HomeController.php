<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\User;
use Hash;




class HomeController extends Controller
{

  public function __construct()
  {
      $this->middleware('AdminMiddleware');
  }

  /**
     * Description : Admin Dashboard
     */
  public function Dashboard()
  {
      return view('admin.dashboard');
  }

  /**
    * Description : View For error Page
  */
  public function pageNotFound(){
    $userData = Auth::user();
    if(isset($userData)){
      return redirect('admin/dashboard');
    }else{
      Auth::logout();
      return view('admin.errors.404');
    }
  }

  /**
    * Description : Admin Profile Show
  */
  public function adminProfile()
  {
    $userData = Auth::user();
    $error = "";
    $uploaded_file_path = "";
    if($userData->profile_picture != NULL){
      $uploaded_file_path = $userData->profile_picture;
    }else{
      $uploaded_file_path = "";
    } 
    return view('admin.Profile',['uploaded_file_path'=>$uploaded_file_path]);
  }

  /**
    * Description : Admin Profile Update
  */
  public function adminProfileSave(Request $request)
  {
      $postData = $request->all();
      $uploaded_file_path = "";
      $validator = Validator::make($postData, [
            'full_name' => 'required|string',
            'email' => 'required|email',
            'country_code'=>'required',
            'mobile'=>'required'
      ]);
      if ($validator->fails())
      {
        return redirect()->back()->withErrors("Please fill up all fields");
      }else{
        $phone_code = "+"."".$request['country_code'];
        $userData = Auth::user();
        $findUser = User::select('email')->where('email',$request['email'])->where('id','!=',$userData->id)->first(); 
       
        if(isset($findUser) && !empty($findUser)){
          return redirect()->back()->withErrors("This email has already exist.");
        }else{
          if(isset($userData)){ 
            $error = "";
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                $imageName = $request['profile_image']->getClientOriginalName();
                $newImageName = str_replace("","_",trim(pathinfo( $imageName,PATHINFO_FILENAME)," "));
                $extension = pathinfo($imageName,PATHINFO_EXTENSION);
                $originalName = $newImageName.".".$extension;
                $finalOriginalName = time().'_'.$originalName;                                     
                $destinationPath = public_path().'/images/';
                $request['profile_image']->move($destinationPath,$finalOriginalName); 
                $userData->profile_picture = $finalOriginalName;
            }else{
              if($userData->profile_picture !=NULL){
                $uploaded_file_path = $userData->profile_picture;
              }else{
                $uploaded_file_path = "";
              } 
              //$userData->profile_pic = $userData->profile_pic;
            }
            $userData->full_name = trim($request['full_name']);
            $userData->email = ($request['email']);
            $userData->country_code = trim($phone_code);
            $userData->mobile = $request['mobile'];
            if($userData->save()){
              return redirect()->back()->with(['uploaded_file_path'=>$uploaded_file_path])->withSuccess('Profile Updated Successfully');
            }else{
                return redirect()->back()->withErrors("Profile not updated successfully");
            }           
          }else{
            return redirect()->back()->withErrors("Something went Wrong!");
          } 
        }
      }
  }

  /**
    * Description : Admin Change Password
  */
  public function adminChangePassword(Request $request)
  {
     $input = $request->all();
     $validator = Validator::make($input, [
          'old_password' => 'required',
          'new_password'=>'required',
          'confirm_password'=>'required|same:new_password',
      ]);
      if ($validator->fails())
      {
        return redirect()->back()->withErrors("Please fill up all fields");
      }else{
        $user = User::where('id',Auth::user()->id)->first();
        if (isset($user)) {
          if (Hash::check($request['old_password'], Auth::user()->password)) {
            User::where('id',Auth::user()->id)->update([
              'password'=>Hash::make($request['new_password'])
            ]);
            return redirect()->back()->withSuccess('Password Updated Successfully');
          }else{
            return redirect()->back()->withErrors('Incorrect Old password');
          }
        }else{
          return redirect()->back()->withErrors('User Not Found');
        }
      }
  }
}
