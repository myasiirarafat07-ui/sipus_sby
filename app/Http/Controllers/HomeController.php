<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Anggota;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $totalBuku = Buku::sum('stok');
        $totalAnggota = Anggota::count();
        $latestBooks = Buku::latest()->take(8)->get();
        $popularBooks = Buku::withCount('peminjamans')->having('peminjamans_count', '>', 0)->orderByDesc('peminjamans_count')->take(5)->get();
        
        return view('welcome', compact('totalBuku', 'totalAnggota', 'latestBooks', 'popularBooks'));
    }

    public function detailBuku($id)
    {
        $buku = Buku::findOrFail($id);
        return view('buku.detail', compact('buku'));
    }
}
