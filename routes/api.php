<?php

use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\StorageLocationController;
use Illuminate\Support\Facades\Route;

// Routes
Route::resource('storage-locations', StorageLocationController::class, ['only' => ['index']]);

Route::get('packages/next-delivery-dates', [PackageController::class, 'nextDeliveryDates'])
    ->name('packages.next-delivery-dates');

Route::resource('packages', PackageController::class, ['only' => ['index', 'store', 'show', 'update']]);
