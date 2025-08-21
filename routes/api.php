<?php

use App\Http\Controllers\AssetRecapController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoInController;
use App\Http\Controllers\ItemDoInController;
use App\Http\Controllers\ItemTypeController;
use App\Http\Controllers\ItemVarietyController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\POController;
use App\Http\Controllers\WarehouseController;
use App\Models\Asset;
use App\Models\ItemDoIn;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Route;



Route::group(['prefix' => 'dashboard',], function () {
    Route::GET('/get-plans', [DashboardController::class, 'getPlans']);
});

Route::group(['prefix' => 'plans',], function () {
    Route::GET('/detail', [PlanController::class, 'getById']);
    Route::GET('/garbage', [PlanController::class, 'getGarbage']);
    Route::GET('', [PlanController::class, 'getAll']);
    Route::POST('', [PlanController::class, 'store']);
    Route::PATCH('', [PlanController::class, 'update']);
    Route::PATCH('/restore', [PlanController::class, 'restore']);
    Route::DELETE('', [PlanController::class, 'destroy']);
});

Route::group(['prefix' => 'po',], function () {
    Route::GET('/detail', [POController::class, 'getById']);
    Route::GET('/garbage', [POController::class, 'getGarbage']);
    Route::GET('', [POController::class, 'getAll']);
    Route::POST('', [POController::class, 'store']);
    Route::PATCH('', [POController::class, 'update']);
    Route::PATCH('/restore', [POController::class, 'restore']);
    Route::DELETE('', [POController::class, 'destroy']);
});

Route::group(['prefix' => 'do-in',], function () {
    Route::GET('/detail', [DoInController::class, 'getById']);
    Route::GET('/garbage', [DoInController::class, 'getGarbage']);
    Route::GET('', [DoInController::class, 'getAll']);
    Route::POST('', [DoInController::class, 'store']);
    Route::PATCH('', [DoInController::class, 'update']);
    Route::PATCH('/approve', [DoInController::class, 'approve']);
    Route::PATCH('/restore', [DoInController::class, 'restore']);
    Route::DELETE('', [DoInController::class, 'destroy']);
});

Route::group(['prefix' => 'item-do-in',], function () {
    Route::GET('/detail', [ItemDoInController::class, 'getById']);
    Route::get('', [ItemDoInController::class, 'getAll']);
    Route::POST('/upload', [ItemDoInController::class, 'uploadItem']);
    Route::POST('', [ItemDoInController::class, 'addItem']);
    Route::PATCH('', [ItemDoInController::class, 'update']);
    Route::PATCH('verification', [ItemDoInController::class, 'verification']);
    Route::DELETE('', [ItemDoInController::class, 'destroy']);
});

// Route list Master data
Route::group(['prefix' => 'cities',], function () {
    Route::GET('/detail', [CityController::class, 'getById']);
    Route::GET('/garbage', [CityController::class, 'getGarbage']);
    Route::GET('', [CityController::class, 'getAll']);
    Route::POST('', [CityController::class, 'store']);
    Route::PATCH('', [CityController::class, 'update']);
    Route::PATCH('/restore', [CityController::class, 'restore']);
    Route::DELETE('', [CityController::class, 'destroy']);
});

Route::group(['prefix' => 'companies',], function () {
    Route::GET('/detail', [CompanyController::class, 'getById']);
    Route::GET('/garbage', [CompanyController::class, 'getGarbage']);
    Route::GET('', [CompanyController::class, 'getAll']);
    Route::POST('', [CompanyController::class, 'store']);
    Route::PATCH('', [CompanyController::class, 'update']);
    Route::PATCH('/restore', [CompanyController::class, 'restore']);
    Route::DELETE('', [CompanyController::class, 'destroy']);
});

Route::group(['prefix' => 'countries',], function () {
    Route::GET('/detail', [CountryController::class, 'getById']);
    Route::GET('/garbage', [CountryController::class, 'getGarbage']);
    Route::GET('', [CountryController::class, 'getAll']);
    Route::POST('', [CountryController::class, 'store']);
    Route::PATCH('', [CountryController::class, 'update']);
    Route::PATCH('/restore', [CountryController::class, 'restore']);
    Route::DELETE('', [CountryController::class, 'destroy']);
});

Route::group(['prefix' => 'brands',], function () {
    Route::GET('/detail', [BrandController::class, 'getById']);
    Route::GET('/garbage', [BrandController::class, 'getGarbage']);
    Route::GET('', [BrandController::class, 'getAll']);
    Route::POST('', [BrandController::class, 'store']);
    Route::PATCH('', [BrandController::class, 'update']);
    Route::PATCH('/restore', [BrandController::class, 'restore']);
    Route::DELETE('', [BrandController::class, 'destroy']);
});

Route::group(['prefix' => 'item_types',], function () {
    Route::GET('/detail', [ItemTypeController::class, 'getById']);
    Route::GET('/garbage', [ItemTypeController::class, 'getGarbage']);
    Route::GET('', [ItemTypeController::class, 'getAll']);
    Route::POST('', [ItemTypeController::class, 'store']);
    Route::PATCH('', [ItemTypeController::class, 'update']);
    Route::PATCH('/restore', [ItemTypeController::class, 'restore']);
    Route::DELETE('', [ItemTypeController::class, 'destroy']);
});

Route::group(['prefix' => 'item_varieties',], function () {
    Route::GET('/detail', [ItemVarietyController::class, 'getById']);
    Route::GET('/garbage', [ItemVarietyController::class, 'getGarbage']);
    Route::GET('', [ItemVarietyController::class, 'getAll']);
    Route::POST('', [ItemVarietyController::class, 'store']);
    Route::PATCH('', [ItemVarietyController::class, 'update']);
    Route::PATCH('/restore', [ItemVarietyController::class, 'restore']);
    Route::DELETE('', [ItemVarietyController::class, 'destroy']);
});

Route::group(['prefix' => 'warehouses',], function () {
    Route::GET('/detail', [WarehouseController::class, 'getById']);
    Route::GET('/garbage', [WarehouseController::class, 'getGarbage']);
    Route::GET('', [WarehouseController::class, 'getAll']);
    Route::POST('', [WarehouseController::class, 'store']);
    Route::PATCH('', [WarehouseController::class, 'update']);
    Route::PATCH('/restore', [WarehouseController::class, 'restore']);
    Route::DELETE('', [WarehouseController::class, 'destroy']);
});

Route::group(['prefix' => 'asset-recap',], function () {
    Route::GET('/detail', [AssetRecapController::class, 'getById']);
    Route::GET('/garbage', [AssetRecapController::class, 'getGarbage']);
    Route::GET('', [AssetRecapController::class, 'getAll']);
    Route::POST('', [AssetRecapController::class, 'store']);
    Route::POST('/upload', [AssetRecapController::class, 'upload']);
    Route::PATCH('', [AssetRecapController::class, 'update']);
    Route::PATCH('/restore', [AssetRecapController::class, 'restore']);
    Route::DELETE('', [AssetRecapController::class, 'destroy']);
});
