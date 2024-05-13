<?php

use App\Http\Controllers\WebAPI\BarangController;
use App\Http\Controllers\WebAPI\CustomerController;
use App\Http\Controllers\WebAPI\DashboardController;
use App\Http\Controllers\WebAPI\LoginController;
use App\Http\Controllers\WebAPI\SupplierController;
use App\Http\Controllers\WebAPI\TransaksiController;
use App\Http\Controllers\WebAPI\TransaksiPembelianController;
use App\Http\Controllers\WebAPI\UserController;
use App\Models\Customer;
use App\Models\Supplier;
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

Route::post('/register', [LoginController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [LoginController::class, 'fetch']);
    Route::post('/logout', [LoginController::class, 'logout']);
});

Route::apiResource('/suppliers', SupplierController::class);
Route::apiResource('/users', UserController::class);
Route::apiResource('/barangs', BarangController::class);
Route::get('/get-suppliers', [BarangController::class, 'getSuppliers'])->name('get-suppliers');
Route::apiResource('/customers', CustomerController::class);

Route::apiResource('/transaksis', TransaksiController::class);
Route::get('/get-customers', [TransaksiController::class, 'getCustomers'])->name('get-customers');
Route::get('/get-barangs', [TransaksiController::class, 'getBarangs'])->name('get-barangs');
Route::get('/transaksis/{transaksiId}/items', [TransaksiController::class, 'getItemTransaksis']);

Route::apiResource('/pembelians', TransaksiPembelianController::class);
Route::get('/get-suppliers', [TransaksiPembelianController::class, 'getSuppliers'])->name('get-suppliers');
Route::get('/pembelians/{transaksiId}/items', [TransaksiPembelianController::class, 'getItemTransaksis']);

Route::get('/get-transaction', [DashboardController::class, 'getTransaction'])->name('get-transaction');
Route::get('/get-categories', [DashboardController::class, 'getCategories'])->name('get-categories');

Route::get('/transaksi-penjualan/filter', [TransaksiController::class, 'filter']);
Route::get('/transaksi-penjualan/export-pdf', [TransaksiController::class, 'exportPDF']);
Route::get('/transaksi-pembelian/filter', [TransaksiPembelianController::class, 'filter']);
Route::get('/transaksi-pembelian/export-pdf', [TransaksiPembelianController::class, 'exportPDF']);

// Route::get('/barangs/search', [BarangController::class, 'search']);
