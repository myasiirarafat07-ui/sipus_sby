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

    public function store(Request $request)
    {
        $data = $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'hari_terlambat' => 'required|integer|min:0',
            'total_denda' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $data['status_bayar'] = 'belum';
        Denda::create($data);

        return back()->with('success', 'Denda manual berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $denda = Denda::findOrFail($id);
        
        // This is usually for "Pay" or editing the fine status
        if ($request->has('status_bayar')) {
            $denda->status_bayar = $request->input('status_bayar');
        }
        
        if ($request->has('keterangan')) {
            $denda->keterangan = $request->input('keterangan');
        }

        if ($request->has('total_denda')) {
            $denda->total_denda = $request->input('total_denda');
        }

        if ($request->has('hari_terlambat')) {
            $denda->hari_terlambat = $request->input('hari_terlambat');
        }

        $denda->save();
        return back()->with('success', 'Data denda berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Denda::findOrFail($id)->delete();
        return back()->with('success', 'Data denda berhasil dihapus.');
    }
}
