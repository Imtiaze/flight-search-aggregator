<?php

namespace App\Http\Controllers;

use App\Actions\SearchFlightsAction;
use App\DataTransferObj\SearchCriteria;
use App\Http\Requests\SearchFlightRequest;
use App\Http\Resources\FlightResource;

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
