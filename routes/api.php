<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\TPMDataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::get('/tpmdata', [TPMDataController::class, 'index']);
Route::get('/tpmdata/{id}', [TPMDataController::class, 'show']);
Route::post('/tpmdata', [TPMDataController::class, 'store']);
Route::put('/tpmdata/{id}', [TPMDataController::class, 'update']);

Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);

