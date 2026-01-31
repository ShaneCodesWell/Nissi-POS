<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\TerminalController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/terminal', [TerminalController::class, 'index'])->name('pos.index');

// Admin Routes
Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

// Will create correct Controllers for the index when database tables are ready
Route::get('/admin/product', [AdminController::class, 'product'])->name('admin.product');
Route::get('/admin/sales', [AdminController::class, 'sales'])->name('admin.sales');
Route::get('/admin/reports', [AdminController::class, 'reports'])->name('admin.reports');
Route::get('/admin/crm', [AdminController::class, 'crm'])->name('admin.crm');
