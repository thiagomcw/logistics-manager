<?php

use App\Http\Controllers\Api\StorageLocationController;
use Illuminate\Support\Facades\Route;

// Routes
Route::resource('storage-locations', StorageLocationController::class, ['only' => ['index']]);
