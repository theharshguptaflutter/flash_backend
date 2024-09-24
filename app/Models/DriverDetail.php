<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DriverDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];


    protected $fillable = [
        'user_id','inspector_id','owner_name','id_number','make','model','driver_id_number','driver_license_number',
        'vehicle_description','year','registration_number','km_reading','license_number',
        'vin_number','exterior_color','interior_color','interior_trim','transmission','provinence',
        'start_date_registration','end_date_road_worthy','seating_capacity','vehicle_license_expiry','inspection_date',
        'is_admin_approve','unique_inspection_id','status','driver_complete_date','is_driver_complete','reject_document_reason','is_update_inspection'
    ];

    public function CarMake()
    {
        return $this->hasOne('App\Models\CarMake', 'id', 'make');
    }

    public function CarModel()
    {
        return $this->hasOne('App\Models\CarModel', 'id', 'model');
    }

    public function DriverVehicleCarType()
    {
        return $this->hasOne('App\Models\CarTypeDetail', 'id', 'vehicle_description');
    }

    public function driver_document()
    {
        return $this->hasOne('App\Models\DriverDocumentDetail', 'driver_detail_id', 'id');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id')->withTrashed();
    }

    public function document_picture()
    {
        return $this->hasOne('App\Models\DriverDocumentPicture', 'driver_detail_id', 'id');
    }

    public function verification_document_picture()
    {
        return $this->hasOne('App\Models\DriverVerificationDocumentPicture', 'driver_detail_id', 'id');
    }

    public function driver_verification_document()
    {
        return $this->hasOne('App\Models\DriverVerificationDocument', 'driver_detail_id', 'id');
    }

    public function driver_vehicle_inspection_document()
    {
        return $this->hasMany('App\Models\DriverVehicleInspectionDetail', 'driver_detail_id', 'id');
    }

    public function driver_transaction()
    {
        return $this->hasOne('App\Models\DriverTransaction', 'driver_detail_id', 'id');
    }
}
