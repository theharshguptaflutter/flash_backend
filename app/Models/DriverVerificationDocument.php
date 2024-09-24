<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverVerificationDocument extends Model
{
    use HasFactory;
    protected $fillable = [
        'driver_detail_id','driver_id','inspector_id','is_road_worth','is_functional_defects','is_warning_light_present','is_wheels_present',
        'is_steering_present','is_window_screen_wiper','is_head_light_present',
        'is_indicator_light_present','is_brake_light_present','is_hooter_present','is_seat_belts_present','is_spare_jack_triangle_present',
        'status'
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'driver_id');
    }

}
