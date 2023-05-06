<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

//public routes
Route::post('register', [LoginController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);
Route::get('/frontend/products', [ProductController::class, 'index']);
Route::get('/frontend/products', [ProductController::class, 'index']);
// authenticated routes
Route::middleware('auth:api')->group(function () {
    Route::get('/user', function(Request $request){
        return $request->user();
    });
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::apiResource('products', ProductController::class);

    Route::get('/cart', [CartController::class, 'index']);
});
