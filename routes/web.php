<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Terminal\CheckoutController;
use App\Http\Controllers\Terminal\PaymentsController;
use App\Http\Controllers\Terminal\SalesController;
use App\Http\Controllers\Terminal\TerminalController;
use App\Http\Controllers\Terminal\TerminalSelectController;
use Illuminate\Support\Facades\Route;

// Login / logout
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =============================================================================
// Authenticated routes — all users (cashiers + admins)
// =============================================================================

Route::middleware('auth')->group(function () {

    // ─── Terminal selection ───────────────────────────────────────────────────
    Route::get('/terminal', [TerminalSelectController::class, 'index'])->name('terminal.select');

    // ─── POS terminal — loads the Blade view ──────────────────────────────────
    Route::get('/terminal/{terminal}', [TerminalController::class, 'index'])->name('pos.index');

    // ─── Terminal API routes ──────────────────────────────────────────────────
    Route::prefix('terminal/{terminal}')->group(function () {
        Route::get('/session', [TerminalController::class, 'session']);
        Route::get('/customers/search', [TerminalController::class, 'searchCustomers']);
        Route::get('/pending-sales', [TerminalController::class, 'pendingSales']);

        Route::prefix('sales')->group(function () {
            Route::post('/', [SalesController::class, 'open']);
            Route::get('/{sale}', [SalesController::class, 'show']);
            Route::post('/{sale}/items', [SalesController::class, 'addItem']);
            Route::patch('/{sale}/items/{saleItem}', [SalesController::class, 'updateItem']);
            Route::delete('/{sale}/items/{saleItem}', [SalesController::class, 'removeItem']);
            Route::post('/{sale}/customer', [SalesController::class, 'attachCustomer']);
            Route::delete('/{sale}/customer', [SalesController::class, 'detachCustomer']);

            Route::post('/{sale}/payments/cash', [PaymentsController::class, 'cash']);
            Route::post('/{sale}/payments/card', [PaymentsController::class, 'card']);
            Route::post('/{sale}/payments/mobile-money', [PaymentsController::class, 'mobileMoney']);
            Route::post('/{sale}/payments/split', [PaymentsController::class, 'split']);
            Route::get('/{sale}/payments/outstanding', [PaymentsController::class, 'outstanding']);

            Route::post('/{sale}/discount/preview', [CheckoutController::class, 'previewDiscount']);
            Route::post('/{sale}/discount', [CheckoutController::class, 'applyDiscount']);
            Route::delete('/{sale}/discount', [CheckoutController::class, 'removeDiscount']);
            Route::post('/{sale}/complete', [CheckoutController::class, 'complete']);
            Route::post('/{sale}/void', [CheckoutController::class, 'void']);
            Route::get('/{sale}/receipt', [CheckoutController::class, 'receipt']);
            Route::post('/{sale}/receipt/print', [CheckoutController::class, 'printReceipt']);
        });
    });

    // ─── Admin routes — gated by EnsureUserIsAdmin middleware ─────────────────
    Route::middleware('admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
        Route::get('/admin/product', [AdminController::class, 'product'])->name('admin.product');
        Route::get('/admin/sales', [AdminController::class, 'sales'])->name('admin.sales');
        Route::get('/admin/reports', [AdminController::class, 'reports'])->name('admin.reports');
        Route::get('/admin/crm', [AdminController::class, 'crm'])->name('admin.crm');
    });
});
