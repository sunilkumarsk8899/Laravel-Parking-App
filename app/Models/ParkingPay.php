<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingPay extends Model
{
    use HasFactory;
    protected $fillable = [
        'vehicle_id',
        'paid',
        'message',
        'pay_status',
        'status'
    ];
}
