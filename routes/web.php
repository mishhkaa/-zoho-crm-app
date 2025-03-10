<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ZohoAuthController;
use App\Http\Controllers\ZohoController;
use App\Http\Controllers\FrontendController;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/api/oauth/callback', [ZohoAuthController::class, 'handleCallback']);

Route::get('/dashboard', [FrontendController::class, 'showDashboard']);

