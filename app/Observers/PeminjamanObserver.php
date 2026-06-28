<?php

namespace App\Observers;

use App\Models\Peminjaman;
use App\Models\Denda;
use Carbon\Carbon;

class PeminjamanObserver
{
    /**
     * Handle the Peminjaman "updated" event.
     */
    public function updated(Peminjaman $peminjaman): void
    {
        // Jika status peminjaman berubah
        if ($peminjaman->wasChanged('status')) {
            $buku = $peminjaman->buku;

            if ($peminjaman->status === 'disetujui') {
                // Kurangi stok buku saat dipinjam
                if ($buku && $buku->stok > 0) {
                    $buku->decrement('stok');
                }
            } elseif ($peminjaman->status === 'selesai') {
                // Kembalikan stok buku saat dikembalikan
                if ($buku) {
                    $buku->increment('stok');
                }

                // Hitung denda terlambat
                if ($peminjaman->tgl_kembali) {
                    $tgl_kembali = Carbon::parse($peminjaman->tgl_kembali);
                    $tgl_jatuh_tempo = Carbon::parse($peminjaman->tgl_jatuh_tempo);

                    if ($tgl_kembali->greaterThan($tgl_jatuh_tempo)) {
                        $hari_terlambat = $tgl_kembali->diffInDays($tgl_jatuh_tempo);
                        if ($hari_terlambat > 0) {
                            Denda::create([
                                'peminjaman_id' => $peminjaman->id,
                                'hari_terlambat' => $hari_terlambat,
                                'total_denda' => $hari_terlambat * 1000,
                                'status_bayar' => 'belum',
                            ]);
                        }
                    }
                }
            }
        }
    }
}
