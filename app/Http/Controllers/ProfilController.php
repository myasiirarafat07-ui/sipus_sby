<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Anggota;

class ProfilController extends Controller
{
    public function index()
    {
        $anggota = Auth::user()->anggota;
        return view('anggota.profil', compact('anggota'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $anggota = $user->anggota;

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
            'email' => ['required', 'email', Rule::unique('anggota')->ignore($anggota->id)],
            'password' => 'nullable|min:6|confirmed',
        ]);

        $anggota->update([
            'nama_lengkap' => $request->nama_lengkap,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
