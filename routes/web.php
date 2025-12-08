<?php

use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketAttachmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/pesan', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/pesan', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/pesan/{order:order_number}/pembayaran', [OrderController::class, 'payment'])->name('orders.payment');

    Route::get('/pesan/cek', [OrderController::class, 'checkForm'])->name('orders.check');
    Route::post('/pesan/cek', [OrderController::class, 'check'])->name('orders.check.perform');

    Route::get('/tiket', [TicketController::class, 'index'])->name('tickets.index');
    Route::post('/tiket', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tiket/{ticket:code}', [TicketController::class, 'show'])->name('tickets.show');
    Route::post('/tiket/{ticket:code}/balas', [TicketController::class, 'reply'])->name('tickets.reply');
    Route::get('/tiket/{ticket:code}/lampiran/{attachment}', TicketAttachmentController::class)->name('tickets.attachments.download');
});

Route::get('/layanan', [ServiceController::class, 'index'])->name('services.index');
Route::get('/layanan/{service:slug}', [ServiceController::class, 'show'])->name('services.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order:order_number}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order:order_number}', [AdminOrderController::class, 'update'])->name('orders.update');
    Route::patch('/services/{service}', [AdminServiceController::class, 'update'])->name('services.update');
    Route::get('/tickets', [AdminTicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{ticket:code}', [AdminTicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket:code}/reply', [AdminTicketController::class, 'reply'])->name('tickets.reply');
    Route::post('/tickets/{ticket:code}/close', [AdminTicketController::class, 'close'])->name('tickets.close');
});

require __DIR__.'/auth.php';
