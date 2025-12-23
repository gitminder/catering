<?php

use App\Enums\ApiErrorCode;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
            $middleware->api([
                //\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
                //\App\Http\Middleware\AuthApiToken::class,
                'throttle:api',
                \Illuminate\Routing\Middleware\SubstituteBindings::class,
            ]);
            $middleware->append(\Illuminate\Foundation\Http\Middleware\TrimStrings::class);
    })
    ->withProviders([
        App\Providers\RateLimitServiceProvider::class,
    ])
    /*
    ->withExceptions(function (Exceptions $exceptions): void {
            // на все запросы к api всегда ответом идет только json
            $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
                    return $request->is('api/*');
            });
    })
    */
    ->withExceptions(function (Exceptions $exceptions): void {

            // 405 Method Not Allowed
            $exceptions->renderable(function (
                \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException $e,
                Request $request
            ) {
                    if ($request->is('api/*')) {
                            return response()->json([
                                'success' => false,
                                'error' => [
                                    'code' => ApiErrorCode::METHOD_NOT_ALLOWED,
                                    'message' => 'Method not allowed',
                                ],
                            ], 405);
                    }
            });

            // 404 Not Found
            $exceptions->renderable(function (
                \Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e,
                Request $request
            ) {
                    if ($request->is('api/*')) {
                            return response()->json([
                                'success' => false,
                                'error' => [
                                    'code' => ApiErrorCode::NOT_FOUND,
                                    'message' => 'Resource not found',
                                ],
                            ], 404);
                    }
            });

            // 401 Unauthorized
            $exceptions->renderable(function (
                \Illuminate\Auth\AuthenticationException $e,
                Request $request
            ) {
                    if ($request->is('api/*')) {
                            return response()->json([
                                'success' => false,
                                'error' => [
                                    'code' => ApiErrorCode::AUTH_UNAUTHENTICATED,
                                    'message' => 'Unauthenticated',
                                ],
                            ], 401);
                    }
            });

            // fallback — любые другие ошибки
            $exceptions->renderable(function (
                Throwable $e,
                Request $request
            ) {
                    if ($request->is('api/*')) {
                            return response()->json([
                                'success' => false,
                                'error' => [
                                    'code' => ApiErrorCode::SERVER_ERROR,
                                    'message' => app()->isProduction()
                                        ? 'Server error'
                                        : $e->getMessage(),
                                ],
                            ], 500);
                    }
            });
    })

    ->create();
