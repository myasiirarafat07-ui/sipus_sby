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
        $totalPeminjamanAktif = Peminjaman::where('status', 'aktif')->count();
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
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'lokasi_rak' => 'nullable|string|max:255',
            'sinopsis' => 'nullable|string',
        ]);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

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
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'lokasi_rak' => 'nullable|string|max:255',
            'sinopsis' => 'nullable|string',
        ]);

        if ($request->hasFile('cover_image')) {
            if ($buku->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($buku->cover_image)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($buku->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $buku->update($data);
        return back()->with('success', 'Buku berhasil diperbarui.');
    }

    public function deleteBuku($id)
    {
        $buku = Buku::findOrFail($id);
        if ($buku->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($buku->cover_image)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($buku->cover_image);
        }
        $buku->delete();
        return back()->with('success', 'Buku berhasil dihapus.');
    }

    public function anggota()
    {
        $anggotas = Anggota::latest()->get();
        return view('admin.anggota', compact('anggotas'));
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
            $anggota->user->delete(); // Hapus juga data user login-nya
        }
        $anggota->delete();
        return back()->with('success', 'Anggota berhasil dihapus.');
    }

    public function resetPassword($id)
    {
        $anggota = Anggota::findOrFail($id);
        if ($anggota->user) {
            $anggota->user->update([
                'password' => \Illuminate\Support\Facades\Hash::make('password123')
            ]);
            return back()->with('success', 'Password akun ' . $anggota->nama_lengkap . ' berhasil di-reset menjadi "password123".');
        }
        return back()->with('error', 'Gagal mereset password. User tidak ditemukan.');
    }

    public function peminjaman()
    {
        $peminjamans = Peminjaman::with(['anggota', 'buku'])->latest()->get();
        return view('admin.peminjaman', compact('peminjamans'));
    }

    public function approvePeminjaman($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Status peminjaman tidak valid untuk disetujui.');
        }

        if ($peminjaman->buku->stok <= 0) {
            return back()->with('error', 'Gagal menyetujui. Stok buku sudah habis dipinjam oleh pengguna lain.');
        }

        $peminjaman->status = 'aktif';
        $peminjaman->save(); // Stok buku dikurangi otomatis lewat Observer

        return back()->with('success', 'Peminjaman disetujui.');
    }

    public function returnPeminjaman($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        if ($peminjaman->status !== 'aktif') {
            return back()->with('error', 'Buku belum dipinjam atau sudah dikembalikan.');
        }

        $tglJatuhTempo = \Carbon\Carbon::parse($peminjaman->tgl_jatuh_tempo)->startOfDay();
        $tglKembali = now()->startOfDay();
        $hariTerlambat = 0;

        if ($tglKembali->gt($tglJatuhTempo)) {
            $hariTerlambat = $tglKembali->diffInDays($tglJatuhTempo);
        }

        $peminjaman->status = 'selesai';
        $peminjaman->tgl_kembali = now();
        $peminjaman->save(); // Stok buku ditambah otomatis lewat Observer

        if ($hariTerlambat > 0) {
            return redirect()->route('admin.denda')->with('error', 'Buku dikembalikan, namun terdapat denda keterlambatan sebesar Rp' . number_format($hariTerlambat * 1000, 0, ',', '.'));
        }

        return back()->with('success', 'Buku berhasil dikembalikan tepat waktu.');
    }
}
