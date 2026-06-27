<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\DendaController;
use App\Http\Controllers\LaporanController;
use App\Http\Middleware\RoleMiddleware;

use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/buku/{id}', [HomeController::class, 'detailBuku'])->name('buku.detail');
Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth', RoleMiddleware::class.':admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    Route::get('/buku', [AdminController::class, 'buku'])->name('admin.buku');
    Route::post('/buku', [AdminController::class, 'storeBuku'])->name('admin.buku.store');
    Route::put('/buku/{id}', [AdminController::class, 'updateBuku'])->name('admin.buku.update');
    Route::delete('/buku/{id}', [AdminController::class, 'deleteBuku'])->name('admin.buku.destroy');

    Route::get('/anggota', [AdminController::class, 'anggota'])->name('admin.anggota');
    Route::put('/anggota/{id}', [AdminController::class, 'updateAnggota'])->name('admin.anggota.update');
    Route::delete('/anggota/{id}', [AdminController::class, 'deleteAnggota'])->name('admin.anggota.destroy');
    Route::post('/anggota/{id}/reset-password', [AdminController::class, 'resetPassword'])->name('admin.anggota.reset_password');

    Route::get('/peminjaman', [AdminController::class, 'peminjaman'])->name('admin.peminjaman');
    Route::post('/peminjaman/{id}/approve', [AdminController::class, 'approvePeminjaman'])->name('admin.peminjaman.approve');
    Route::post('/peminjaman/{id}/return', [AdminController::class, 'returnPeminjaman'])->name('admin.peminjaman.return');

    Route::get('/denda', [DendaController::class, 'index'])->name('admin.denda');
    Route::post('/denda/{id}/pay', [DendaController::class, 'pay'])->name('admin.denda.pay');
    Route::delete('/denda/{id}', [DendaController::class, 'destroy'])->name('admin.denda.destroy');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('admin.laporan');
    Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('admin.laporan.cetak');
});

Route::middleware(['auth', RoleMiddleware::class.':anggota'])->group(function () {
    Route::post('/katalog/{id}/pinjam', [KatalogController::class, 'pinjam'])->name('katalog.pinjam');
    Route::get('/riwayat', [KatalogController::class, 'riwayat'])->name('katalog.riwayat');

    Route::get('/profil', [\App\Http\Controllers\ProfilController::class, 'index'])->name('anggota.profil');
    Route::put('/profil', [\App\Http\Controllers\ProfilController::class, 'update'])->name('anggota.profil.update');
});
