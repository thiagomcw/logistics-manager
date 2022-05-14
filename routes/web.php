<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', fn() => view('home'))
    ->where('any', '.*');
