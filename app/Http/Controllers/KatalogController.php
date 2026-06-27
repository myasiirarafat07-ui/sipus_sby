<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Denda;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::query();
        if ($request->has('q') && $request->q != '') {
            $query->where('judul', 'like', '%' . $request->q . '%')
                  ->orWhere('pengarang', 'like', '%' . $request->q . '%');
        }
        $bukus = $query->get();
        $anggota = auth()->check() ? auth()->user()->anggota : null;
        return view('katalog.index', compact('bukus', 'anggota'));
    }

    public function riwayat()
    {
        $anggota = auth()->user()->anggota;
        if (!$anggota) {
            return redirect('/')->with('error', 'Profil anggota tidak ditemukan.');
        }
        $peminjamans = Peminjaman::where('anggota_id', $anggota->id)->with('buku')->latest()->get();
        
        $dendaBelumDibayar = Denda::whereHas('peminjaman', function($q) use ($anggota) {
            $q->where('anggota_id', $anggota->id);
        })->where('status_bayar', 'belum')->sum('total_denda');

        return view('katalog.riwayat', compact('peminjamans', 'dendaBelumDibayar'));
    }
    public function pinjam(Request $request, $buku_id)
    {
        $buku = Buku::findOrFail($buku_id);

        if ($buku->stok <= 0) {
            return back()->with('error', 'Stok buku habis.');
        }

        $anggota = auth()->user()->anggota;
        
        if (!$anggota) {
            return back()->with('error', 'Profil anggota tidak ditemukan.');
        }

        $activeLoans = Peminjaman::where('anggota_id', $anggota->id)
            ->whereIn('status', ['menunggu', 'aktif'])
            ->count();

        if ($activeLoans >= 5) {
            return back()->with('error', 'Anda telah mencapai batas maksimal peminjaman aktif (5 buku). Harap kembalikan buku sebelumnya.');
        }

        $alreadyBorrowed = Peminjaman::where('anggota_id', $anggota->id)
            ->where('buku_id', $buku->id)
            ->whereIn('status', ['menunggu', 'aktif'])
            ->exists();

        if ($alreadyBorrowed) {
            return back()->with('error', 'Anda sudah mengajukan peminjaman atau sedang meminjam buku ini. Harap kembalikan terlebih dahulu.');
        }

        Peminjaman::create([
            'anggota_id' => $anggota->id,
            'buku_id' => $buku->id,
            'tgl_pinjam' => now(),
            'tgl_jatuh_tempo' => now()->addDays(7), // Pinjam 7 hari
            'status' => 'menunggu'
        ]);

        return back()->with('success', 'Permintaan peminjaman berhasil diajukan.');
    }
}
