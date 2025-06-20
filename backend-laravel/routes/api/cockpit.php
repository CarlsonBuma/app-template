<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cockpit\CockpitStorageController;
use App\Http\Controllers\Cockpit\CockpitProfileController;


Route::middleware(['auth:api', 'email_verified', 'access_cockpit'])->group(function () {

    //* Gallery
    Route::get('cockpit-storage-get-gallery-files', [CockpitStorageController::class, 'readGallery']);
    Route::post('cockpit-storage-upload-image', [CockpitStorageController::class, 'uploadFile'])
        ->middleware('access_storage');
    
    // File Management
    Route::middleware(['access_file'])->group(function () {
        Route::post('cockpit-storage-rename-file', [CockpitStorageController::class, 'renameFile']);
        Route::post('cockpit-storage-publish-file', [CockpitStorageController::class, 'publishFile']);
        Route::post('cockpit-storage-unpublish-file', [CockpitStorageController::class, 'unpublishFile']);
        Route::delete('cockpit-storage-delete-file', [CockpitStorageController::class, 'removeFile']);
    
        Route::post('cockpit-storage-link-file-to-event', [CockpitStorageController::class, 'linkFile'])
            ->middleware('access_event');
    });
    
    //* Manage cockpit profile
    Route::get('/cockpit-get-community-profile', [CockpitProfileController::class, 'loadCommunityProfile']);
    Route::get('/cockpit-load-profile', [CockpitProfileController::class, 'loadProfile']);
    Route::post('/cockpit-update-publicity', [CockpitProfileController::class, 'updatePublicity']);
    Route::post('/cockpit-update-avatar', [CockpitProfileController::class, 'updateAvatar']);
    Route::post('/cockpit-update-credits', [CockpitProfileController::class, 'updateName']);
    Route::post('/cockpit-update-impressum', [CockpitProfileController::class, 'updateImpressum']);
    Route::post('/cockpit-update-about', [CockpitProfileController::class, 'updateAbout']);
    Route::post('/cockpit-update-bulletpoints', [CockpitProfileController::class, 'updateTags']);
});
