<?php

use Illuminate\Support\Facades\Route;
use App\Listeners\PaddleWebhookListener;
use App\Http\Controllers\User\Auth\GoogleLoginController;

// Google Login
Route::get('/google-auth/redirect/web', [GoogleLoginController::class, 'redirect'])
    ->withoutMiddleware(['web'])
    ->middleware(['throttle:12,1']);
Route::get('/google-auth/callback/web', [GoogleLoginController::class, 'callback'])
    ->withoutMiddleware(['web'])
    ->middleware(['throttle:12,1']);

// Access
Route::post('/access/webhook', [PaddleWebhookListener::class, 'handleWebhook'])
    ->middleware('paddle_webhook_verified')
    ->name('access.webhook');
    
Route::view('/{any}', 'welcome')
    ->where('any', '.*')
    ->name('default');
