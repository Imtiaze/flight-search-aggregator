<?php 

namespace App\Actions;

use App\DataTransferObj\SearchCriteria;
use App\Services\{
    FlightDeduplicator,
    FlightAggregator,
    FlightSorter
};

class SearchFlightsAction 
{
    public function __construct(
        private FlightAggregator $aggregator,
        private FlightDeduplicator $deduplicator,
        private FlightSorter $sorter
    ) {}

    public function execute(SearchCriteria $criteria, array $filters, string $sortBy, string $sortDirection): array
    {
        // Get the flights
        $aggregationResults = $this->aggregator->aggregate($criteria);

        // Check for duplications
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