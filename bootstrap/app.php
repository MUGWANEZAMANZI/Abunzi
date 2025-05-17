<?php 

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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'locale' => \App\Http\Middleware\LocaleMiddleware::class, // Alias for specific routes
        ]);

        $middleware->web([
            // Add your global web middleware here if needed
            // \App\Http\Middleware\AnotherMiddleware::class,
        ]);

        // Removed the call to routeMiddleware as it is not a valid method
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();