<?php

use App\Http\Controllers\Customers\CustomerController;
use App\Http\Controllers\Customers\LoyaltyController;
use App\Http\Controllers\Customers\VoucherController;
use App\Http\Controllers\Discounts\DiscountCodeController;
use App\Http\Controllers\Discounts\DiscountController;
use App\Http\Controllers\Inventory\InventoryController;
use App\Http\Controllers\Products\CategoryController;
use App\Http\Controllers\Products\ProductController;
use App\Http\Controllers\Reports\ReportController;
use App\Http\Controllers\Sales\RefundController;
use App\Http\Controllers\Sales\SaleHistoryController;
use App\Http\Controllers\Settings\LocationController;
use App\Http\Controllers\Settings\LoyaltyTiersController;
use App\Http\Controllers\Settings\TerminalSettingsController;
use App\Http\Controllers\Terminal\CheckoutController;
use App\Http\Controllers\Terminal\PaymentsController;
use App\Http\Controllers\Terminal\SalesController;
use App\Http\Controllers\Terminal\TerminalController;
use Illuminate\Support\Facades\Route;

// =============================================================================
// Public routes — no authentication required
// =============================================================================

// Health check — useful for uptime monitoring and load balancer checks
Route::get('/health', fn() => response()->json([
    'status'  => 'ok',
    'version' => config('app.version', '1.0.0'),
    'time'    => now()->toIso8601String(),
]));

// =============================================================================
// Authenticated routes
// All routes below require a valid auth token.
// Replace 'auth:sanctum' with your preferred auth middleware.
// =============================================================================

Route::middleware('auth:sanctum')->group(function () {

    // =========================================================================
    // Terminal — POS checkout
    // Scoped to a specific terminal device.
    // =========================================================================

    Route::prefix('terminal/{terminal}')->name('terminal.')->group(function () {

        // Session boot & customer lookup
        Route::get('/', [TerminalController::class, 'session'])->name('session');
        Route::get('/customers/search', [TerminalController::class, 'searchCustomers'])->name('customers.search');
        Route::get('/pending-sales', [TerminalController::class, 'pendingSales'])->name('pending-sales');

        // Sale lifecycle
        Route::prefix('sales')->name('sales.')->group(function () {

            Route::post('/', [SalesController::class, 'open'])->name('open');
            Route::get('/{sale}', [SalesController::class, 'show'])->name('show');

            // Line items
            Route::post('/{sale}/items', [SalesController::class, 'addItem'])->name('items.add');
            Route::patch('/{sale}/items/{saleItem}', [SalesController::class, 'updateItem'])->name('items.update');
            Route::delete('/{sale}/items/{saleItem}', [SalesController::class, 'removeItem'])->name('items.remove');

            // Customer attachment
            Route::post('/{sale}/customer', [SalesController::class, 'attachCustomer'])->name('customer.attach');
            Route::delete('/{sale}/customer', [SalesController::class, 'detachCustomer'])->name('customer.detach');

            // Payments
            Route::prefix('{sale}/payments')->name('payments.')->group(function () {
                Route::post('/cash', [PaymentsController::class, 'cash'])->name('cash');
                Route::post('/card', [PaymentsController::class, 'card'])->name('card');
                Route::post('/mobile-money', [PaymentsController::class, 'mobileMoney'])->name('mobile-money');
                Route::post('/split', [PaymentsController::class, 'split'])->name('split');
                Route::get('/outstanding', [PaymentsController::class, 'outstanding'])->name('outstanding');
            });

            // Checkout actions
            Route::prefix('{sale}')->name('checkout.')->group(function () {
                Route::post('/discount/preview', [CheckoutController::class, 'previewDiscount'])->name('discount.preview');
                Route::post('/discount', [CheckoutController::class, 'applyDiscount'])->name('discount.apply');
                Route::delete('/discount', [CheckoutController::class, 'removeDiscount'])->name('discount.remove');
                Route::post('/complete', [CheckoutController::class, 'complete'])->name('complete');
                Route::post('/void', [CheckoutController::class, 'void'])->name('void');
                Route::get('/receipt', [CheckoutController::class, 'receipt'])->name('receipt');
                Route::post('/receipt/print', [CheckoutController::class, 'printReceipt'])->name('receipt.print');
            });
        });
    });

    // =========================================================================
    // Organisation-scoped routes
    // All routes below are scoped to an organisation — every resource
    // belongs to an org and is only accessible within it.
    // =========================================================================

    Route::prefix('organizations/{organization}')->name('org.')->group(function () {

        // ─── Products & Categories ────────────────────────────────────────────

        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::post('/', [CategoryController::class, 'store'])->name('store');
            Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
            Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('index');
            Route::post('/', [ProductController::class, 'store'])->name('store');
            Route::get('/{product}', [ProductController::class, 'show'])->name('show');
            Route::put('/{product}', [ProductController::class, 'update'])->name('update');
            Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
        });

        // ─── Customers & CRM ──────────────────────────────────────────────────

        Route::prefix('customers')->name('customers.')->group(function () {
            Route::get('/', [CustomerController::class, 'index'])->name('index');
            Route::post('/', [CustomerController::class, 'store'])->name('store');
            Route::get('/{customer}', [CustomerController::class, 'show'])->name('show');
            Route::put('/{customer}', [CustomerController::class, 'update'])->name('update');
            Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('destroy');

            // Loyalty points
            Route::prefix('{customer}/loyalty')->name('loyalty.')->group(function () {
                Route::get('/', [LoyaltyController::class, 'history'])->name('history');
                Route::post('/redeem', [LoyaltyController::class, 'redeem'])->name('redeem');
                Route::post('/bonus', [LoyaltyController::class, 'bonus'])->name('bonus');
            });

            // Vouchers
            Route::prefix('{customer}/vouchers')->name('vouchers.')->group(function () {
                Route::get('/', [VoucherController::class, 'index'])->name('index');
                Route::post('/', [VoucherController::class, 'assign'])->name('assign');
                Route::delete('/{voucher}', [VoucherController::class, 'revoke'])->name('revoke');
            });
        });

        // ─── Discounts ────────────────────────────────────────────────────────

        Route::prefix('discounts')->name('discounts.')->group(function () {
            Route::get('/', [DiscountController::class, 'index'])->name('index');
            Route::post('/', [DiscountController::class, 'store'])->name('store');
            Route::put('/{discount}', [DiscountController::class, 'update'])->name('update');
            Route::delete('/{discount}', [DiscountController::class, 'destroy'])->name('destroy');

            // Codes under a discount rule
            Route::prefix('{discount}/codes')->name('codes.')->group(function () {
                Route::get('/', [DiscountCodeController::class, 'index'])->name('index');
                Route::post('/', [DiscountCodeController::class, 'store'])->name('store');
                Route::patch('/{code}', [DiscountCodeController::class, 'deactivate'])->name('deactivate');
                Route::delete('/{code}', [DiscountCodeController::class, 'destroy'])->name('destroy');
            });
        });

        // ─── Reports ─────────────────────────────────────────────────────────

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/summary', [ReportController::class, 'organizationSummary'])->name('org-summary');
        });

        // ─── Settings ─────────────────────────────────────────────────────────

        Route::prefix('locations')->name('locations.')->group(function () {
            Route::get('/', [LocationController::class, 'index'])->name('index');
            Route::post('/', [LocationController::class, 'store'])->name('store');
            Route::put('/{location}', [LocationController::class, 'update'])->name('update');
            Route::delete('/{location}', [LocationController::class, 'destroy'])->name('destroy');
            Route::post('/{location}/users', [LocationController::class, 'assignUser'])->name('users.assign');
            Route::delete('/{location}/users/{user}', [LocationController::class, 'removeUser'])->name('users.remove');
        });

        Route::prefix('loyalty-tiers')->name('loyalty-tiers.')->group(function () {
            Route::get('/', [LoyaltyTiersController::class, 'index'])->name('index');
            Route::post('/', [LoyaltyTiersController::class, 'store'])->name('store');
            Route::put('/{tier}', [LoyaltyTiersController::class, 'update'])->name('update');
            Route::delete('/{tier}', [LoyaltyTiersController::class, 'destroy'])->name('destroy');
        });
    });

    // =========================================================================
    // Location-scoped routes
    // These routes are scoped to a specific branch rather than the org,
    // because the data they return is always location-specific.
    // =========================================================================

    Route::prefix('locations/{location}')->name('location.')->group(function () {

        // ─── Sales history ────────────────────────────────────────────────────

        Route::prefix('sales')->name('sales.')->group(function () {
            Route::get('/', [SaleHistoryController::class, 'index'])->name('index');
            Route::get('/{sale}', [SaleHistoryController::class, 'show'])->name('show');
            Route::post('/{sale}/refund', [RefundController::class, 'refund'])->name('refund');
        });

        // ─── Inventory ────────────────────────────────────────────────────────

        Route::prefix('inventory')->name('inventory.')->group(function () {
            Route::get('/', [InventoryController::class, 'index'])->name('index');
            Route::get('/{variant}', [InventoryController::class, 'show'])->name('show');
            Route::post('/{variant}/adjust', [InventoryController::class, 'adjust'])->name('adjust');
            Route::patch('/{variant}/thresholds', [InventoryController::class, 'updateThresholds'])->name('thresholds');
        });

        // ─── Reports ──────────────────────────────────────────────────────────

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/summary', [ReportController::class, 'summary'])->name('summary');
            Route::get('/revenue/daily', [ReportController::class, 'revenueByDay'])->name('revenue.daily');
            Route::get('/revenue/weekly', [ReportController::class, 'revenueByWeek'])->name('revenue.weekly');
            Route::get('/revenue/monthly', [ReportController::class, 'revenueByMonth'])->name('revenue.monthly');
            Route::get('/products', [ReportController::class, 'topProducts'])->name('products');
            Route::get('/categories', [ReportController::class, 'topCategories'])->name('categories');
            Route::get('/payments', [ReportController::class, 'paymentBreakdown'])->name('payments');
            Route::get('/cashiers', [ReportController::class, 'cashierPerformance'])->name('cashiers');
        });

        // ─── Terminal settings ────────────────────────────────────────────────

        Route::prefix('terminals')->name('terminals.')->group(function () {
            Route::get('/', [TerminalSettingsController::class, 'index'])->name('index');
            Route::post('/', [TerminalSettingsController::class, 'store'])->name('store');
            Route::put('/{terminal}', [TerminalSettingsController::class, 'update'])->name('update');
            Route::delete('/{terminal}', [TerminalSettingsController::class, 'destroy'])->name('destroy');
        });
    });

    // =========================================================================
    // Cross-location routes
    // These don't belong to a single location.
    // =========================================================================

    // Stock transfer between locations
    Route::post('/inventory/transfer', [InventoryController::class, 'transfer'])
        ->name('inventory.transfer');

});
