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
        // Auto calculate 7 days due date from today
        $peminjaman->tgl_pinjam = now()->toDateString();
        $peminjaman->tgl_jatuh_tempo = now()->addDays(7)->toDateString();
        $peminjaman->save();

        return back()->with('success', 'Peminjaman disetujui.');
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

        // Check for Denda via Observer or redirect
        if (Denda::where('peminjaman_id', $peminjaman->id)->exists()) {
            return redirect()->route('admin.denda')->with('error', 'Buku dikembalikan, namun terdapat denda keterlambatan.');
        }

        return back()->with('success', 'Buku berhasil dikembalikan tepat waktu.');
    }
}
