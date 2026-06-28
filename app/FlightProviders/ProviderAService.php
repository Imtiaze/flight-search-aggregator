<?php 

namespace App\FlightProviders;

use App\Contracts\FlightProviderInterface;
use App\DataTransferObj\FlightDTO;
use App\DataTransferObj\SearchCriteria;
use Illuminate\Http\Client\Pool;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class ProviderAService implements FlightProviderInterface
{
    public function getName(): string
    {
        return 'ProviderAService';
    }

    public function buildPoolRequest(Pool $pool, SearchCriteria $criteria)
    {
        
    }

    public function fetchFlights(SearchCriteria $criteria): array
    {
        $rawData = [
            "flights" => [
                [ "carrier" => "AA", "from" => "DAC", "to" => "DXB", "depart" => "2026-07-01T08:00:00", "arrive" => "2026-07-01T12:30:00", "stops" => 0, "fare_usd" => 320.00, "flight_no" => "AA101" ],
                [ "carrier" => "AA", "from" => "DAC", "to" => "DXB", "depart" => "2026-07-01T22:10:00", "arrive" => "2026-07-02T02:40:00", "stops" => 0, "fare_usd" => 280.00, "flight_no" => "AA205" ],
                [ "carrier" => "BS", "from" => "DAC", "to" => "DXB", "depart" => "2026-07-01T09:15:00", "arrive" => "2026-07-01T15:00:00", "stops" => 1, "fare_usd" => 310.00, "flight_no" => "BS220" ],
                [ "carrier" => "EK", "from" => "DAC", "to" => "DXB", "depart" => "2026-07-01T03:45:00", "arrive" => "2026-07-01T06:50:00", "stops" => 0, "fare_usd" => 410.00, "flight_no" => "EK585" ]
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