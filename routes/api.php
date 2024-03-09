<?php

use App\Http\Controllers\AccountCreateController;
use App\Http\Controllers\TransactionCreateController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('account')->group(function () {
    Route::post('/', AccountCreateController::class);
});

Route::prefix('transaction')->group(function () {
    Route::post('/', TransactionCreateController::class);
});
