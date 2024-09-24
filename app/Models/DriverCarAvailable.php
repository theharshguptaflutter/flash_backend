<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverCarAvailable extends Model
{
    use HasFactory;
    protected $fillable = [
        'driver_id','cur_lat','cur_long','cur_location','for_hire','is_available','is_requested'
    ];
}
