<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\ItemTypeController;
use App\Http\Controllers\ItemVarietyController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\POController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'cities',], function () {
    Route::GET('/detail', [CityController::class, 'getById']);
    Route::GET('', [CityController::class, 'getAll']);
    Route::POST('', [CityController::class, 'store']);
    Route::PATCH('', [CityController::class, 'update']);
    Route::PATCH('/restore', [CityController::class, 'restore']);
    Route::DELETE('{city}', [CityController::class, 'destroy']);
});

Route::group(['prefix' => 'countries',], function () {
    Route::GET('/detail', [CountryController::class, 'getById']);
    Route::GET('', [CountryController::class, 'getAll']);
    Route::POST('', [CountryController::class, 'store']);
    Route::PATCH('', [CountryController::class, 'update']);
    Route::PATCH('/restore', [CountryController::class, 'restore']);
    Route::DELETE('{country}', [CountryController::class, 'destroy']);
});

Route::group(['prefix' => 'item_types',], function () {
    Route::GET('/detail', [ItemTypeController::class, 'getById']);
    Route::GET('', [ItemTypeController::class, 'getAll']);
    Route::POST('', [ItemTypeController::class, 'store']);
    Route::PATCH('', [ItemTypeController::class, 'update']);
    Route::PATCH('/restore', [ItemTypeController::class, 'restore']);
    Route::DELETE('{item_type}', [ItemTypeController::class, 'destroy']);
});

Route::group(['prefix' => 'item_varieties',], function () {
    Route::GET('/detail', [ItemVarietyController::class, 'getById']);
    Route::GET('', [ItemVarietyController::class, 'getAll']);
    Route::POST('', [ItemVarietyController::class, 'store']);
    Route::PATCH('', [ItemVarietyController::class, 'update']);
    Route::PATCH('/restore', [ItemVarietyController::class, 'restore']);
    Route::DELETE('{item_variety}', [ItemVarietyController::class, 'destroy']);
});

Route::group(['prefix' => 'plans',], function () {
    Route::GET('/detail', [PlanController::class, 'getById']);
    Route::GET('', [PlanController::class, 'getAll']);
    Route::POST('', [PlanController::class, 'store']);
    Route::PATCH('', [PlanController::class, 'update']);
    Route::PATCH('/restore', [PlanController::class, 'restore']);
    Route::DELETE('{plan}', [PlanController::class, 'destroy']);
});

Route::group(['prefix' => 'po',], function () {
    Route::GET('/detail', [POController::class, 'getById']);
    Route::GET('', [POController::class, 'getAll']);
    Route::POST('', [POController::class, 'store']);
    Route::PATCH('', [POController::class, 'update']);
    Route::PATCH('/restore', [POController::class, 'restore']);
    Route::DELETE('{po}', [POController::class, 'destroy']);
});
