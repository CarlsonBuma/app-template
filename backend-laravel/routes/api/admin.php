<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminNewsfeedController;
use App\Http\Controllers\Admin\AdminBackpanelController;

Route::middleware(['auth:api', 'email_verified', 'access_admin'])->group(function () {
    
    //* Dashboard
    Route::get('/admin-backpanel', [AdminBackpanelController::class, 'loadDashboard'])
        ->name('admin.backpanel');

    //* Newsfeed Management
    Route::get('/admin-get-newsfeed/all', [AdminNewsfeedController::class, 'loadEntries']);
    Route::post('/admin-create-newsfeed', [AdminNewsfeedController::class, 'create']);
    Route::post('/admin-update-newsfeed', [AdminNewsfeedController::class, 'update']);
    Route::delete('/admin-delete-newsfeed/{id}', [AdminNewsfeedController::class, 'delete']);
});
