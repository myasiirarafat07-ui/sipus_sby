<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anggota;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AnggotaController extends Controller
{
    // Admin: Tampilkan Daftar Anggota
    public function indexAdmin()
    {
        $anggotas = Anggota::latest()->get();
        return view('admin.anggota', compact('anggotas'));
    }

    // Admin: Tambah Anggota (Diagram 3)
    public function daftarAnggota(Request $request)
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

        // "verifikasiAnggota(nik)" tercover oleh validasi `unique:anggota` di atas.

        $user = User::create([
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
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

    // Admin: Helper untuk Form Edit (Diagram 4)
    public function getAnggota($id)
    {
        return response()->json(Anggota::findOrFail($id));
    }

    // Admin: Ubah Anggota (Diagram 4)
    public function ubahAnggota(Request $request, $id)
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

    // Admin: Hapus Anggota (Diagram 5)
    public function hapusAnggota($id)
    {
        $anggota = Anggota::findOrFail($id);
        if ($anggota->user) {
            $anggota->user->delete();
        }
        $anggota->delete();
        return back()->with('success', 'Anggota berhasil dihapus.');
    }
}
