<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;

Route::middleware(['auth:api', 'email_verified'])->group(function () {

    // User
    Route::get('user-load-user', [UserController::class, 'loadUser']);
});
