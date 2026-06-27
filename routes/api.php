<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function()  {

    Route::get('/hello', function () {
        return ['message' => 'Hello API'];
    });

});

