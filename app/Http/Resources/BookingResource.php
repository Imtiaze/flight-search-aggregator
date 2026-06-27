<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'reference' => $this->reference,
            'flight_signature' => $this->flight_key,
            'provider' => $this->provider_booked,
            'total_fare_usd' => $this->amount_paid,
            'passengers' => $this->passenger_details,
            'created_at' => $this->created_at->toIso8601String()
        ];
    }
}