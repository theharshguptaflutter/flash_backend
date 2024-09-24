<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Http\Request;
use App\Models\DriverDetail;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens,HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'full_name', 'gender','email','password','balance',
        'address','city','state','zipcode', 'country_code', 'mobile',
        'profile_picture','stripe_customer_id','driver_approval','cur_lat','cur_long','location','is_online','avg_rating','is_covid_accepted', 'user_type', 'login_type','status','step',
        'email_verified_at','remember_token','created_at','updated_at','ride_otp','auth_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','auth_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $dates = ['deleted_at'];



    public function UserTokens()
    {
        return $this->hasOne('App\Models\UserToken', 'user_id', 'id');
    }

    public function DriverDetails()
    {
        return $this->hasOne('App\Models\DriverDetail', 'user_id', 'id');
    }

    public function DriverReview()
    {
        return $this->hasMany('App\Models\DriverReview', 'passenger_id', 'id');
    }
    
    // public function PassengerRideDetails() 
    // {
    //     return $this->hasMany(PassengerRideDetail::class);
    // }


    public static function searchQueryBuilder(Request $request){
        $query = User::where("users.user_type","D");
        if($request->filled('search_full_name')){            
            $query->where('users.full_name', 'LIKE',"%".$request->search_full_name."%" );
        }

        if($request->filled('search_email')){            
            $query->where('users.email', 'LIKE',"%".$request->search_email."%" );
        }  
        if($request->filled('search_mobile')){ 
            $query->where(DB::raw("CONCAT(`country_code`, '', `mobile`)"), 'LIKE',"%".$request->search_mobile."%"); 
        } 
        if($request->filled('search_verification_status')){ 
            if($request->search_verification_status == "U"){ //uploaded
                $userIds = DriverDetail::select('user_id')->where('driver_details.is_driver_complete', '=', 'Y')->get()->toArray();
                $query = User::whereIn('users.id',$userIds);
            }  
            if($request->search_verification_status == "R"){
                $userIds = DriverDetail::select('user_id')->where('driver_details.is_admin_approve', '=', 'R')->get()->toArray();
                $query = User::whereIn('users.id',$userIds);
            }  
            if($request->search_verification_status == "P"){
                $query->where('users.driver_approval', '=', $request->search_verification_status);
            }          
        }       
        return $query;
    }

    // when image click popup image show in laravel blade file

    public static function searchUserList(Request $request){
        $query = User::where('user_type','!=','A');
        if($request->filled('search_full_name')){            
            $query->where('users.full_name', 'LIKE',"%".$request->search_full_name."%" );
        }

        if($request->filled('search_email')){            
            $query->where('users.email', 'LIKE',"%".$request->search_email."%" );
        }  
        if($request->filled('search_mobile')){
            $query->where(DB::raw("CONCAT(`country_code`, '', `mobile`)"), 'LIKE', "%".$request->search_mobile."%");  
        } 
        if($request->filled('search_status')){  
            if($request->search_status == "P"){        
                $query->where('users.user_type', '=', $request->search_status);
            }
            if($request->search_status == "D"){        
                $query->where('users.user_type', '=', $request->search_status);
            }
        } 
        return $query;
    }

    
}
