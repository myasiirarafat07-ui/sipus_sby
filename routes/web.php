<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\DendaController;
use App\Http\Controllers\LaporanController;
use App\Http\Middleware\RoleMiddleware;
use App\Models\Buku;
use App\Models\Anggota;
use App\Models\Peminjaman;
use App\Models\Denda;


// Public route redirects to login
Route::get('/', function() { return redirect()->route('login'); })->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth', RoleMiddleware::class . ':admin'])->prefix('admin')->group(function () {
    // Halaman Dashboard Admin
    Route::get('/dashboard', function() {
        $totalBuku = Buku::count();
        $totalAnggota = Anggota::count();
        $totalPeminjamanAktif = Peminjaman::where('status', 'disetujui')->count();
        $totalDenda = Denda::where('status_bayar', 'belum')->sum('total_denda');
        return view('admin.dashboard', compact('totalBuku', 'totalAnggota', 'totalPeminjamanAktif', 'totalDenda'));
    })->name('admin.dashboard');
    
    // Kelola Buku
    Route::get('/buku', [BukuController::class, 'indexAdmin'])->name('admin.buku');
    Route::post('/buku', [BukuController::class, 'tambahBuku'])->name('admin.buku.store');
    Route::get('/buku/{id}', [BukuController::class, 'getBuku'])->name('admin.buku.show');
    Route::put('/buku/{id}', [BukuController::class, 'ubahBuku'])->name('admin.buku.update');
    Route::delete('/buku/{id}', [BukuController::class, 'hapusBuku'])->name('admin.buku.destroy');

    // Kelola Anggota
    Route::get('/anggota', [AnggotaController::class, 'indexAdmin'])->name('admin.anggota');
    Route::post('/anggota', [AnggotaController::class, 'daftarAnggota'])->name('admin.anggota.store');
    Route::get('/anggota/{id}', [AnggotaController::class, 'getAnggota'])->name('admin.anggota.show');
    Route::put('/anggota/{id}', [AnggotaController::class, 'ubahAnggota'])->name('admin.anggota.update');
    Route::delete('/anggota/{id}', [AnggotaController::class, 'hapusAnggota'])->name('admin.anggota.destroy');

    // Kelola Peminjaman (Approval)
    Route::get('/peminjaman', [PeminjamanController::class, 'lihatStatusPeminjaman'])->name('admin.peminjaman');
    Route::post('/peminjaman/{id}/approve', [PeminjamanController::class, 'konfirmasiPeminjaman'])->name('admin.peminjaman.approve');
    Route::post('/peminjaman/{id}/reject', [PeminjamanController::class, 'tolakPeminjaman'])->name('admin.peminjaman.reject');

    // Kelola Pengembalian
    Route::get('/pengembalian', [PengembalianController::class, 'indexAdmin'])->name('admin.pengembalian');
    Route::post('/pengembalian/{id}/confirm', [PengembalianController::class, 'konfirmasiPengembalian'])->name('admin.pengembalian.confirm');

    // Kelola Denda
    Route::get('/denda', [DendaController::class, 'indexAdmin'])->name('admin.denda');
    Route::post('/denda', [DendaController::class, 'storeManual'])->name('admin.denda.store');
    Route::get('/denda/{id}', [DendaController::class, 'getDendaByAnggota'])->name('admin.denda.show');
    Route::put('/denda/{id}', [DendaController::class, 'updateStatusBayar'])->name('admin.denda.update');
    Route::delete('/denda/{id}', [DendaController::class, 'hapusDenda'])->name('admin.denda.destroy');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'tampilkanFormLaporan'])->name('admin.laporan');
    Route::get('/laporan/cetak', [LaporanController::class, 'generateLaporan'])->name('admin.laporan.cetak');
});

Route::middleware(['auth', RoleMiddleware::class . ':anggota'])->group(function () {
    // Katalog & Pencarian
    Route::get('/katalog', [BukuController::class, 'cariBuku'])->name('katalog');
    Route::post('/katalog/{id}/pinjam', [PeminjamanController::class, 'ajuanPeminjaman'])->name('katalog.pinjam');
    
    // Status Peminjaman & Riwayat
    Route::get('/peminjaman', [PeminjamanController::class, 'lihatStatusPeminjaman'])->name('anggota.peminjaman');
    Route::post('/peminjaman/{id}/kembalikan', [PengembalianController::class, 'konfirmasiPengembalian'])->name('anggota.peminjaman.kembalikan');

    // Informasi Denda
    Route::get('/pengembalian', [DendaController::class, 'tampilkanDenda'])->name('anggota.pengembalian');
});
