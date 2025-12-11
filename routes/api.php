<?php

use App\Http\Controllers\Api\MidtransWebhookController;
use App\Http\Controllers\Api\OrderController as ApiOrderController;
use App\Http\Controllers\Api\ServiceController as ApiServiceController;
use App\Http\Controllers\Api\TicketController as ApiTicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/services', [ApiServiceController::class, 'index']);
Route::get('/services/{service:slug}', [ApiServiceController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user()->load(['orders', 'tickets']);
    });

    Route::post('/orders', [ApiOrderController::class, 'store']);
    Route::get('/orders/{order:order_number}', [ApiOrderController::class, 'show']);
    Route::patch('/orders/{order:order_number}/status', [ApiOrderController::class, 'updateStatus']);

    Route::get('/tickets', [ApiTicketController::class, 'index']);
    Route::post('/tickets', [ApiTicketController::class, 'store']);
    Route::post('/tickets/{ticket:code}/messages', [ApiTicketController::class, 'storeMessage']);
});

Route::post('/midtrans/notification', MidtransWebhookController::class)->name('api.midtrans.webhook');
