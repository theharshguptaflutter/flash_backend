<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','plan_id', 'subscription_id','amount','discount','total_amount',
       'plan_period_start_date','plan_period_end_date','event_type','is_cancel', 'is_runing', 'status',
       'api_subscription_response','created_at','updated_at' 
   ];
}
