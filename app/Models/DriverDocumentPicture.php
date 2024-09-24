<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverDocumentPicture extends Model
{
    use HasFactory;
    protected $fillable = [
        'driver_detail_id','driver_id','id_number_picture','registration_picture','license_picture','vin_picture',
        'exterior_color_picture','interior_color_picture','first_registration_picture','road_worthy_picture','license_expiration_picture','status','km_reading_picture'
    ];
}
