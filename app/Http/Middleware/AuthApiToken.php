<?php

namespace App\Http\Middleware;

use App\Enums\ApiErrorCode;
use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class AuthApiToken
{
        public function handle(Request $request, Closure $next)
        {
                $token = $request->bearerToken(); // получаем токен из Authorization header

                if (! $token) {
                        return response()->json([
                            'success' => false,
                            'error' => [
                                'code' => ApiErrorCode::AUTH_UNAUTHENTICATED,
                                'message' => 'No token provided'
                            ]
                        ], 401);
                }

                $accessToken = PersonalAccessToken::findToken($token);
                //echo $accessToken;
                //die;
                if (! $accessToken || ! $accessToken->tokenable) {
                        return response()->json([
                            'success' => false,
                            'error' => [
                                'code' => ApiErrorCode::AUTH_UNAUTHENTICATED,
                                'message' => 'Invalid token'
                            ]
                        ], 401);
                }

                // Привязываем пользователя к request
                //$request->setUserResolver(fn() => $accessToken->tokenable);
                // подставляем пользователя и токен
                $user = $accessToken->tokenable;
                $user->withAccessToken($accessToken); // вот это важно

                $request->setUserResolver(fn() => $user);

                //return $next($request);
                return $next($request);
        }
}
