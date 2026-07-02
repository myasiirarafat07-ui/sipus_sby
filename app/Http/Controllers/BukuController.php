<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;

class BukuController extends Controller
{
    // Admin: Tampilkan Daftar Buku
    public function indexAdmin()
    {
        $bukus = Buku::latest()->get();
        return view('admin.buku', compact('bukus'));
    }

    // Admin: Tambah Buku (Diagram 18)
    public function tambahBuku(Request $request)
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

    // Admin: Helper untuk Form Edit (Diagram 19)
    public function getBuku($id)
    {
        return response()->json(Buku::findOrFail($id));
    }

    // Admin: Ubah Buku (Diagram 19)
    public function ubahBuku(Request $request, $id)
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

    // Admin: Hapus Buku (Diagram 21)
    public function hapusBuku($id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();
        return back()->with('success', 'Buku berhasil dihapus.');
    }

    // Anggota: Cari Buku / Katalog (Diagram 14)
    public function cariBuku(Request $request)
    {
        $kataKunci = $request->input('q');
        if ($kataKunci) {
            $bukus = Buku::where('judul', 'like', "%{$kataKunci}%")
                         ->orWhere('pengarang', 'like', "%{$kataKunci}%")
                         ->latest()->get();
        } else {
            $bukus = Buku::latest()->get();
        }

        return view('anggota.katalog.index', compact('bukus', 'kataKunci'));
    }
}
