<?php  

namespace App\DataTransferObj;

use App\Http\Requests\SearchFlightRequest;
use Carbon\Carbon;

readonly class SearchCriteria
{
    public function __construct(
        public string $from,
        public string $to,
        public Carbon $date,
        public int $passengers
    ) {}

    public static function fromRequest(SearchFlightRequest $request): self
    {
        return new self(
            from: strtoupper($request->input('from')),
            to: strtoupper($request->input('to')),
            date: Carbon::parse($request->input('date')),
            passengers: (int) $request->input('passengers', 1)
        );
    }
}