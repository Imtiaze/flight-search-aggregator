<?php 

namespace App\Support;

use App\DataTransferObj\FlightDTO;
use Exception;

class FlightIdGenerator
{
    /**
     * Encodes core matching details + source identifier into a stable frontend key.
     */
    public static function generate(FlightDTO $flight): string
    {
        $payload = [
            'sig' => sprintf('%s-%s-%s', $flight->carrier, $flight->flightNo, $flight->departureTime->format('YmdHi')),
            'prv' => $flight->provider,
            'val' => $flight->fareUsd
        ];

        return base64_encode(json_encode($payload));
    }

    /**
     * Decodes the payload for confirmation handling inside downstream booking pipelines.
     */
    public static function decode(string $id): array
    {
        $decoded = json_decode(base64_decode($id), true);
        
        if (!$decoded || !isset($decoded['sig'], $decoded['prv'], $decoded['val'])) {
            throw new Exception("Invalid or corrupted flight token structure.");
        }

        return $decoded;
    }
}