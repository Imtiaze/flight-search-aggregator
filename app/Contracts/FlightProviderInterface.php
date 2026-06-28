<?php 

namespace App\Contracts;

use App\DataTransferObj\SearchCriteria;
use Illuminate\Http\Client\Pool;

interface FlightProviderInterface
{
    public function getName(): string;

    /**
     * Let each provider attach its own specific URL and query keys to the parallel pool.
     */
    public function buildPoolRequest(Pool $pool, SearchCriteria $criteria);

    /**
     * @return array<int, \App\DTO\FlightDTO>
     */
    public function parseResponse(array $rawData): array;
}