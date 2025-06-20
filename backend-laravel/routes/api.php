<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\AppController;

//* App attributes
Route::get('/get-app-newsfeed', [AppController::class, 'loadNewsfeed']);
Route::get('/get-app-languages', [AppController::class, 'loadLanguages']);
Route::get('/get-app-countries', [AppController::class, 'loadCountries']);
Route::get('/get-app-currencies', [AppController::class, 'loadCurrencies']);

// Testing
Route::get('/testing', function () {
    return response()->json([
        'message' => 'Hello you!'
    ], 200);
});
    
//* Import Routes
require __DIR__.'/api/auth.php';
require __DIR__.'/api/access.php';
require __DIR__.'/api/user.php';
require __DIR__.'/api/cockpit.php';
require __DIR__.'/api/admin.php';
