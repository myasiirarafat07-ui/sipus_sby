<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PengembalianController extends Controller
{
    // Admin: Tampilkan Daftar Peminjaman Aktif untuk Pengembalian
    public function indexAdmin()
    {
        $peminjamans = Peminjaman::with(['anggota', 'buku'])->where('status', 'disetujui')->latest()->get();
        return view('admin.pengembalian.index', compact('peminjamans'));
    }

    // Admin & Anggota: Konfirmasi Pengembalian (Diagram 2 & 15)
    public function konfirmasiPengembalian($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $user = Auth::user();


        if ($peminjaman->status !== 'disetujui') {
            return back()->with('error', 'Buku belum dipinjam atau sudah dikembalikan.');
        }

        // updateStatus("selesai")
        $peminjaman->status = 'selesai';
        $peminjaman->tgl_kembali = now()->toDateString();
        $peminjaman->save();

        // Kembalikan stok buku (Diagram 2)
        $buku = $peminjaman->buku;
        $buku->stok += 1;
        $buku->save();

        // Cek keterlambatan & Denda (Diagram 2)
        $tgl_jatuh_tempo = Carbon::parse($peminjaman->tgl_jatuh_tempo)->startOfDay();
        $tgl_kembali = Carbon::parse($peminjaman->tgl_kembali)->startOfDay();

        if ($tgl_kembali->gt($tgl_jatuh_tempo)) {
            $hari_terlambat = $tgl_jatuh_tempo->diffInDays($tgl_kembali);
            
            // Panggil prosesDenda di DendaController menggunakan helper app()
            app(\App\Http\Controllers\DendaController::class)->prosesDenda($peminjaman->id, $hari_terlambat, 'Denda keterlambatan sistem otomatis.');

            return redirect()->route('admin.denda')->with('error', "Buku dikembalikan terlambat {$hari_terlambat} hari. Denda otomatis telah ditambahkan.");
        }

        return redirect()->route('admin.pengembalian')->with('success', 'Buku berhasil dikembalikan tepat waktu.');
    }
}
