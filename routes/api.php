<?php

use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    Route::post('/orders', [OrderController::class, 'store']);
});