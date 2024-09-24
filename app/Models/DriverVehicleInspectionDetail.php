<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverVehicleInspectionDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id','driver_detail_id','vehicle_inspection_document','vehicle_document_type','status'
    ];
}
