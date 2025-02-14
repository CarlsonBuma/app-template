<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\AppController;

//* App attributes
Route::get('/get-app-newsfeed', [AppController::class, 'loadNewsfeedIndex'])
    ->name('get.app.newsfeed');
Route::get('/get-app-languages', [AppController::class, 'loadPublicLanguages'])
    ->name('get.app.language');
Route::get('/get-app-countries', [AppController::class, 'loadPublicCountries'])
    ->name('get.app.countries');
    
//* Import Routes
require __DIR__.'/api/auth.php';
require __DIR__.'/api/access.php';
require __DIR__.'/api/user.php';
require __DIR__.'/api/cockpit.php';
require __DIR__.'/api/admin.php';
    
