<?php 

namespace App\Services;

class FlightDeduplicator
{
    public function deduplicate(array $flights): array
    {
        $processed = [];

        foreach ($flights as $flight) {
            // Uniqueness evaluated via Carrier, Flight Number, and exact Departure timestamp context.
            $uniqueKey = sprintf(
                '%s-%s-%s',
                $flight->carrier,
                $flight->flightNo,
                $flight->departureTime->format('YmdHi')
            );

            if (!isset($processed[$uniqueKey])) {
                $processed[$uniqueKey] = $flight;
                continue;
            }

            // Price evaluation step - keep the cheaper choice if matching duplicates occur
            if ($flight->fareUsd < $processed[$uniqueKey]->fareUsd) {
                $processed[$uniqueKey] = $flight;
            }
        }

        return array_values($processed);
    }
}