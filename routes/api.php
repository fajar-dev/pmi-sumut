<?php

use App\Http\Controllers\Api\BmkgServiceController;
use App\Http\Controllers\Api\GeoCodeController;
use App\Http\Controllers\Api\SiamoServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/geocode', [GeoCodeController::class, 'geoCode']);

Route::prefix('/siamo-service')->group(function () {
    Route::get('/member-count', [SiamoServiceController::class, 'memberCount']);
});  

Route::prefix('/bmkg-service')->group(function () {
    Route::prefix('/weather')->group(function () {
        Route::get('/current', [BmkgServiceController::class, 'currentWeather']);
        Route::get('/forecast', [BmkgServiceController::class, 'forecastWeather']);
        Route::get('/early-warning', [BmkgServiceController::class, 'earlyWarning']);
    });  
    Route::prefix('/earthquake')->group(function () {
        Route::get('/lastest', [BmkgServiceController::class, 'latestEarthquake']);
    });  
});  