<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Anggota;
use App\Models\Peminjaman;
use App\Models\Denda;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalBuku = Buku::count();
        $totalAnggota = Anggota::count();
        $totalPeminjamanAktif = Peminjaman::where('status', 'disetujui')->count();
        $totalDenda = Denda::where('status_bayar', 'belum')->sum('total_denda');

        return view('admin.dashboard', compact('totalBuku', 'totalAnggota', 'totalPeminjamanAktif', 'totalDenda'));
    }

    public function buku()
    {
        $bukus = Buku::latest()->get();
        return view('admin.buku', compact('bukus'));
    }

    public function storeBuku(Request $request)
    {
        $data = $request->validate([
            'isbn' => 'required|unique:buku',
            'judul' => 'required',
            'pengarang' => 'required',
            'penerbit' => 'required',
            'tahun_terbit' => 'required|integer',
            'kategori' => 'required',
            'stok' => 'required|integer|min:0',
        ]);

        Buku::create($data);
        return back()->with('success', 'Buku berhasil ditambahkan.');
    }

    public function updateBuku(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);
        $data = $request->validate([
            'isbn' => 'required|unique:buku,isbn,'.$id,
            'judul' => 'required',
            'pengarang' => 'required',
            'penerbit' => 'required',
            'tahun_terbit' => 'required|integer',
            'kategori' => 'required',
            'stok' => 'required|integer|min:0',
        ]);

        $buku->update($data);
        return back()->with('success', 'Buku berhasil diperbarui.');
    }

    public function deleteBuku($id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();
        return back()->with('success', 'Buku berhasil dihapus.');
    }

    public function anggota()
    {
        $anggotas = Anggota::latest()->get();
        return view('admin.anggota', compact('anggotas'));
    }


    public function storeAnggota(Request $request)
    {
        $data = $request->validate([
            'nama_lengkap' => 'required',
            'nik' => 'required|unique:anggota',
            'alamat' => 'required',
            'no_telepon' => 'required',
            'email' => 'required|email|unique:anggota',
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = \App\Models\User::create([
            'username' => $data['username'],
            'password' => \Illuminate\Support\Facades\Hash::make($data['password']),
            'role' => 'anggota',
        ]);

        Anggota::create([
            'user_id' => $user->id,
            'nama_lengkap' => $data['nama_lengkap'],
            'nik' => $data['nik'],
            'alamat' => $data['alamat'],
            'no_telepon' => $data['no_telepon'],
            'email' => $data['email'],
            'tgl_registrasi' => now(),
        ]);

        return back()->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function updateAnggota(Request $request, $id)
    {
        $anggota = Anggota::findOrFail($id);
        $data = $request->validate([
            'nama_lengkap' => 'required',
            'nik' => 'required|unique:anggota,nik,'.$id,
            'alamat' => 'required',
            'no_telepon' => 'required',
            'email' => 'required|email|unique:anggota,email,'.$id,
        ]);
        $anggota->update($data);
        return back()->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function deleteAnggota($id)
    {
        $anggota = Anggota::findOrFail($id);
        if ($anggota->user) {
            $anggota->user->delete();
        }
        $anggota->delete();
        return back()->with('success', 'Anggota berhasil dihapus.');
    }

    public function peminjaman()
    {
        $peminjamans = Peminjaman::with(['anggota', 'buku'])->latest()->get();
        return view('admin.peminjaman.index', compact('peminjamans'));
    }

    public function approvePeminjaman($id)
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

    public function rejectPeminjaman($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Status peminjaman tidak valid.');
        }

        $peminjaman->status = 'ditolak';
        $peminjaman->save();

        return back()->with('success', 'Peminjaman ditolak.');
    }

    public function pengembalian()
    {
        $peminjamans = Peminjaman::with(['anggota', 'buku'])->where('status', 'disetujui')->latest()->get();
        return view('admin.pengembalian.index', compact('peminjamans'));
    }

    public function confirmPengembalian($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        if ($peminjaman->status !== 'disetujui') {
            return back()->with('error', 'Buku belum dipinjam atau sudah dikembalikan.');
        }

        $peminjaman->status = 'selesai';
        $peminjaman->tgl_kembali = now()->toDateString();
        $peminjaman->save();

        // Kembalikan stok buku
        $buku = $peminjaman->buku;
        $buku->stok += 1;
        $buku->save();

        // Cek keterlambatan & Denda Otomatis
        $tgl_jatuh_tempo = \Carbon\Carbon::parse($peminjaman->tgl_jatuh_tempo)->startOfDay();
        $tgl_kembali = \Carbon\Carbon::parse($peminjaman->tgl_kembali)->startOfDay();

        if ($tgl_kembali->gt($tgl_jatuh_tempo)) {
            $hari_terlambat = $tgl_jatuh_tempo->diffInDays($tgl_kembali);
            $total_denda = $hari_terlambat * 1000;

            Denda::create([
                'peminjaman_id' => $peminjaman->id,
                'hari_terlambat' => $hari_terlambat,
                'total_denda' => $total_denda,
                'status_bayar' => 'belum',
                'keterangan' => 'Denda keterlambatan sistem otomatis.'
            ]);

            return redirect()->route('admin.denda')->with('error', "Buku dikembalikan terlambat {$hari_terlambat} hari. Denda otomatis Rp" . number_format($total_denda, 0, ',', '.') . " telah ditambahkan.");
        }

        return back()->with('success', 'Buku berhasil dikembalikan tepat waktu dan stok buku telah dikembalikan.');
    }
}
