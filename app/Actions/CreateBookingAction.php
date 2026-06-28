<?php 

namespace App\Actions;

use App\Support\FlightIdGenerator;
use Illuminate\Support\Str;
use App\Models\Booking;

class CreateBookingAction
{
    public function execute(string $flightId, array $passengers): Booking
    {
        $flightDetails = FlightIdGenerator::decode($flightId);
        
        return Booking::create([
            'reference' => 'IBX' . strtoupper(Str::random(8)),
            'flight_key' => $flightDetails['sig'],
            'provider_booked' => $flightDetails['prv'],
            'amount_paid' => $flightDetails['val'] * count($passengers),
            'passenger_details' => $passengers,
        ]);
    }
}