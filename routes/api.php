<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\API\MultipleUploadController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Public routes

Route::post('video-upload', [MultipleUploadController::class, 'addVideo']);

Route::post('multiple-image-upload', [MultipleUploadController::class, 'store']);


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/get-user-posts', [PostController::class, 'index']); // create post
Route::get('/get-user-all-posts', [PostController::class, 'getAllUserPost']); // create post
Route::get('/get-images', [MultipleUploadController::class, 'index']); // create post


// Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function () {

    // User
    Route::get('/get-all-users', [AuthController::class, 'getAllUsers']);
    Route::get('/user-id/{id}', [AuthController::class, 'userID']);
    Route::post('/update-specific-user/{id}', [AuthController::class, 'updateSpecificUser']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/user', [AuthController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);


    Route::post('/posts', [PostController::class, 'store']); // create post
    Route::put('/posts/{id}', [PostController::class, 'update']); // update post
});
