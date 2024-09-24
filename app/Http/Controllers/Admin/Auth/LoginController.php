<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    // composer require laravel/ui
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
      return view('admin.auth.login');
    }

    public function LoginSubmit(Request $request)
    {
      $input = $request->all();
      
      $validator = Validator::make($input, [
        'email' => 'required|email',
        'password'=>'required',
      ]);
        if ($validator->fails())
        {
          return redirect()->back()->withErrors("Please fill up all fields");
        }
        else{
          $user = User::where("email",$input['email'])->where("user_type","=","A")->first();
          if (isset($user)) {
            if (Auth::attempt(['email' => $input['email'], 'password' => $input['password'], 'user_type'=>'A'])){
              return redirect('admin/dashboard');
            }else{
              return redirect()->back()->withErrors('Your Password is incorrect');
            }
          }else{
            return redirect()->back()->withErrors( 'Invalid Login credential');
          }
        }      
    }


    public function logout(Request $request) {
        Auth::logout();
        return redirect('/admin');
    }
}
