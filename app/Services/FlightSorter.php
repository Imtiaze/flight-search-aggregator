<?php 

namespace App\Services;

use Illuminate\Support\Collection;

class FlightSorter
{
    public function sort(Collection $flights, string $sortBy, string $direction = 'asc'): Collection
    {
        $descending = ($direction === 'desc');

        return match ($sortBy) {
            'duration' => $flights->sortBy(function ($flight) {
                return $flight->departureTime->diffInMinutes($flight->arrivalTime);
            }, SORT_REGULAR, $descending),
            
            default => $flights->sortBy('fareUsd', SORT_REGULAR, $descending),
        };
    }
}