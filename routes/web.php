<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// oauth2-google
Route::get('/index', [\App\Http\Controllers\GoogleController::class,'index']);
Route::get('/oauth/gmail/callback', [\App\Http\Controllers\GoogleController::class,'index']);
Route::get('/refresh-token', [\App\Http\Controllers\GoogleController::class,'refreshToken']);
Route::get('/send/mail', [\App\Http\Controllers\GoogleController::class,'sendMail']);
//Provider
//Route::get('/provider', [\App\Http\Controllers\ProviderController::class,'index']);
//Route::get('/oauth/gmail/callback', [\App\Http\Controllers\ProviderController::class,'index']);
