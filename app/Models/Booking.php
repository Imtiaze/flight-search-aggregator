<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'reference',
        'flight_key',
        'provider_booked',
        'amount_paid',
        'passenger_details'
    ];
}
