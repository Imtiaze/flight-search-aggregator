<?php 

namespace App\FlightProviders;

use App\Contracts\FlightProviderInterface;
use App\DataTransferObj\FlightDTO;
use App\DataTransferObj\SearchCriteria;
use Illuminate\Http\Client\Pool;
use Carbon\Carbon;

class ProviderBService implements FlightProviderInterface
{
    public function getName(): string
    {
        return 'ProviderBService';
    }

    public function buildPoolRequest(Pool $pool, SearchCriteria $criteria)
    {
        
    }

    public function fetchFlights(SearchCriteria $criteria): array
    {
        $rawData = [
            "data" => [
                [ "airline_code" => "BS", "origin" => "DAC", "destination" => "DXB", "departure_time" => "2026-07-01 09:15", "arrival_time" => "2026-07-01 15:00", "segments" => 1, "price" => [ "amount" => 295, "currency" => "USD" ], "number" => "BS220" ],
                [ "airline_code" => "BS", "origin" => "DAC", "destination" => "DXB", "departure_time" => "2026-07-01 14:30", "arrival_time" => "2026-07-01 19:20", "segments" => 1, "price" => [ "amount" => 265, "currency" => "USD" ], "number" => "BS118" ],
                [ "airline_code" => "EK", "origin" => "DAC", "destination" => "DXB", "departure_time" => "2026-07-01 03:45", "arrival_time" => "2026-07-01 06:50", "segments" => 0, "price" => [ "amount" => 399, "currency" => "USD" ], "number" => "EK585" ]
            ]
        ];

        return $this->parseResponse($rawData);
    }

    public function parseResponse(array $rawData): array
    {
        $flights = [];

        foreach ($rawData['flights'] ?? [] as $flight) {
            $flights[] = new FlightDTO(
                provider: $this->getName(),
                carrier: $flight['carrier'],
                flightNo: $flight['flight_no'],
                from: $flight['from'],
                to: $flight['to'],
                departureTime: Carbon::parse($flight['depart']),
                arrivalTime: Carbon::parse($flight['arrive']),
                stops: (int) $flight['stops'],
                fareUsd: (float) $flight['fare_usd']
            );
        }

        return $flights;
    }
}