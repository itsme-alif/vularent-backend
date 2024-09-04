<?php

use App\Http\Controllers\ItemsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\TransactionsController;
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

Route::apiResource('transaction', TransactionsController::class);
Route::apiResource('item', ItemsController::class);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('order', OrdersController::class);
});
