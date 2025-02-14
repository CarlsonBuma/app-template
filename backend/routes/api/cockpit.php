<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cockpit\CockpitProfileController;


Route::middleware(['auth:api', 'email_verified', 'access_cockpit'])->group(function () {

    //* Manage cockpit profile
    Route::get('/load-cockpit-profile', [CockpitProfileController::class, 'loadProfile'])
        ->name('load.cockpit.profile');
    Route::post('/update-cockpit-public-access', [CockpitProfileController::class, 'updatePublicity'])
        ->name('update.cockpit.public.access');
    Route::post('/update-cockpit-avatar', [CockpitProfileController::class, 'updateAvatar'])
        ->name('update.cockpit.avatar');
    Route::post('/update-cockpit-credits', [CockpitProfileController::class, 'updateName'])
        ->name('update.cockpit.credits');
    Route::post('/update-cockpit-impressum', [CockpitProfileController::class, 'updateImpressum'])
        ->name('update.cockpit.impressum');
    Route::post('/update-cockpit-about', [CockpitProfileController::class, 'updateAbout'])
        ->name('update.cockpit.about');
    Route::post('/update-cockpit-bulletpoints', [CockpitProfileController::class, 'updateTags'])
        ->name('update.cockpit.bulletpoints');
    Route::post('/update-cockpit-location', [CockpitProfileController::class, 'updateLocation'])
        ->name('update.cockpit.location');
});
