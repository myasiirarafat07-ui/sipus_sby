@extends('layouts.app')

@section('title', 'Detail Buku - ' . $buku->judul)

@section('content')
<div x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)" x-cloak x-show="show" x-transition:enter="transition ease-[cubic-bezier(0.34,1.56,0.64,1)] duration-1000" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6">
    <!-- Back Button -->
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-brand-blue-600 transition-colors mb-8">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali
    </a>

    <div class="glass-panel p-6 sm:p-10 rounded-[2rem] shadow-xl border border-white/60">
        <div class="flex flex-col md:flex-row gap-10">
            <!-- Cover Buku -->
            <div class="w-full md:w-1/3 shrink-0">
                <div class="aspect-[3/4] rounded-2xl overflow-hidden shadow-md border border-gray-200 bg-gray-50 flex items-center justify-center">
                    @if($buku->cover_image)
                        <img src="{{ Storage::url($buku->cover_image) }}" alt="{{ $buku->judul }}" class="w-full h-full object-contain">
                    @else
                        <div class="text-center p-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <p class="text-sm font-medium text-gray-400">Tidak ada gambar cover</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Detail Buku -->
            <div class="flex-1 flex flex-col">
                <div class="mb-2">
                    <span class="inline-block px-3 py-1 bg-brand-blue-50 text-brand-blue-700 text-xs font-bold uppercase tracking-wider rounded-lg border border-brand-blue-100">
                        {{ $buku->kategori }}
                    </span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 tracking-tight leading-tight mb-2">
                    {{ $buku->judul }}
                </h1>
                <p class="text-lg text-gray-500 font-medium mb-6">oleh <span class="text-gray-700 font-bold">{{ $buku->pengarang }}</span></p>
                
                <div class="grid grid-cols-2 gap-4 mb-8 bg-gray-50/50 p-5 rounded-2xl border border-gray-100">
                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Penerbit</p>
                        <p class="font-medium text-gray-800">{{ $buku->penerbit }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Tahun Terbit</p>
                        <p class="font-medium text-gray-800">{{ $buku->tahun_terbit }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">ISBN</p>
                        <p class="font-medium text-gray-800">{{ $buku->isbn }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Lokasi Rak</p>
                        <p class="font-medium text-gray-800">
                            @if($buku->lokasi_rak)
                                <span class="inline-flex items-center gap-1.5 text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-md border border-emerald-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $buku->lokasi_rak }}
                                </span>
                            @else
                                <span class="text-gray-400 italic">Belum ditentukan</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-800 mb-3">Sinopsis</h3>
                    <div class="prose prose-sm sm:prose text-gray-600 text-justify leading-relaxed">
                        @if($buku->sinopsis)
                            {!! nl2br(e($buku->sinopsis)) !!}
                        @else
                            <p class="italic text-gray-400">Sinopsis belum tersedia untuk buku ini.</p>
                        @endif
                    </div>
                </div>

                <div class="mt-auto border-t border-gray-100 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-semibold text-gray-500">Stok Tersedia:</span>
                        <span class="text-2xl font-black {{ $buku->stok > 0 ? 'text-emerald-500' : 'text-red-500' }}">
                            {{ $buku->stok }}
                        </span>
                    </div>

                    <div class="w-full sm:w-auto">
                        @if($buku->stok <= 0)
                            <button disabled class="w-full sm:w-auto px-8 py-4 rounded-xl font-bold bg-gray-100 text-gray-400 border border-gray-200 cursor-not-allowed text-center">
                                Stok Habis
                            </button>
                        @else
                            @auth
                                @if(auth()->user()->role === 'anggota')
                                    <form method="POST" action="{{ route('katalog.pinjam', $buku->id) }}">
                                        @csrf
                                        <button type="submit" class="w-full sm:w-auto px-8 py-4 rounded-xl font-bold btn-primary text-center shadow-lg focus:outline-none focus:ring-4 focus:ring-brand-blue-500/50 hover:scale-105 transition-transform">
                                            Ajukan Pinjam Buku
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('admin.buku') }}" class="btn-primary block text-center w-full sm:w-auto px-8 py-4 rounded-xl font-bold shadow-lg hover:scale-105 transition-transform">
                                        Kelola Buku
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn-primary block text-center w-full sm:w-auto px-8 py-4 rounded-xl font-bold shadow-lg hover:scale-105 transition-transform">
                                    Login untuk Pinjam
                                </a>
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
