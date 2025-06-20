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

    // MIddleware
    // https://laravel.com/docs/12.x/middleware#global-middleware
    ->withMiddleware(function (Middleware $middleware): void {
        
        // WEB
        $middleware->web(append: [
            Laravel\Passport\Http\Middleware\CreateFreshApiToken::class, // Oauth - at last position within Middleware Stack!
        ]);

        // Aliases
        $middleware->alias([
            
            // Access
            'email_verified' => \App\Http\Middleware\EmailVerified::class,
            'access_admin' => \App\Http\Middleware\AccessAdmin::class,
            'access_cockpit' => \App\Http\Middleware\AccessCockpit::class,
            'access_storage' => \App\Http\Middleware\AccessStorage::class,
            'access_file' => \App\Http\Middleware\AccessFile::class,

            // Paddle Management
            'paddle_webhook_verified' => \App\Http\Middleware\PaddleWebhookVerification::class,
            'paddle_no_active_subscriptions' => \App\Http\Middleware\PaddleNoActiveSubscriptions::class,
        ]);
    })

    // Exceptions
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
