<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register',[\App\Http\Controllers\Api\AuthController::class,'Register']);
Route::post('login',[\App\Http\Controllers\Api\AuthController::class,'Login']);

Route::get('categories',[\App\Http\Controllers\Api\CategoryController::class,'index']);
Route::get('categories/{id}',[\App\Http\Controllers\Api\CategoryController::class,'show']);
Route::post('categories',[\App\Http\Controllers\Api\CategoryController::class,'store']);
Route::put('categories/{id}',[\App\Http\Controllers\Api\CategoryController::class,'update']);
Route::delete('categories/{id}',[\App\Http\Controllers\Api\CategoryController::class,'destroy']);

Route::get('products',[\App\Http\Controllers\Api\ProductController::class,'index']);
Route::get('products/{id}',[\App\Http\Controllers\Api\ProductController::class,'show']);
Route::post('products',[\App\Http\Controllers\Api\ProductController::class,'store']);
Route::put('products/{id}',[\App\Http\Controllers\Api\ProductController::class,'update']);

