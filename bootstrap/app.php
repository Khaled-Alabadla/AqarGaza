<?php

use App\Http\Middleware\ConditionalPasswordConfirm;
use App\Http\Middleware\CorsDeleteMiddleware;
use App\Http\Middleware\CorsMiddleware;
use App\Http\Middleware\EnsureEmailIsVerifiedApi;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleCORS;
use Flasher\Laravel\Middleware\SessionMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [
            __DIR__ . '/../routes/dashboard.php',
            __DIR__ . '/../routes/web.php',
            __DIR__ . '/../routes/auth.php',
        ],
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);
        $middleware->web(append: [
            HandleAppearance::class,
            SessionMiddleware::class,
            AddLinkHeadersForPreloadedAssets::class,
            CorsDeleteMiddleware::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            // '/messages',
            // '/messages/*',
            // 'messages/{message}'
        ]);
        $middleware->alias([
            'verified.api' => EnsureEmailIsVerifiedApi::class,
            'abilities' => CheckAbilities::class,
            'ability' => CheckForAnyAbility::class,
            'admin' => EnsureUserIsAdmin::class,
            'cors' => CorsDeleteMiddleware::class,
            'confirm_password' => ConditionalPasswordConfirm::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
