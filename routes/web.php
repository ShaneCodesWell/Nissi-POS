<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\Terminal\TerminalController;
use App\Http\Controllers\Terminal\PaymentController;
use App\Http\Controllers\Terminal\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Demo route for testing
// POS terminal
Route::get('/terminal', [TerminalController::class, 'index'])->name('pos.index');

// Admin Routes
Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

// Demo Routes for admin sections
Route::get('/admin/product', [AdminController::class, 'product'])->name('admin.product');
Route::get('/admin/sales', [AdminController::class, 'sales'])->name('admin.sales');
Route::get('/admin/reports', [AdminController::class, 'reports'])->name('admin.reports');
Route::get('/admin/crm', [AdminController::class, 'crm'])->name('admin.crm');

// Terminal Routes
Route::prefix('terminal/{terminal}')->group(function () {
    Route::get('/', [TerminalController::class, 'session']);
    Route::get('customers/search', [TerminalController::class, 'searchCustomers']);
    Route::get('pending-sales', [TerminalController::class, 'pendingSales']);

    Route::prefix('sales')->group(function () {
        Route::post('/',                           [SalesController::class, 'open']);
        Route::get('/{sale}',                      [SalesController::class, 'show']);
        Route::post('/{sale}/items',               [SalesController::class, 'addItem']);
        Route::patch('/{sale}/items/{saleItem}',   [SalesController::class, 'updateItem']);
        Route::delete('/{sale}/items/{saleItem}',  [SalesController::class, 'removeItem']);
        Route::post('/{sale}/customer',            [SalesController::class, 'attachCustomer']);
        Route::delete('/{sale}/customer',          [SalesController::class, 'detachCustomer']);

        Route::post('/{sale}/payments/cash',        [PaymentController::class, 'cash']);
        Route::post('/{sale}/payments/card',        [PaymentController::class, 'card']);
        Route::post('/{sale}/payments/mobile-money',[PaymentController::class, 'mobileMoney']);
        Route::post('/{sale}/payments/split',       [PaymentController::class, 'split']);
        Route::get('/{sale}/payments/outstanding',  [PaymentController::class, 'outstanding']);

        Route::post('/{sale}/discount/preview', [CheckoutController::class, 'previewDiscount']);
        Route::post('/{sale}/discount',         [CheckoutController::class, 'applyDiscount']);
        Route::delete('/{sale}/discount',       [CheckoutController::class, 'removeDiscount']);
        Route::post('/{sale}/complete',         [CheckoutController::class, 'complete']);
        Route::post('/{sale}/void',             [CheckoutController::class, 'void']);
        Route::get('/{sale}/receipt',           [CheckoutController::class, 'receipt']);
        Route::post('/{sale}/receipt/print',    [CheckoutController::class, 'printReceipt']);
    });
});
