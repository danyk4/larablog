<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'homePage']);

Route::get('/about', [PageController::class, 'aboutPage']);

Route::post('/register', [UserController::class, 'register']);
