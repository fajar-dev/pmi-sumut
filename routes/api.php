<?php

use App\Http\Controllers\Api\BmkgServiceController;
use App\Http\Controllers\Api\SiamoServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('/siamo-service')->group(function () {
    Route::get('/member-count', [SiamoServiceController::class, 'memberCount']);
});  

Route::prefix('/bmkg-service')->group(function () {
    Route::get('/lastest-earthquake', [BmkgServiceController::class, 'latestEarthquake']);
});  