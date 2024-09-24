<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassangerTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id','pay_request_id','paygate_id','reference','transaction_id','result_code','auth_code','currency','amount',
        'result_desc','pay_method','pay_method_detail','vault_id','payvault_data_1','payvault_data_2','transaction_status','checksum','transaction_date'
    ];

}
