<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\Auth\UserAuthController;
use App\Http\Controllers\User\Auth\UserAccountController;
use App\Http\Controllers\User\Auth\UserTransferController;
use App\Http\Controllers\User\Auth\CreateAccountController;
use App\Http\Controllers\User\Auth\PasswordResetController;
use App\Http\Controllers\User\Auth\EmailVerificationController;

Route::middleware(['auth:api', 'email_verified'])->group(function () {

    //* Auth
    Route::get('/auth', [UserAuthController::class, 'authUser'])
        ->name('auth');
    Route::post('/logout', [UserAuthController::class, 'logoutUser'])
        ->name('logout');
    
    //* User Account
    Route::post('/update-user-avatar', [UserAccountController::class, 'changeAvatar'])
        ->name('update.user.avatar');
    Route::post('/update-user-name', [UserAccountController::class, 'changeName'])
        ->name('update.user.name');
    Route::post('/update-user-password', [UserAccountController::class, 'changePassword'])
        ->name('update.user.password'); 

    // Transfer Account Request 
    // Email will be updated, after Emailverification, email_verified_at = null
    Route::post('/transfer-user-account', [UserTransferController::class, 'initializeEmailTransfer'])
        ->middleware('paddle_no_active_subscriptions')
        ->name('transfer.user.account');

    // Delete User
    Route::post('/user-delete-account', [UserAccountController::class, 'deleteAccount'])
        ->middleware('paddle_no_active_subscriptions')
        ->name('user.delete.account');
});


//* Public account access
Route::post('/login', [UserAuthController::class, 'loginUser'])
    ->middleware(['throttle:6,1'])    
    ->name('login');

// Create Account
Route::post('/create-account', [CreateAccountController::class, 'register'])
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
