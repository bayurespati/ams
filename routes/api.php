<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'cities',], function () {
    Route::GET('{city}', [CityController::class, 'getById']);
    Route::GET('', [CityController::class, 'getAll']);
    Route::POST('', [CityController::class, 'store']);
    Route::PATCH('{city}', [CityController::class, 'update']);
    Route::DELETE('{city}', [CityController::class, 'destroy']);
});

Route::group(['prefix' => 'countries',], function () {
    Route::GET('{country}', [CountryController::class, 'getById']);
    Route::GET('', [CountryController::class, 'getAll']);
    Route::POST('', [CountryController::class, 'store']);
    Route::PATCH('{country}', [CountryController::class, 'update']);
    Route::DELETE('{country}', [CountryController::class, 'destroy']);
});
