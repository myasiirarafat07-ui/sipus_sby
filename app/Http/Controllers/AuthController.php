<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Anggota;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/katalog');
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'nama_lengkap' => 'required',
            'nik' => 'required|unique:anggota',
            'alamat' => 'required',
            'no_telepon' => 'required',
            'email' => 'required|email|unique:anggota',
            'username' => 'required|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

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

        Auth::login($user);

        return redirect('/katalog')->with('success', 'Registrasi berhasil. Selamat datang!');
    }
}
