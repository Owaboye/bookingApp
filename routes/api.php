<?php

use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UploadFileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['middleware' => 'api', 'prefix'=> 'auth'], function ($router) {
    Route::post('/register', [AuthController::class,'register']);
    Route::get('/unauthorize_access', [AuthController::class,'unauthorize_access'])->name('login');
    Route::post('/login', [AuthController::class,'login']);
    Route::get('/profile', [AuthController::class,'profile'])->middleware('auth:api');
    Route::post('/logout', [AuthController::class,'logout'])->middleware('auth:api');
});

Route::group(['middleware' => 'api', 'prefix'=> 'apartments'], function ($router) {
    Route::post('/create', [ApartmentController::class, 'store'])->middleware('auth:api');
    Route::get('/', [ApartmentController::class, 'index'])->middleware('auth:api');
    Route::get('/{id}', [ApartmentController::class, 'show'])->middleware('auth:api');
    Route::put('/update', [ApartmentController::class, 'update'])->middleware('auth:api');
    Route::delete('/delete/{id}', [ApartmentController::class, 'delete'])->middleware('auth:api');
    Route::get('/search/{params}', [ApartmentController::class, 'search'])->middleware('auth:api');
    Route::post('/uploads', [ApartmentController::class, 'upload'])->middleware('auth:api');

    // Route::post('/uploads', [UploadFileController::class,'store']);
});


