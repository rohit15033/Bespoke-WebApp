<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\HandleInertiaRequests;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        api: __DIR__.'/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // $middleware->web(append: [
        //     HandleInertiaRequests::class,
        // ]);
        $middleware->append(\App\Http\Middleware\CorsMiddleware::class);

        $middleware->validateCsrfTokens(except: [
            'appointments',  // Disable CSRF for this route
            'api/*',         // Disable for all API routes (if needed)
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
