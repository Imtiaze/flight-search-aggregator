<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchFlightRequest;
use App\DataTransferObj\SearchCriteria;
use App\Http\Resources\FlightResource;
use App\Actions\SearchFlightsAction;

class FlightSearchController extends Controller
{
    public function __invoke(SearchFlightRequest $request, SearchFlightsAction $action)
    {
        $criteria = SearchCriteria::fromRequest($request);

        $output = $action->execute(
            $criteria,
            $request->only(['max_stops', 'carrier']),
            $request->input('sort_by', 'price'),
            $request->input('sort_direction', 'asc')
        );
        
        return response()->json([
            'metadata' => $output['metadata'],
            'data' => FlightResource::collection($output['results'])
        ]);
    }
}
