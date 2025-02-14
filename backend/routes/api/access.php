<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\Access\UserAccessController;
use App\Http\Controllers\User\Access\AdminAccessController;

Route::middleware(['auth:api', 'email_verified'])->group(function () {

    //* User access
    Route::get('/load-user-access', [UserAccessController::class, 'loadUserAccess'])
        ->name('load.user.access');
    Route::get('/check-user-access/{access_token}', [UserAccessController::class, 'checkUserAccess'])
        ->name('check.user.access');
    
    // Process Paddle Client Checkout
    // Initialize client-access-request for subsequent user-access verification by webhooks
    // See "\Listeners\PaddleWebhookListener"
    Route::post('/initialize-user-checkout', [UserAccessController::class, 'initializeClientCheckout'])
        ->name('initialize.user.checkout');
    Route::post('/verify-user-checkout', [UserAccessController::class, 'verifyUserTransaction'])
        ->name('verify.user.checkout');  
    
    // Cancel Paddle subscription
    Route::post('/cancel-user-subscription', [UserAccessController::class, 'cancelSubscription'])
        ->middleware(['throttle:6,1']) 
        ->name('cancel.user.subscription');

    //* Admin Access Management
    Route::middleware(['access_admin'])->group(function () {
        Route::get('/get-app-prices', [AdminAccessController::class, 'loadPrices'])
            ->name('get.app.prices');
        Route::post('/update-app-price', [AdminAccessController::class, 'updatePrice'])
            ->name('update.app.price');
        Route::get('/get-app-user-access', [AdminAccessController::class, 'loadUserAccess'])
            ->name('get.app.user.access');
        Route::post('/update-app-user-access', [AdminAccessController::class, 'updateUserAccess'])
            ->name('update.app.user.access');
        Route::post('/create-app-user-access', [AdminAccessController::class, 'createUserAccess'])
            ->name('create.app.user.access');
    });        
});
