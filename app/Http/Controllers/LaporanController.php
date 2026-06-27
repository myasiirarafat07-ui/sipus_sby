<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Denda;

class LaporanController extends Controller
{
    public function index()
    {
        return view('admin.laporan');
    }

    public function cetak(Request $request)
    {
        $jenis = $request->jenis_laporan;
        $tgl_mulai = $request->tgl_mulai;
        $tgl_akhir = $request->tgl_akhir;

        if ($jenis == 'peminjaman') {
            $data = Peminjaman::with(['anggota', 'buku'])
                ->whereBetween('tgl_pinjam', [$tgl_mulai, $tgl_akhir])
                ->get();
            return view('admin.laporan_cetak', compact('data', 'jenis', 'tgl_mulai', 'tgl_akhir'));
        } elseif ($jenis == 'denda') {
            $data = Denda::with(['peminjaman.anggota', 'peminjaman.buku'])
                ->whereHas('peminjaman', function($q) use ($tgl_mulai, $tgl_akhir) {
                    $q->whereBetween('tgl_kembali', [$tgl_mulai, $tgl_akhir]);
                })
                ->get();
            return view('admin.laporan_cetak', compact('data', 'jenis', 'tgl_mulai', 'tgl_akhir'));
        }

        return back()->with('error', 'Jenis laporan tidak valid.');
    }
}
