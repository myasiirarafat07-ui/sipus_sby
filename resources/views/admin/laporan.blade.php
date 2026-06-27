@extends('layouts.app')

@section('title', 'Cetak Laporan')

@section('content')
<div x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)" x-cloak x-show="show" x-transition:enter="transition ease-[cubic-bezier(0.34,1.56,0.64,1)] duration-1000" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
<div class="max-w-3xl mx-auto">
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-gray-800">Cetak Laporan Operasional</h1>
        <p class="text-gray-500 mt-1">Buat laporan peminjaman atau denda berdasarkan periode.</p>
    </div>

    <div class="glass-panel p-8 rounded-3xl shadow-xl border border-white/60">
        <form action="{{ route('admin.laporan.cetak') }}" method="GET" target="_blank" class="space-y-6">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Laporan</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="cursor-pointer">
                        <input type="radio" name="jenis_laporan" value="peminjaman" class="peer sr-only" checked>
                        <div class="p-4 rounded-2xl border-2 border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all text-center bg-white/50 hover:bg-white shadow-sm">
                            <span class="font-bold text-gray-800 peer-checked:text-blue-700">Laporan Peminjaman</span>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="jenis_laporan" value="denda" class="peer sr-only">
                        <div class="p-4 rounded-2xl border-2 border-gray-200 peer-checked:border-red-500 peer-checked:bg-red-50 transition-all text-center bg-white/50 hover:bg-white shadow-sm">
                            <span class="font-bold text-gray-800 peer-checked:text-red-700">Laporan Denda</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                    <input type="date" name="tgl_mulai" class="w-full px-4 py-3 rounded-2xl border border-gray-200 bg-white/50 focus:bg-white focus:ring-2 focus:ring-blue-500/50 outline-none transition shadow-inner" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                    <input type="date" name="tgl_akhir" class="w-full px-4 py-3 rounded-2xl border border-gray-200 bg-white/50 focus:bg-white focus:ring-2 focus:ring-blue-500/50 outline-none transition shadow-inner" required>
                </div>
            </div>

            <button type="submit" class="w-full py-4 rounded-2xl btn-primary text-lg mt-4 shadow-lg hover:shadow-xl hover:-translate-y-1">
                Cetak Laporan Dokumen
            </button>
        </form>
    </div>
</div>
</div>
@endsection
