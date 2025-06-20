<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserAccessController;
use App\Http\Controllers\Admin\AdminAccessController;

Route::middleware(['auth:api', 'email_verified'])->group(function () {

    // Load Price Tokens
    Route::get('/user-load-pricing-plans', [UserAccessController::class, 'loadUserPricingPlans']);

    //* User access
    Route::get('/user-load-transactions', [UserAccessController::class, 'loadUserTransactions']);
    Route::get('/user-check-access/{access_token}', [UserAccessController::class, 'checkUserAccess']);
    Route::put('/user-free-community-access', [UserAccessController::class, 'createFreeCommunityAccess']);
    
    // Process Paddle Client Checkout
    Route::post('/user-initialize-checkout', [UserAccessController::class, 'initializeClientCheckout']);
    Route::post('/user-verify-checkout', [UserAccessController::class, 'verifyUserTransaction']);
    
    // Cancel Paddle subscription
    Route::post('/user-cancel-subscription', [UserAccessController::class, 'cancelSubscription'])
        ->middleware(['throttle:6,1']);

    //* Admin Access Management
    Route::middleware(['access_admin'])->group(function () {

        // Prices
        Route::get('/admin-load-prices', [AdminAccessController::class, 'loadPrices']);
        Route::post('/admin-update-app-price', [AdminAccessController::class, 'updatePrice']);

        // Access Management
        Route::get('/admin-get-user-access', [AdminAccessController::class, 'loadUserAccess']);
        Route::post('/admin-create-user-access', [AdminAccessController::class, 'createUserAccess']);
        Route::post('/admin-update-user-access', [AdminAccessController::class, 'updateUserAccess']);
        Route::post('/admin-cancel-user-transaction', [AdminAccessController::class, 'cancelTransaction']);
    });        
});
