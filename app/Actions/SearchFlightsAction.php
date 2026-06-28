<?php 

namespace App\Actions;

use App\DataTransferObj\SearchCriteria;
use App\Services\FlightAggregator;
use App\Services\FlightDeduplicator;
use App\Services\FlightSorter;

class SearchFlightsAction 
{
    public function __construct(
        private FlightAggregator $aggregator,
        private FlightDeduplicator $deduplicator,
        private FlightSorter $sorter
    ) {}

    public function execute(SearchCriteria $criteria, array $filters, string $sortBy, string $sortDirection): array
    {
        $aggregationResults = $this->aggregator->aggregate($criteria);

        $uniqueFlights = $this->deduplicator->deduplicate($aggregationResults['flights']);

        $collection = collect($uniqueFlights);


        if (isset($filters['max_stops'])) {
            $collection = $collection->where('stops', '<=', (int) $filters['max_stops']);
        }
        if (isset($filters['carrier'])) {
            $collection = $collection->where('carrier', strtoupper($filters['carrier']));
        }

        // Sort processing
        $sortedCollection = $this->sorter->sort($collection, $sortBy, $sortDirection);

        return [
            'results' => $sortedCollection,
            'metadata' => [
                'provider_completeness' => $aggregationResults['metadata'],
                'total_results' => $sortedCollection->count()
            ]
        ];

    }
}