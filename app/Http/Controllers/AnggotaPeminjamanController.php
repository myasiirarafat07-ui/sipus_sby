<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Peminjaman;
use App\Models\Denda;

class AnggotaPeminjamanController extends Controller
{
    public function index()
    {
        $anggota = auth()->user()->anggota;
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

    public function kembalikan(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        $anggota = auth()->user()->anggota;
        if (!$anggota || $peminjaman->anggota_id !== $anggota->id) {
            return back()->with('error', 'Anda tidak berhak mengembalikan buku ini.');
        }

        if ($peminjaman->status !== 'disetujui') {
            return back()->with('error', 'Buku belum disetujui untuk dipinjam atau sudah dikembalikan.');
        }

        // Just notify the user to bring the book to the counter
        return back()->with('success', 'Silakan serahkan buku fisik ke petugas perpustakaan untuk diproses.');
    }
}
