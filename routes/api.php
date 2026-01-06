<?php

use App\Http\Controllers\Api\V1\EatersController;
use App\Http\Controllers\Api\V1\SchoolController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Middleware\AuthApiToken;

Route::prefix('v1/auth')->group(function () {

        // ОТКРЫТЫЕ эндпоинты
        Route::get('/test', function () {
                return response()->json([
                    'status' => 'ok',
                    'message' => 'API works',
                ]);
        });
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/verify', [AuthController::class, 'verify']);

        // ЗАКРЫТЫЕ эндпоинты (только по токену)
        // auth:sanctum тут мешал, он перебрасывал на login, это настраивать было сложно
        // я заменил его на свой мидлвар AuthApiToken
        Route::middleware(/*'auth:sanctum'*/[ AuthApiToken::class])->group(function () {
                Route::get('/profile', [AuthController::class, 'profile']);
                Route::post('/logout', [AuthController::class, 'logout']);

        });
});
Route::prefix('v1/school')->group(function () {
    Route::middleware([ AuthApiToken::class])->group(function () {
        Route::get('/eater-groups', [SchoolController::class, 'eaterGroups']);
        Route::get('/info', [SchoolController::class, 'info']);
    });
});

Route::prefix('v1/eaters')->group(function () {
    Route::middleware([ AuthApiToken::class])->group(function () {
        Route::get('/list', [EatersController::class, 'list']);
        Route::post('/create', [EatersController::class, 'create']);
        Route::post('/connect', [EatersController::class, 'connect']);
    });
});
