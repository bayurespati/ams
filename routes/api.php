<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\ItemTypeController;
use App\Http\Controllers\ItemVarietyController;
use App\Http\Controllers\PlanController;
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

Route::group(['prefix' => 'item_types',], function () {
    Route::GET('{item_type}', [ItemTypeController::class, 'getById']);
    Route::GET('', [ItemTypeController::class, 'getAll']);
    Route::POST('', [ItemTypeController::class, 'store']);
    Route::PATCH('{item_type}', [ItemTypeController::class, 'update']);
    Route::DELETE('{item_type}', [ItemTypeController::class, 'destroy']);
});

Route::group(['prefix' => 'item_varieties',], function () {
    Route::GET('{item_variety}', [ItemVarietyController::class, 'getById']);
    Route::GET('', [ItemVarietyController::class, 'getAll']);
    Route::POST('', [ItemVarietyController::class, 'store']);
    Route::PATCH('{item_variety}', [ItemVarietyController::class, 'update']);
    Route::DELETE('{item_variety}', [ItemVarietyController::class, 'destroy']);
});

Route::group(['prefix' => 'plans',], function () {
    Route::GET('{plan}', [PlanController::class, 'getById']);
    Route::GET('', [PlanController::class, 'getAll']);
    Route::POST('', [PlanController::class, 'store']);
    Route::PATCH('{plan}', [PlanController::class, 'update']);
    Route::DELETE('{plan}', [PlanController::class, 'destroy']);
});
