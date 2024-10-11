<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'correctHomePage']);
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);

Route::get('/create-post', [PostController::class, 'createPost']);
Route::post('/create-post', [PostController::class, 'storePost']);
Route::get('/post/{post}', [PostController::class, 'show']);
