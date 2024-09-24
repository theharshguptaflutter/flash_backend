<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverDocumentDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'driver_detail_id','user_id','professional_driving_permit_name','driver_photo','driving_evaluation_report','safety_screening_result',
        'vehicle_insurance_policy','vehicle_card_double_disk','vehicle_inspection_id','locate_inspection_center_name','vehicle_document'
    ];
    
}
