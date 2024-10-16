<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

// Admins
Route::get('/admins-only', function () {
    return 'Only for admins';
})->middleware('can:vizitAdminPages');

// User routes
Route::get('/', [UserController::class, 'correctHomePage'])->name('login');
Route::post('/register', [UserController::class, 'register'])->middleware('guest');
Route::post('/login', [UserController::class, 'login'])->middleware('guest');
Route::post('/logout', [UserController::class, 'logout'])->middleware('mustBeLoggedIn');

// Post routes
Route::get('/create-post', [PostController::class, 'createPost'])->middleware('mustBeLoggedIn');
Route::post('/create-post', [PostController::class, 'storePost'])->middleware('mustBeLoggedIn');
Route::get('/post/{post}', [PostController::class, 'show'])->name('post.show');
Route::delete('/post/{post}', [PostController::class, 'destroy'])->middleware('can:delete,post')->name('post.destroy');
Route::get('/post/{post}/edit', [PostController::class, 'edit'])->middleware('can:update,post')->name('post.edit');
Route::put('/post/{post}', [PostController::class, 'update'])->middleware('can:update,post')->name('post.update');

// Profile routes
Route::get('/profile/{user}', [UserController::class, 'profile'])->middleware('mustBeLoggedIn');
