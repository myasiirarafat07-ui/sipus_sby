<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Denda;

class DendaController extends Controller
{
    public function index()
    {
        $dendas = Denda::with(['peminjaman.anggota', 'peminjaman.buku'])->latest()->get();
        return view('admin.denda', compact('dendas'));
    }

    public function pay($id)
    {
        $denda = Denda::findOrFail($id);
        $denda->status_bayar = 'lunas';
        $denda->save();
        return back()->with('success', 'Status denda berhasil diperbarui menjadi Lunas.');
    }

    public function destroy($id)
    {
        Denda::findOrFail($id)->delete();
        return back()->with('success', 'Data denda berhasil dihapus.');
    }
}
