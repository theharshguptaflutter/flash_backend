<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'bank_name','holder_name','account_number','branch_code','swift_code','set_as_primary'
    ];
}
