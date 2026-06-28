<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Denda;

class AnggotaPengembalianController extends Controller
{
    public function index()
    {
        $anggota = auth()->user()->anggota;
        if (!$anggota) {
            return redirect('/')->with('error', 'Profil anggota tidak ditemukan.');
        }

        $dendas = Denda::whereHas('peminjaman', function($q) use ($anggota) {
            $q->where('anggota_id', $anggota->id);
        })->with('peminjaman.buku')->latest()->get();

        return view('anggota.pengembalian.index', compact('dendas'));
    }
}
