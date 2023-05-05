<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

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

Route::post('register', [LoginController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::apiResource('product', ProductController::class);
});
