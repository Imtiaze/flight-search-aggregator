<?php 

namespace App\DataTransferObj;

use Carbon\Carbon;

readonly class FlightDTO
{
    public function __construct(
        public string $provider,
        public string $carrier,
        public string $flightNo,
        public string $from,
        public string $to,
        public Carbon $departureTime,
        public Carbon $arrivalTime,
        public int $stops,
        public float $fareUsd
    ) {}
}