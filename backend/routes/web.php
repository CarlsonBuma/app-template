<?php

use App\Listeners\PaddleWebhookListener;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::post('/access/webhook', [PaddleWebhookListener::class, 'handleWebhook'])
    ->middleware('paddle_webhook_verified')
    ->name('access.webhook');
    
Route::view('/{any}', 'welcome')
    ->where('any', '.*')
    ->name('default');
