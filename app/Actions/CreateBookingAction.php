<?php 

namespace App\Actions;

use App\Models\Booking;
use Illuminate\Support\Str;

class CreateBookingAction
{
    public function execute(string $flightId, array $passengers): Booking
    {
        //TODO::upate later with real info
        return Booking::create([
            'reference' => 'IBX' . strtoupper(Str::random(8)),
            'flight_key' => 'flight_key',
            'provider_booked' => 'proivder_booked',
            'amount_paid' => 100,
            'passenger_details' => json_encode($passengers),
        ]);
    }
}