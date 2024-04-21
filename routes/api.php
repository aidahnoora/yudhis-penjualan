<?php

use App\Http\Controllers\WebAPI\BarangController;
use App\Http\Controllers\WebAPI\CustomerController;
use App\Http\Controllers\WebAPI\DashboardController;
use App\Http\Controllers\WebAPI\LoginController;
use App\Http\Controllers\WebAPI\SupplierController;
use App\Http\Controllers\WebAPI\TransaksiController;
use App\Http\Controllers\WebAPI\UserController;
use Illuminate\Http\Request;
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

    Route::apiResource('/suppliers', SupplierController::class);
    Route::apiResource('/users', UserController::class);
    Route::apiResource('/barangs', BarangController::class);
    Route::get('/get-suppliers', [BarangController::class, 'getSuppliers'])->name('get-suppliers');
    Route::apiResource('/customers', CustomerController::class);
    Route::apiResource('/transaksis', TransaksiController::class);
    Route::get('/get-customers', [TransaksiController::class, 'getCustomers'])->name('get-customers');
    Route::get('/get-barangs', [TransaksiController::class, 'getBarangs'])->name('get-barangs');
    Route::get('/transaksis/{transaksiId}/items', [TransaksiController::class, 'getItemTransaksis']);
    Route::get('/laporan-transaksi-penjualan', [TransaksiController::class, 'report']);
});

Route::get('/get-transaction', [DashboardController::class, 'getTransaction'])->name('get-transaction');
