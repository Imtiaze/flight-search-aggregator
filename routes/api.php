<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    FlightSearchController,
    BookingController
};

Route::prefix('v1')->group(function()  {

    # Flight Search 
    Route::get('/flights/search', FlightSearchController::class);

    # Flight Booking
    Route::group([
        'prefix' => 'bookings',
        'controller' => BookingController::class
    ],function () {
        Route::post('/', 'store');
        Route::get('/{reference}', 'show');
    });

});