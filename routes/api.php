<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;

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
        Route::middleware(/*'auth:sanctum'*/[ \App\Http\Middleware\AuthApiToken::class])->group(function () {
                Route::get('/profile', [AuthController::class, 'profile']);
                Route::post('/logout', [AuthController::class, 'logout']);

                // другие защищённые эндпоинты v1
        });

});
