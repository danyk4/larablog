<?php

use App\Http\Controllers\FollowController;
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
Route::get('/manage-avatar', [UserController::class, 'manageAvatar'])->middleware('mustBeLoggedIn');
Route::post('/manage-avatar', [UserController::class, 'storeAvatar'])->middleware('mustBeLoggedIn');

// Follow routes
Route::post('/create-follow/{user:username}', [FollowController::class, 'createFollow'])->middleware('mustBeLoggedIn');
Route::post('/remove-follow/{user:username}', [FollowController::class, 'removeFollow'])->middleware('mustBeLoggedIn');

// Post routes
Route::get('/create-post', [PostController::class, 'createPost'])->middleware('mustBeLoggedIn');
Route::post('/create-post', [PostController::class, 'storePost'])->middleware('mustBeLoggedIn');
Route::get('/post/{post}', [PostController::class, 'show'])->name('post.show');
Route::delete('/post/{post}', [PostController::class, 'destroy'])->middleware('can:delete,post')->name('post.destroy');
Route::get('/post/{post}/edit', [PostController::class, 'edit'])->middleware('can:update,post')->name('post.edit');
Route::put('/post/{post}', [PostController::class, 'update'])->middleware('can:update,post')->name('post.update');
Route::get('/search/{term}', [PostController::class, 'search'])->name('post.search');

// Profile routes
Route::get('/profile/{user}', [UserController::class, 'profile'])->middleware('mustBeLoggedIn');
Route::get('/profile/{user}/followers', [UserController::class, 'profileFollowers'])->middleware('mustBeLoggedIn');
Route::get('/profile/{user}/following', [UserController::class, 'profileFollowing'])->middleware('mustBeLoggedIn');
