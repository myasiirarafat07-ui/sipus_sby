@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')

@section('content')
<div x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)" x-cloak x-show="show" x-transition:enter="transition ease-[cubic-bezier(0.34,1.56,0.64,1)] duration-1000" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Riwayat Peminjaman</h1>
        <p class="text-gray-500 mt-1">Pantau status buku yang Anda pinjam</p>
    </div>
    
    @if($dendaBelumDibayar > 0)
    <div class="glass border-red-300 bg-red-50/50 px-6 py-3 rounded-2xl flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center font-bold">!</div>
        <div>
            <p class="text-sm text-red-600 font-semibold">Total Denda Belum Dibayar</p>
            <p class="text-xl font-bold text-red-700">Rp {{ number_format($dendaBelumDibayar, 0, ',', '.') }}</p>
        </div>
    </div>
    @endif
</div>

<div class="glass p-6 rounded-3xl overflow-hidden shadow-lg">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="py-4 px-4 font-semibold text-gray-600">Buku</th>
                    <th class="py-4 px-4 font-semibold text-gray-600">Tgl Pinjam</th>
                    <th class="py-4 px-4 font-semibold text-gray-600">Jatuh Tempo</th>
                    <th class="py-4 px-4 font-semibold text-gray-600">Tgl Kembali</th>
                    <th class="py-4 px-4 font-semibold text-gray-600">Status</th>
                    <th class="py-4 px-4 font-semibold text-gray-600">Denda</th>
                    <th class="py-4 px-4 font-semibold text-gray-600 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjamans as $p)
                <tr class="border-b border-gray-100/50 hover:bg-white/50 hover:scale-[1.01] transition-all duration-300">
                    <td class="py-4 px-4 text-gray-800 font-medium">{{ $p->buku->judul }}</td>
                    <td class="py-4 px-4">{{ \Carbon\Carbon::parse($p->tgl_pinjam)->format('d M Y') }}</td>
                    <td class="py-4 px-4">{{ \Carbon\Carbon::parse($p->tgl_jatuh_tempo)->format('d M Y') }}</td>
                    <td class="py-4 px-4">{{ $p->tgl_kembali ? \Carbon\Carbon::parse($p->tgl_kembali)->format('d M Y') : '-' }}</td>
                    <td class="py-4 px-4">
                        @if($p->status === 'menunggu')
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">Menunggu</span>
                        @elseif($p->status === 'aktif')
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">Dipinjam</span>
                        @elseif($p->status === 'selesai')
                            <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Dikembalikan</span>
                        @else
                            <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">Ditolak</span>
                        @endif
                    </td>
                    <td class="py-4 px-4 font-semibold">
                        @php
                            $denda = \App\Models\Denda::where('peminjaman_id', $p->id)->first();
                        @endphp
                        @if($denda)
                            <span class="text-red-500 block">Rp {{ number_format($denda->total_denda, 0, ',', '.') }}</span>
                            @if($denda->status_bayar === 'lunas')
                                <span class="text-xs text-green-500 bg-green-50 px-2 py-0.5 rounded-md mt-1 inline-block">Lunas</span>
                            @else
                                <span class="text-xs text-red-500 bg-red-50 px-2 py-0.5 rounded-md mt-1 inline-block">Belum</span>
                            @endif
                        @else
                            <span class="text-gray-400 font-normal">-</span>
                        @endif
                    </td>
                    <td class="py-4 px-4 text-right">
                        @if($p->status === 'aktif')
                            <button onclick="alert('Silakan bawa buku fisik ke meja Pustakawan untuk memproses pengembalian ini.')" class="text-sm px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-xl shadow-sm transition-all hover:scale-105">Ajukan Pengembalian</button>
                        @elseif($p->status === 'menunggu')
                            <button onclick="alert('Silakan ambil buku fisik di meja Pustakawan.')" class="text-sm px-4 py-2 bg-gray-200 text-gray-700 rounded-xl shadow-sm transition-all hover:scale-105">Instruksi</button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-8 text-center text-gray-500">Belum ada riwayat peminjaman</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>
@endsection
