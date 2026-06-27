<?php

use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function()  {

    Route::post('bookings', [BookingController::class, 'store']);

});

