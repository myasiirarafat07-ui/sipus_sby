<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Denda;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;

class DendaController extends Controller
{
    // Admin: Tampilkan Daftar Denda
    public function indexAdmin()
    {
        $dendas = Denda::with(['peminjaman.anggota', 'peminjaman.buku'])->latest()->get();
        return view('admin.denda', compact('dendas'));
    }

    // Admin (atau sistem dari PengembalianController): Proses Denda (Diagram 2 & 6)
    public function prosesDenda($peminjaman_id, $hari_terlambat, $keterangan = '')
    {
        // hitungDenda(hariTerlambat) -> as per Diagram 2
        $total_denda = $this->hitungDenda($hari_terlambat);

        Denda::create([
            'peminjaman_id' => $peminjaman_id,
            'hari_terlambat' => $hari_terlambat,
            'total_denda' => $total_denda,
            'status_bayar' => 'belum',
            'keterangan' => $keterangan
        ]);

        return $total_denda;
    }

    // Fungsi internal untuk menghitung denda (Diagram 2)
    private function hitungDenda($hari_terlambat)
    {
        // Contoh: Rp 1000 per hari keterlambatan
        return $hari_terlambat * 1000;
    }

    // Tambahan untuk Admin jika ingin input manual denda melalui form (fallback)
    public function storeManual(Request $request)
    {
        $data = $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'hari_terlambat' => 'required|integer|min:0',
        ]);

        $keterangan = $request->input('keterangan', 'Denda manual');
        $this->prosesDenda($data['peminjaman_id'], $data['hari_terlambat'], $keterangan);

        return back()->with('success', 'Denda manual berhasil ditambahkan.');
    }

    // Admin: Helper untuk ambil denda berdasarkan anggota (Diagram 7)
    public function getDendaByAnggota($idDenda)
    {
        // Diagram 7 shows getDendaByAnggota() returning dataDenda for FormEdit
        // We actually just return the specific denda item
        return response()->json(Denda::with('peminjaman.anggota')->findOrFail($idDenda));
    }

    // Admin: Update Status Bayar Denda (Diagram 7)
    public function updateStatusBayar(Request $request, $id)
    {
        $denda = Denda::findOrFail($id);
        
        if ($request->has('status_bayar')) {
            $denda->status_bayar = $request->input('status_bayar');
        }
        
        if ($request->has('keterangan')) {
            $denda->keterangan = $request->input('keterangan');
        }

        if ($request->has('total_denda')) {
            $denda->total_denda = $request->input('total_denda');
        }

        if ($request->has('hari_terlambat')) {
            $denda->hari_terlambat = $request->input('hari_terlambat');
        }

        $denda->save();
        return back()->with('success', 'Status/Data denda berhasil diperbarui.');
    }

    // Admin: Hapus Denda (Diagram 8)
    public function hapusDenda($id)
    {
        Denda::findOrFail($id)->delete();
        return back()->with('success', 'Data denda berhasil dihapus.');
    }

    // Anggota: Tampilkan Informasi Denda (Diagram 10)
    public function tampilkanDenda(Request $request)
    {
        $anggota = Auth::user()->anggota;
        if (!$anggota) {
            return redirect('/')->with('error', 'Profil anggota tidak ditemukan.');
        }

        // Ambil daftar denda berdasarkan anggota
        $dendas = Denda::whereHas('peminjaman', function($q) use ($anggota) {
            $q->where('anggota_id', $anggota->id);
        })->with('peminjaman.buku')->latest()->get();

        return view('anggota.pengembalian.index', compact('dendas'));
    }
}
