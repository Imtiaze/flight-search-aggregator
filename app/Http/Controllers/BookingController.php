<?php

namespace App\Http\Controllers;

use App\Actions\CreateBookingAction;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;

class BookingController extends Controller
{
    public function store(StoreBookingRequest $request, CreateBookingAction $action)
    {
        $booking = $action->execute(
            $request->input('flight_id'),
            $request->input('passengers')
        );

        return (new BookingResource($booking))
            ->additional(['message' => 'Booking confirmed successfully.'])
            ->response()
            ->setStatusCode(201);
    }

    public function show(string $reference)
    {
        $booking = Booking::where('reference', $reference)->firstOrFail();
        
        return new BookingResource($booking);
    }
}
