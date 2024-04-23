<?php

use App\Http\Controllers\CityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'cities',], function () {

    Route::GET('{city}', [CityController::class, 'getById']);
    Route::GET('', [CityController::class, 'getAll']);
    Route::POST('', [CityController::class, 'store']);
    Route::PATCH('{city}', [CityController::class, 'update']);
    Route::DELETE('{city}', [CityController::class, 'destroy']);
});
