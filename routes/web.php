<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\PageController::class, 'homePage']);

Route::get('/about', [\App\Http\Controllers\PageController::class, 'aboutPage']);
