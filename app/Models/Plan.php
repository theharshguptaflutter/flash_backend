<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;
    protected $fillable = [
        'plan_name', 'amount','discount','note','currency',
       'recurring_type','status','created_at','updated_at' 
   ];

   public function PlanDetails()
   {
       return $this->hasMany('App\Models\PlanDetail', 'plan_id', 'id');
   }
}
