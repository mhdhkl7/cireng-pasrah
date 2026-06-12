<?php

use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InventoriController;
use App\Http\Controllers\Admin\PesananController as AdminPesananController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\KatalogController;
use App\Http\Controllers\Customer\KeranjangController;
use App\Http\Controllers\Customer\PesananController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Route Publik (Guest)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('katalog.index');
    }
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Route Customer
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'customer'])->group(function () {
    // Katalog
    Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog.index');
    Route::get('/katalog/{produk}', [KatalogController::class, 'show'])->name('katalog.show');

    // Keranjang
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang/tambah', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
    Route::patch('/keranjang/update', [KeranjangController::class, 'update'])->name('keranjang.update');
    Route::delete('/keranjang/hapus', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');
    Route::delete('/keranjang/kosongkan', [KeranjangController::class, 'kosongkan'])->name('keranjang.kosongkan');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/proses', [CheckoutController::class, 'proses'])->name('checkout.proses');

    // Pesanan Customer
    Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{kode}', [PesananController::class, 'show'])->name('pesanan.show');
});

/*
|--------------------------------------------------------------------------
| Route Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Produk CRUD
    Route::resource('produk', ProdukController::class)->except(['show']);

    // Inventori
    Route::get('/inventori', [InventoriController::class, 'index'])->name('inventori.index');
    Route::post('/inventori', [InventoriController::class, 'store'])->name('inventori.store');
    Route::put('/inventori/{inventori}', [InventoriController::class, 'update'])->name('inventori.update');
    Route::delete('/inventori/{inventori}', [InventoriController::class, 'destroy'])->name('inventori.destroy');
    Route::post('/inventori/{inventori}/tambah-stok', [InventoriController::class, 'tambahStok'])->name('inventori.tambahStok');

    // Pesanan Admin
    Route::get('/pesanan', [AdminPesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{pesanan}', [AdminPesananController::class, 'show'])->name('pesanan.show');
    Route::patch('/pesanan/{pesanan}/status', [AdminPesananController::class, 'updateStatus'])->name('pesanan.updateStatus');
    Route::patch('/pesanan/{pesanan}/verifikasi', [AdminPesananController::class, 'verifikasiPembayaran'])->name('pesanan.verifikasi');

    // Customer Management
    Route::get('/customer', [AdminCustomerController::class, 'index'])->name('customer.index');
    Route::get('/customer/{user}', [AdminCustomerController::class, 'show'])->name('customer.show');
});
