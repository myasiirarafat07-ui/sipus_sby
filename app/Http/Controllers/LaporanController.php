<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Denda;

class LaporanController extends Controller
{
    // Admin: Tampilkan Form Laporan
    public function tampilkanFormLaporan()
    {
        return view('admin.laporan');
    }

    // Admin: Generate Laporan (Diagram 9)
    public function generateLaporan(Request $request)
    {
        $jenis = $request->input('jenis_laporan');
        $tgl_mulai = $request->input('tgl_mulai');
        $tgl_akhir = $request->input('tgl_akhir');

        // Panggil getLaporanPeminjaman jika jenis = peminjaman (mengikuti diagram)
        if ($jenis == 'peminjaman') {
            $dataLaporan = $this->getLaporanPeminjaman($tgl_mulai, $tgl_akhir);
            return view('admin.laporan_cetak', [
                'data' => $dataLaporan, 
                'jenis' => $jenis, 
                'tgl_mulai' => $tgl_mulai, 
                'tgl_akhir' => $tgl_akhir
            ]);
        } elseif ($jenis == 'denda') {
            $dataLaporan = Denda::with(['peminjaman.anggota', 'peminjaman.buku'])
                ->whereHas('peminjaman', function($q) use ($tgl_mulai, $tgl_akhir) {
                    $q->whereBetween('tgl_kembali', [$tgl_mulai, $tgl_akhir]);
                })
                ->get();
            return view('admin.laporan_cetak', [
                'data' => $dataLaporan, 
                'jenis' => $jenis, 
                'tgl_mulai' => $tgl_mulai, 
                'tgl_akhir' => $tgl_akhir
            ]);
        }

        return back()->with('error', 'Jenis laporan tidak valid.');
    }

    // Helper (Diagram 9)
    private function getLaporanPeminjaman($tglMulai, $tglAkhir)
    {
        return Peminjaman::with(['anggota', 'buku'])
            ->whereBetween('tgl_pinjam', [$tglMulai, $tglAkhir])
            ->get();
    }
}
