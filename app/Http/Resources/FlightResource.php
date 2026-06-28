<?php 

namespace App\Http\Resources;

use App\Support\FlightIdGenerator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FlightResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $passengerCount = (int) $request->input('passengers', 1);
        
        return [
            'id' => FlightIdGenerator::generate($this->resource),
            'carrier' => $this->carrier,
            'flight_number' => $this->flightNo,
            'origin' => $this->from,
            'destination' => $this->to,
            'departure_time' => $this->departureTime->toIso8601String(),
            'arrival_time' => $this->arrivalTime->toIso8601String(),
            'duration_minutes' => $this->departureTime->diffInMinutes($this->arrivalTime),
            'stops' => $this->stops,
            'price' => [
                'amount' => $this->fareUsd * $passengerCount,
                'currency' => 'USD'
            ]
        ];
    }
}