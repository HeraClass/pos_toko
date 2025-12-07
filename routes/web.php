<?php

use App\Http\Controllers\AdjustmentController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect('/admin');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/export', [ExportController::class, 'export'])->name('export');
});

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');

    Route::resource('categories', CategoryController::class);

    Route::resource('products', ProductController::class);
    Route::get('/products/export/pdf', [ProductController::class, 'exportPdf'])->name('products.export.pdf');
    Route::get('/products/export/csv', [ProductController::class, 'exportCsv'])->name('products.export.csv');

    Route::resource('customers', CustomerController::class);
    Route::get('/customers/{customer}/order-history', [CustomerController::class, 'orderHistory'])->name('customers.order-history');
    Route::get('/customers/export/pdf', [CustomerController::class, 'exportPdf'])->name('customers.export.pdf');
    Route::get('/customers/export/csv', [CustomerController::class, 'exportCsv'])->name('customers.export.csv');

    Route::get('/orders/{id}/invoice-modal', [OrderController::class, 'getInvoiceModal'])
        ->name('orders.invoice.modal')
        ->middleware('permission:orders.view');
    Route::get('/orders/{id}/invoice-print', [OrderController::class, 'printInvoice'])
        ->name('orders.invoice.print')
        ->middleware('permission:orders.view');
    Route::resource('orders', OrderController::class);
    Route::get('orders/export/pdf', [OrderController::class, 'exportPdf'])->name('orders.export.pdf');
    Route::get('orders/export/csv', [OrderController::class, 'exportCsv'])->name('orders.export.csv');
    Route::resource('suppliers', SupplierController::class);
    Route::get('/suppliers/export/pdf', [SupplierController::class, 'exportPdf'])->name('suppliers.export.pdf');
    Route::get('/suppliers/export/csv', [SupplierController::class, 'exportCsv'])->name('suppliers.export.csv');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::post('/cart/change-qty', [CartController::class, 'changeQty']);
    Route::delete('/cart/delete', [CartController::class, 'delete']);
    Route::delete('/cart/empty', [CartController::class, 'empty']);

    Route::post('/orders/partial-payment', [OrderController::class, 'partialPayment'])->name('orders.partial-payment');

    Route::resource('purchases', PurchaseController::class);
    Route::get('/purchases/export/pdf', [PurchaseController::class, 'exportPdf'])->name('purchases.export.pdf');
    Route::get('/purchases/export/csv', [PurchaseController::class, 'exportCsv'])->name('purchases.export.csv');

    Route::resource('adjustments', AdjustmentController::class);
    Route::get('/adjustments/export/pdf', [AdjustmentController::class, 'exportPdf'])->name('adjustments.export.pdf');
    Route::get('/adjustments/export/csv', [AdjustmentController::class, 'exportCsv'])->name('adjustments.export.csv');

    Route::resource('roles', RoleController::class);

    Route::resource('permissions', PermissionController::class);

    Route::resource('users', UserController::class);

    Route::get('/locale/{type}', function ($type) {
        $translations = trans($type);
        return response()->json($translations);
    });

    Route::get('/lang-switch/{lang}', function ($lang) {
        $supportedLocales = ['en', 'id'];

        if (in_array($lang, $supportedLocales)) {
            session(['locale' => $lang]);
            app()->setLocale($lang);
        }

        return redirect()->back();
    })->name('lang.switch');
});

Route::get('/test-permission', function () {
    dd(auth()->user()->getAllPermissions());
})->middleware('auth');
