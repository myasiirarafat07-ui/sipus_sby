@extends('layouts.app')

@section('title', 'Katalog Buku')

@section('content')
<div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6" x-data="{ showHeader: false }" x-init="setTimeout(() => showHeader = true, 50)" x-show="showHeader" x-transition:enter="transition ease-[cubic-bezier(0.34,1.56,0.64,1)] duration-700" x-transition:enter-start="opacity-0 -translate-x-8" x-transition:enter-end="opacity-100 translate-x-0">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">Katalog Buku</h1>
        @php
            $nama = 'Tamu';
            if(auth()->check()) {
                $nama = auth()->user()->role === 'anggota' && isset($anggota) ? ($anggota->nama_lengkap ?? auth()->user()->username) : auth()->user()->username;
            }
        @endphp
        <p class="text-gray-500 mt-1 font-medium">Halo, <span class="text-brand-blue-600">{{ $nama }}</span>! Temukan buku favoritmu di sini.</p>
    </div>
    
    <form method="GET" action="{{ route('katalog') }}" class="w-full md:w-auto relative group">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul atau pengarang..." class="w-full md:w-80 px-5 py-3.5 pl-12 rounded-2xl glass-panel focus:bg-white focus:ring-4 focus:ring-brand-blue-500/20 focus:border-brand-blue-500 outline-none transition-all duration-300 shadow-sm font-medium text-gray-700 placeholder-gray-400">
        <div class="absolute left-4 top-4 text-gray-400 transition-colors group-focus-within:text-brand-blue-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </form>
</div>

@if($bukus->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" x-data x-init="
        const cards = document.querySelectorAll('.book-card');
        cards.forEach((card, i) => {
            setTimeout(() => {
                card.classList.remove('opacity-0', 'translate-y-8');
            }, 100 + (i * 75));
        });
    ">
        @foreach($bukus as $buku)
            <div class="book-card opacity-0 translate-y-8 transition-all duration-700 ease-[cubic-bezier(0.34,1.56,0.64,1)] glass-panel p-5 rounded-[1.5rem] flex flex-col justify-between hover:-translate-y-1.5 hover:shadow-xl group border border-white/60">
                <div>
                    <div class="bg-gradient-to-br from-brand-blue-50 to-gray-100 w-full aspect-[3/4] rounded-2xl mb-5 flex items-center justify-center shadow-inner overflow-hidden relative border border-gray-200/50 group-hover:shadow-md transition-shadow p-4">
                        <div class="absolute top-0 right-0 w-16 h-16 bg-brand-yellow-400/20 rounded-bl-[3rem]"></div>
                        <span class="text-brand-blue-300 font-semibold absolute rotate-[-10deg] text-xl opacity-30">{{ $buku->kategori }}</span>
                        <!-- Sampul Default -->
                        <div class="w-12 h-16 border-2 border-brand-blue-200 rounded flex flex-col group-hover:scale-110 transition-transform duration-300">
                            <div class="h-2 border-b-2 border-brand-blue-200 bg-brand-blue-100"></div>
                            <div class="flex-1 flex items-center justify-center text-brand-blue-200 text-xs font-bold">P</div>
                        </div>
                    </div>
                    <h3 class="font-bold text-lg text-gray-800 line-clamp-2 leading-tight mb-1.5 group-hover:text-brand-blue-600 transition-colors">
                        {{ $buku->judul }}
                    </h3>
                    <p class="text-sm text-gray-500 mb-4 font-medium">{{ $buku->pengarang }}</p>
                    <div class="flex flex-wrap items-center gap-2 mb-5">
                        <span class="px-3 py-1 bg-brand-blue-50 text-brand-blue-700 text-xs font-bold rounded-lg border border-brand-blue-100">Stok: {{ $buku->stok }}</span>
                        <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-lg">{{ $buku->tahun_terbit }}</span>
                        <span class="px-3 py-1 bg-brand-yellow-50 text-brand-yellow-700 text-xs font-medium rounded-lg border border-brand-yellow-100">{{ $buku->kategori }}</span>
                    </div>
                </div>
                
                @if($buku->stok <= 0)
                    <button disabled class="w-full py-3 rounded-xl font-bold bg-gray-100 text-gray-400 border border-gray-200 cursor-not-allowed text-sm">Stok Habis</button>
                @else
                    @auth
                        @if(auth()->user()->role === 'anggota')
                            <form method="POST" action="{{ route('katalog.pinjam', $buku->id) }}">
                                @csrf
                                <button type="submit" class="w-full py-3 rounded-xl font-bold transition-all shadow-sm focus:outline-none focus:ring-4 focus:ring-brand-blue-500/50 btn-primary text-sm">
                                    Pinjam Buku
                                </button>
                            </form>
                        @else
                            <a href="{{ route('admin.buku') }}" class="btn-primary block text-center w-full py-3 rounded-xl font-bold shadow-sm text-sm">Kelola Buku</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn-primary block text-center w-full py-3 rounded-xl font-bold shadow-sm text-sm">Login untuk Pinjam</a>
                    @endauth
                @endif
            </div>
        @endforeach
    </div>
@else
    <div class="glass-panel rounded-3xl p-12 text-center flex flex-col items-center justify-center min-h-[40vh]" x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)" x-show="show" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 scale-95">
        <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6 border border-gray-200 shadow-inner text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
        </div>
        <h3 class="text-2xl font-bold text-gray-800 mb-2">Buku Tidak Ditemukan</h3>
        <p class="text-gray-500 max-w-md mx-auto mb-6">Maaf, kami tidak dapat menemukan buku dengan kata kunci "{{ request('q') }}". Coba gunakan kata kunci lain.</p>
        <a href="{{ route('katalog') }}" class="btn-primary px-6 py-3 rounded-xl inline-flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
            Hapus Pencarian
        </a>
    </div>
@endif
@endsection
