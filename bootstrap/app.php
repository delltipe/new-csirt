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
    ->withMiddleware(function (Middleware $middleware): void {
        // Trust all proxies (needed for load-balanced cloud environments like Railway)
        $middleware->trustProxies(at: '*');

        // Register the 'admin' middleware alias used by routes/web.php ['auth', 'admin'] groups.
        // Must be declared here (Laravel 12) — the legacy app/Http/Kernel.php routeMiddleware is not loaded.
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'bug_hunter' => \App\Http\Middleware\BugHunterMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
