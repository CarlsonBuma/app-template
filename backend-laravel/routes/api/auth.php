<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\Auth\UserAuthController;
use App\Http\Controllers\User\Auth\UserDeleteController;
use App\Http\Controllers\User\Auth\UserAccountController;
use App\Http\Controllers\User\Auth\UserTransferController;
use App\Http\Controllers\User\Auth\CreateAccountController;
use App\Http\Controllers\User\Auth\PasswordResetController;
use App\Http\Controllers\User\Auth\EmailVerificationController;



// Auth
Route::post('/login', [UserAuthController::class, 'loginUser'])
    ->middleware(['throttle:9,1']);

Route::post('/authenticate/{email}/{token}', [UserAuthController::class, 'authenticateUser'])
    ->middleware(['throttle:6,1'])
    ->name('authenticate');

// Authenticated
Route::middleware(['auth:api', 'email_verified'])->group(function () {

    //* Auth
    Route::get('/user', [UserAuthController::class, 'getUser']);
    Route::post('/logout', [UserAuthController::class, 'logoutUser']);
    
    //* User Account
    Route::post('/user-update-name', [UserAccountController::class, 'changeName']);
    Route::post('/user-update-avatar', [UserAccountController::class, 'changeAvatar']);
    Route::post('/user-reset-password', [UserAccountController::class, 'changePassword']);
    Route::post('/user-invalidate-tokens', [UserAccountController::class, 'invalidateTokens']);
    Route::post('/user-invalidate-email', [UserAccountController::class, 'invalidateEmail']);

    // Transfer Account Request 
    // Email will be updated, after Emailverification
    Route::post('/user-transfer-account', [UserTransferController::class, 'initializeEmailTransfer'])
        ->middleware('paddle_no_active_subscriptions');

    // Delete User
    Route::post('/user-delete-account', [UserDeleteController::class, 'deleteAccount'])
        ->middleware('paddle_no_active_subscriptions');
});

// Create Account
Route::post('/create-account', [CreateAccountController::class, 'register'])
    ->middleware(['throttle:5,1'])    
    ->name('create.account');

// Verify Email
Route::post('/email-verification-request', [EmailVerificationController::class, 'sendToken'])
    ->middleware(['throttle:3,1'])
    ->name('email.verification.request');
Route::put('/email-verification/{email}/{token}', [EmailVerificationController::class, 'verifyToken'])
    ->middleware(['throttle:5,1'])
    ->name('email.verification');

// Reset Password
Route::post('/password-reset-request', [PasswordResetController::class, 'sendToken'])
    ->middleware(['throttle:3,1'])
    ->name('password.reset.request');
Route::put('/password-reset/{email}/{token}', [PasswordResetController::class, 'verifyToken'])
    ->middleware(['throttle:5,1'])
    ->name('password.reset');

// Transfer Account
Route::put('/transfer-account/{email}/{token}/{transfer}', [UserTransferController::class, 'verifyEmailTransfer'])
    ->middleware(['throttle:5,1'])
    ->name('transfer.account');
