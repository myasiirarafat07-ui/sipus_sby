@extends('layouts.app')

@section('title', 'Informasi Denda')

@section('content')
<div x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)" x-cloak x-show="show" x-transition:enter="transition ease-[cubic-bezier(0.34,1.56,0.64,1)] duration-1000" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Informasi Denda</h1>
        <p class="text-gray-500 mt-1">Daftar tagihan denda keterlambatan Anda.</p>
    </div>
</div>

<div class="glass p-6 rounded-3xl overflow-hidden shadow-lg">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="py-4 px-4 font-semibold text-gray-600">ID Pinjam</th>
                    <th class="py-4 px-4 font-semibold text-gray-600">Buku</th>
                    <th class="py-4 px-4 font-semibold text-gray-600">Terlambat</th>
                    <th class="py-4 px-4 font-semibold text-gray-600">Total Denda</th>
                    <th class="py-4 px-4 font-semibold text-gray-600">Status</th>
                    <th class="py-4 px-4 font-semibold text-gray-600">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dendas as $d)
                <tr class="border-b border-gray-100/50 hover:bg-white/50 hover:scale-[1.01] transition-all duration-300">
                    <td class="py-4 px-4 text-gray-600">#{{ $d->peminjaman_id }}</td>
                    <td class="py-4 px-4 font-medium text-gray-800">{{ $d->peminjaman->buku->judul ?? 'Unknown' }}</td>
                    <td class="py-4 px-4 text-red-500 font-semibold">{{ $d->hari_terlambat }} Hari</td>
                    <td class="py-4 px-4 font-bold text-gray-800">Rp {{ number_format($d->total_denda, 0, ',', '.') }}</td>
                    <td class="py-4 px-4">
                        @if($d->status_bayar === 'lunas')
                            <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-lg border border-green-200">Lunas</span>
                        @else
                            <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-lg border border-red-200">Belum Bayar</span>
                        @endif
                    </td>
                    <td class="py-4 px-4 text-gray-600">
                        {{ $d->keterangan ?? '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-8 text-center text-gray-500">Anda tidak memiliki tagihan denda.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>
@endsection