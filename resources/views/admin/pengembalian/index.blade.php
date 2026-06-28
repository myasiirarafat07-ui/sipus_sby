@extends('layouts.app')

@section('title', 'Data Pengembalian')

@section('content')
<div x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)" x-cloak x-show="show" x-transition:enter="transition ease-[cubic-bezier(0.34,1.56,0.64,1)] duration-1000" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Data Pengembalian</h1>
        <p class="text-gray-500 mt-1">Konfirmasi pengembalian buku</p>
    </div>
</div>

<div class="glass p-6 rounded-3xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="py-4 px-4 font-semibold text-gray-600">ID Pinjam</th>
                    <th class="py-4 px-4 font-semibold text-gray-600">Anggota</th>
                    <th class="py-4 px-4 font-semibold text-gray-600">Buku</th>
                    <th class="py-4 px-4 font-semibold text-gray-600">Tgl Pinjam</th>
                    <th class="py-4 px-4 font-semibold text-gray-600">Jatuh Tempo</th>
                    <th class="py-4 px-4 font-semibold text-gray-600">Status</th>
                    <th class="py-4 px-4 font-semibold text-gray-600 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjamans as $p)
                <tr class="border-b border-gray-100/50 hover:bg-white/50 transition-colors">
                    <td class="py-4 px-4 text-gray-600">#{{ $p->id }}</td>
                    <td class="py-4 px-4">{{ $p->anggota->nama_lengkap }}</td>
                    <td class="py-4 px-4 text-gray-800 font-medium">{{ $p->buku->judul }}</td>
                    <td class="py-4 px-4">{{ \Carbon\Carbon::parse($p->tgl_pinjam)->format('d M Y') }}</td>
                    <td class="py-4 px-4">{{ \Carbon\Carbon::parse($p->tgl_jatuh_tempo)->format('d M Y') }}</td>
                    <td class="py-4 px-4">
                        @if($p->status === 'menunggu')
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">Menunggu</span>
                        @elseif($p->status === 'disetujui')
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">Disetujui</span>
                        @elseif($p->status === 'selesai')
                            <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Selesai</span>
                        @else
                            <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">Ditolak</span>
                        @endif
                    </td>
                    <td class="py-4 px-4 text-right flex justify-end gap-2">
                        @if($p->status === 'disetujui')
                            <form method="POST" action="{{ route('admin.pengembalian.confirm', $p->id) }}">
                                @csrf
                                <button type="submit" class="text-sm px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl shadow-sm transition">Konfirmasi Pengembalian</button>
                            </form>
                        @else
                            <span class="text-gray-400 text-sm">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-8 text-center text-gray-500">Belum ada data peminjaman</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>
@endsection
