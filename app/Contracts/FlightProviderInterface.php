<?php 

namespace App\Contracts;

use App\DataTransferObj\SearchCriteria;

interface FlightProviderInterface
{
    public function getName(): string;

    /**
     * Let each provider attach its own specific URL and query keys
     */
     public function fetchFlights(SearchCriteria $criteria): array;

    /**
     * @return array<int, \App\DTO\FlightDTO>
     */
    public function parseResponse(array $rawData): array;
}