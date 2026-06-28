<?php

use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->controller(BookingController::class)->group(function()  {

    Route::prefix('bookings')->group(function () {
        Route::post('/', 'store');
        Route::get('/{reference}', 'show');
    });

});

