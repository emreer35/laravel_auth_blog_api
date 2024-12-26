<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::get('/posts', [PostController::class, 'index']);
Route::get('/post/{post}', [PostController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    //create
    Route::post('/post/create', [PostController::class, 'store']);
    Route::post('/post/createValidate', [PostController::class, 'storeValidate']);
    //update
    Route::put('post/update/{post}', [PostController::class, 'update']);
    Route::put('post/updateValidate/{post}', [PostController::class, 'updateValidate']);
    //delete
    Route::delete('post/delete/{post}', [PostController::class, 'destroy']);
});
