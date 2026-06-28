<?php 

namespace App\FlightProviders;

use App\Contracts\FlightProviderInterface;
use App\DataTransferObj\{
    SearchCriteria,
    FlightDTO
};
use Carbon\Carbon;

class ProviderDService implements FlightProviderInterface
{
    public function getName(): string
    {
        return 'ProviderDService';
    }

    public function fetchFlights(SearchCriteria $criteria): array
    {
        $rawData = [
            "results" => [
                [
                    "carrier" => "QR",
                    "from" => "DAC",
                    "to" => "DXB",
                    "depart" => "2026-07-01T01:50:00",
                    "arrive" => "2026-07-01T05:35:00",
                    "stops" => 0,
                    "fare_usd" => 385.00,
                    "flight_no" => "QR639"
                ],
                [
                    "carrier" => "TG",
                    "from" => "DAC",
                    "to" => "DXB",
                    "depart" => "2026-07-01T06:20:00",
                    "arrive" => "2026-07-01T13:10:00",
                    "stops" => 1,
                    "fare_usd" => 295.00,
                    "flight_no" => "TG340"
                ],
                [
                    "carrier" => "SV",
                    "from" => "DAC",
                    "to" => "DXB",
                    "depart" => "2026-07-01T11:45:00",
                    "arrive" => "2026-07-01T17:55:00",
                    "stops" => 1,
                    "fare_usd" => 315.00,
                    "flight_no" => "SV803"
                ],
                [
                    "carrier" => "WY",
                    "from" => "DAC",
                    "to" => "DXB",
                    "depart" => "2026-07-01T19:15:00",
                    "arrive" => "2026-07-02T00:10:00",
                    "stops" => 0,
                    "fare_usd" => 350.00,
                    "flight_no" => "WY318"
                ],
                [
                    "carrier" => "FZ",
                    "from" => "DAC",
                    "to" => "DXB",
                    "depart" => "2026-07-01T15:30:00",
                    "arrive" => "2026-07-01T20:20:00",
                    "stops" => 0,
                    "fare_usd" => 275.00,
                    "flight_no" => "FZ524"
                ]
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