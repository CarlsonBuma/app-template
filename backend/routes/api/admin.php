<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BackpanelController;
use App\Http\Controllers\Admin\AppNewsfeedController;

Route::middleware(['auth:api', 'email_verified', 'access_admin'])->group(function () {
    
    //* Dashboard
    Route::get('/admin-backpanel', [BackpanelController::class, 'loadDashboard'])
        ->name('admin.backpanel');

    //* Newsfeed Management
    Route::get('/get-app-newsfeed/all', [AppNewsfeedController::class, 'loadEntries'])
        ->name('get.app.newsfeed.all');
    Route::post('/create-app-newsfeed', [AppNewsfeedController::class, 'create'])
        ->name('create.app.newsfeed');
    Route::post('/update-app-newsfeed', [AppNewsfeedController::class, 'update'])
        ->name('update.app.newsfeed');
    Route::delete('/delete-app-newsfeed/{id}', [AppNewsfeedController::class, 'delete'])
        ->name('delete.app.newsfeed');
});
