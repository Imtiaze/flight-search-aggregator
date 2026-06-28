<?php 

namespace App\FlightProviders;

use App\Contracts\FlightProviderInterface;
use App\DataTransferObj\FlightDTO;
use App\DataTransferObj\SearchCriteria;
use Illuminate\Http\Client\Pool;
use Carbon\Carbon;

class ProviderCService implements FlightProviderInterface
{
    public function getName(): string
    {
        return 'ProviderCService';
    }

    public function buildPoolRequest(Pool $pool, SearchCriteria $criteria)
    {
        
    }

    public function fetchFlights(SearchCriteria $criteria): array
    {
        $rawData = [
            "results" => [
                [ "iata" => "AA", "route" => [ "src" => "DAC", "dst" => "DXB" ], "times" => [ "dep" => 1782892800, "arr" => 1782909000 ], "layovers" => 0, "total_price" => 335, "currency" => "USD", "code" => "AA101" ],
                [ "iata" => "CJ", "route" => [ "src" => "DAC", "dst" => "DXB" ], "times" => [ "dep" => 1782885600, "arr" => 1782903600 ], "layovers" => 2, "total_price" => 270, "currency" => "USD", "code" => "CJ300" ],
                [ "iata" => "EK", "route" => [ "src" => "DAC", "dst" => "DXB" ], "times" => [ "dep" => 1782877500, "arr" => 1782888600 ], "layovers" => 0, "total_price" => 405, "currency" => "USD", "code" => "EK585" ]
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