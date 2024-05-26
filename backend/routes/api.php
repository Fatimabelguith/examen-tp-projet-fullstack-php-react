<?php

use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Controllers\UserController; 
 
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/users/{id}', [UserController::class, 'getUser']);

Route::get('/posts', [PostController::class, 'getFeedPosts']);
Route::post('/posts', [PostController::class, 'createPost']);
Route::get('/{userId}/posts', [PostController::class, 'getUserPosts']);

/* UPDATE */
Route::patch('posts/{id}/like', [PostController::class, 'likePost']);

 /* READ */
 Route::get('/{id}', [UserController::class, 'getUser']);
 Route::get('/users/{id}/friends', [UserController::class, 'getUserFriends']);

 /* UPDATE */
 Route::patch('/users/{id}/{friendId}', [UserController::class, 'addRemoveFriend']);
// Route::middleware([AuthMiddleware::class])->group(function () {
//     /* READ */
    
// });