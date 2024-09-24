<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverApprovalPayment extends Model
{
    use HasFactory;
    protected $table = 'driver_approval_payments';
    protected $fillable = [
        'amount','tax','total_amount','percentage'
    ];
}
