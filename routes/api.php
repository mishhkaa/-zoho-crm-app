<?php 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ZohoController;

Route::middleware('api')->group(function () {
    Route::post('/account', [ZohoController::class, 'createAccount']);
    Route::post('/deal', [ZohoController::class, 'createDeal']);
});
Route::put('/account/{id}', [ZohoController::class, 'updateAccount']);
Route::put('/deal/{id}', [ZohoController::class, 'updateDeal']);
Route::delete('/account/{id}', [ZohoController::class, 'deleteAccount']);
Route::delete('/deal/{id}', [ZohoController::class, 'deleteDeal']);
Route::get('/accounts', [ZohoController::class, 'getAccounts']);
Route::get('/deals', [ZohoController::class, 'getDeals']);

