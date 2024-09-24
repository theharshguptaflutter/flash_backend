<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverVerificationDocumentPicture extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_detail_id','driver_id','road_worthiness_picture','functional_defect_picture','warning_light_picture','wheel_picture',
        'steering_picture','window_screen_picture','head_light_picture','indicator_light_picture','brake_light_picture','hooter_picture',
        'seat_belt_picture','jack_triangle_picture','status','front_right_wheel_picture','front_left_wheel_picture','back_left_wheel_picture',
        'back_right_wheel_picture','front_seat_belt_picture','passenger_seat_belt_picture','rear_seat_belt_picture'
    ];
}
