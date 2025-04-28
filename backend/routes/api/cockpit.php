<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cockpit\CockpitProfileController;


Route::middleware(['auth:api', 'email_verified', 'access_cockpit'])->group(function () {

    //* Manage cockpit profile
    Route::get('/cockpit-load-profile', [CockpitProfileController::class, 'loadProfile']);
    Route::post('/cockpit-update-publicity', [CockpitProfileController::class, 'updatePublicity']);
    Route::post('/cockpit-update-avatar', [CockpitProfileController::class, 'updateAvatar']);
    Route::post('/cockpit-update-credits', [CockpitProfileController::class, 'updateName']);
    Route::post('/cockpit-update-impressum', [CockpitProfileController::class, 'updateImpressum']);
    Route::post('/cockpit-update-about', [CockpitProfileController::class, 'updateAbout']);
    Route::post('/cockpit-update-bulletpoints', [CockpitProfileController::class, 'updateTags']);
});
