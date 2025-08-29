<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Alias our simple session-based auth middleware
        $middleware->alias([
            'auth.simple' => \App\Http\Middleware\EnsureAuthenticated::class,
        ]);
        
        // (Optional) Add to web group automatically
        // $middleware->appendToGroup('web', [
        //     // e.g. \App\Http\Middleware\SomeGlobalWebMiddleware::class,
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
