<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\Anggota;
use App\Models\Denda;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    // Admin & Anggota: Lihat Status Peminjaman (Diagram 1 & 14)
    public function lihatStatusPeminjaman(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            $peminjamans = Peminjaman::with(['anggota', 'buku'])->latest()->get();
            return view('admin.peminjaman.index', compact('peminjamans'));
        } else {
            $anggota = $user->anggota;
            if (!$anggota) {
                return redirect('/')->with('error', 'Profil anggota tidak ditemukan.');
            }

            $peminjamans = Peminjaman::where('anggota_id', $anggota->id)
                                     ->with('buku')
                                     ->latest()
                                     ->get();
            
            $dendaBelumDibayar = Denda::whereHas('peminjaman', function($q) use ($anggota) {
                $q->where('anggota_id', $anggota->id);
            })->where('status_bayar', 'belum')->sum('total_denda');
            
            return view('anggota.peminjaman.index', compact('peminjamans', 'dendaBelumDibayar'));
        }
    }

    // Admin: Konfirmasi Peminjaman (Diagram 1)
    public function konfirmasiPeminjaman($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Status peminjaman tidak valid untuk disetujui.');
        }

        if ($peminjaman->buku->stok <= 0) {
            return back()->with('error', 'Gagal menyetujui. Stok buku sudah habis.');
        }

        $peminjaman->status = 'disetujui';
        $peminjaman->save();

        // Kurangi stok buku
        $buku = $peminjaman->buku;
        $buku->stok -= 1;
        $buku->save();

        return back()->with('success', 'Peminjaman disetujui. Stok buku telah dikurangi.');
    }

    // Admin: Tolak Peminjaman (Diagram 1)
    public function tolakPeminjaman($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Status peminjaman tidak valid.');
        }

        $peminjaman->status = 'ditolak';
        $peminjaman->save();

        return back()->with('success', 'Peminjaman ditolak.');
    }

    // Anggota: Ajuan Peminjaman (Diagram 13)
    public function ajuanPeminjaman(Request $request, $idBuku)
    {
        $buku = Buku::findOrFail($idBuku);
        $anggota = Auth::user()->anggota;

        if (!$anggota) {
            return back()->with('error', 'Anda harus melengkapi profil anggota.');
        }

        if ($buku->stok <= 0) {
            return back()->with('error', 'Maaf, stok buku ini sedang kosong.');
        }

        // Cek denda belum dibayar
        $punyaDenda = Denda::whereHas('peminjaman', function($q) use ($anggota) {
            $q->where('anggota_id', $anggota->id);
        })->where('status_bayar', 'belum')->exists();

        if ($punyaDenda) {
            return back()->with('error', 'Anda memiliki denda yang belum dibayar. Tidak bisa meminjam buku.');
        }

        // Cek peminjaman aktif
        $pinjamanAktif = Peminjaman::where('anggota_id', $anggota->id)
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->count();

        if ($pinjamanAktif >= 3) {
            return back()->with('error', 'Anda telah mencapai batas maksimal peminjaman (3 buku).');
        }
        
        // Cek apakah buku yang sama sedang dipinjam
        $sedangPinjamBukuIni = Peminjaman::where('anggota_id', $anggota->id)
            ->where('buku_id', $buku->id)
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->exists();
            
        if ($sedangPinjamBukuIni) {
             return back()->with('error', 'Anda masih memiliki peminjaman aktif untuk buku ini.');
        }

        $tgl_pinjam = Carbon::now()->toDateString();
        // hitungJatuhTempo() sesuai diagram
        $tgl_jatuh_tempo = Carbon::now()->addDays(7)->toDateString(); 

        Peminjaman::create([
            'anggota_id' => $anggota->id,
            'buku_id' => $buku->id,
            'tgl_pinjam' => $tgl_pinjam,
            'tgl_jatuh_tempo' => $tgl_jatuh_tempo,
            'status' => 'menunggu'
        ]);

        return redirect()->route('anggota.peminjaman')->with('success', 'Pengajuan peminjaman berhasil. Menunggu konfirmasi admin.');
    }
}
