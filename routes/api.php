<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\JwtController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;

// use Illuminate\Support\Facades\Hash;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// public routes
Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

// private routes
Route::controller(AuthController::class)->middleware('api')->group(function () {
    Route::post('/logout', 'logout');
});

Route::controller(JwtController::class)
    ->middleware('api')->prefix('auth')
    ->group(function ($router) {
        Route::post('login', 'login');
        Route::post('logout', 'logout');
        Route::post('refresh', 'refresh');
        Route::get('me', 'me');
        Route::post('register', 'register');
    });

