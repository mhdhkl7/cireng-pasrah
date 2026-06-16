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
use App\Http\Controllers\Customer\ProfilController;
use App\Http\Controllers\Driver\DashboardController as DriverDashboardController;
use App\Http\Controllers\Driver\PesananController as DriverPesananController;
use App\Http\Controllers\Driver\ProfilController as DriverProfilController;
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
        if (auth()->user()->isDriver()) {
            return redirect()->route('driver.dashboard');
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
Route::middleware(['auth', 'customer', 'session.timeout'])->group(function () {
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
    Route::post('/checkout/hitung-ongkir', [CheckoutController::class, 'hitungOngkir'])->name('checkout.hitungOngkir');

    // Pesanan Customer
    Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{kode}', [PesananController::class, 'show'])->name('pesanan.show');

    // Profil Customer
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::put('/profil/password', [ProfilController::class, 'updatePassword'])->name('profil.password');
});

/*
|--------------------------------------------------------------------------
| Route Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin', 'session.timeout'])->prefix('admin')->name('admin.')->group(function () {
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
    Route::patch('/pesanan/{pesanan}/tidak-diambil', [AdminPesananController::class, 'tandaiTidakDiambil'])->name('pesanan.tidakDiambil');

    // Customer Management
    Route::get('/customer', [AdminCustomerController::class, 'index'])->name('customer.index');
    Route::get('/customer/{user}', [AdminCustomerController::class, 'show'])->name('customer.show');
    Route::delete('/customer/{user}', [AdminCustomerController::class, 'destroy'])->name('customer.destroy');

    // Assign Driver Role
    Route::patch('/customer/{user}/assign-driver', [AdminCustomerController::class, 'assignDriver'])->name('customer.assignDriver');
});

/*
|--------------------------------------------------------------------------
| Route Driver
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'driver', 'session.timeout'])->prefix('driver')->name('driver.')->group(function () {
    Route::get('/dashboard', [DriverDashboardController::class, 'index'])->name('dashboard');

    // Pesanan driver
    Route::get('/pesanan', [DriverPesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{pesanan}', [DriverPesananController::class, 'show'])->name('pesanan.show');

    // Pool: ambil pesanan terbuka
    Route::post('/pesanan/{pesanan}/ambil', [DriverPesananController::class, 'ambil'])->name('pesanan.ambil');

    // Status bertahap
    Route::patch('/pesanan/{pesanan}/update-status', [DriverPesananController::class, 'updateStatus'])->name('pesanan.updateStatus');

    // COD: terima tunai
    Route::post('/pesanan/{pesanan}/terima-tunai', [DriverPesananController::class, 'terimaTunai'])->name('pesanan.terimaTunai');

    // Batal (hanya driver_menuju_resto)
    Route::post('/pesanan/{pesanan}/batal', [DriverPesananController::class, 'batal'])->name('pesanan.batal');

    // Profil driver
    Route::get('/profil', [DriverProfilController::class, 'index'])->name('profil.index');
    Route::put('/profil', [DriverProfilController::class, 'update'])->name('profil.update');
    Route::put('/profil/password', [DriverProfilController::class, 'updatePassword'])->name('profil.password');
});

