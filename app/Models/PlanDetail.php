<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'plan_id', 'name','created_at','updated_at' 
   ];
}
