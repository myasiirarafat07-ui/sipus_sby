@extends('layouts.app')

@section('title', 'Selamat Datang')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
        [x-cloak] { display: none !important; }
        
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        /* Kustomisasi Swiper */
        .popular-swiper .swiper-slide {
            width: 260px;
            transition: all 0.3s ease;
        }
        @media (min-width: 768px) {
            .popular-swiper .swiper-slide {
                width: 320px;
            }
        }
        
        .popular-swiper .swiper-slide-active {
            z-index: 10 !important;
        }
        
        /* Pagination Swiper */
        .swiper-pagination-bullet {
            background-color: #94a3b8;
            transition: all 0.3s;
        }
        .swiper-pagination-bullet-active {
            background-color: #2563eb;
            width: 24px;
            border-radius: 8px;
        }
    </style>

<div class="pt-8 sm:pt-12 flex flex-col gap-16 md:gap-20" x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)">
    
    <!-- Bagian Hero -->
    <div class="glass-panel px-6 pt-10 pb-12 md:px-12 md:pt-16 md:pb-16 rounded-[2.5rem] max-w-4xl w-full mx-auto text-center" x-cloak x-show="show" x-transition:enter="transition ease-[cubic-bezier(0.34,1.56,0.64,1)] duration-1000" x-transition:enter-start="opacity-0 translate-y-12 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
        
        <!-- Logo -->
        <img src="{{ asset('images/City_of_Surabaya_Logo.png') }}" alt="Logo Kota Surabaya" class="w-32 h-auto mx-auto mb-8 drop-shadow-xl hover:scale-110 transition-transform duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)]">
        
        <!-- Tagline -->
        <div class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-brand-blue-50 border border-brand-blue-100 text-brand-blue-700 font-bold text-sm mb-6 uppercase tracking-[0.2em] shadow-sm">
            Sistem Informasi Perpustakaan
        </div>
        
        <!-- Judul Utama -->
        <h1 class="text-6xl md:text-8xl font-black text-transparent bg-clip-text bg-gradient-to-r from-brand-blue-700 to-brand-blue-900 mb-2 tracking-tighter drop-shadow-sm">
            SIPUS
        </h1>
        
        <!-- Sub-judul -->
        <h2 class="text-2xl md:text-3xl font-extrabold text-gray-700 mb-8 tracking-tight">
            Perpustakaan Umum Kota Surabaya
        </h2>
        <p class="text-xl text-gray-600 mb-10 max-w-2xl mx-auto leading-relaxed">
            Jelajahi dunia pengetahuan melalui ribuan koleksi buku kami. Nikmati kemudahan meminjam buku secara digital dengan pengalaman yang cepat dan modern.
        </p>

        <div class="flex flex-col sm:flex-row justify-center items-center gap-6 mb-12">
            <div class="glass px-8 py-5 rounded-3xl flex flex-col items-center min-w-[200px] hover:-translate-y-1 transition-all duration-300 hover:shadow-xl border-t border-white/80 cursor-default group">
                <span class="text-5xl font-extrabold text-brand-blue-600 mb-1 group-hover:scale-110 transition-transform duration-300">{{ $totalBuku }}</span>
                <span class="text-gray-500 font-medium text-sm tracking-wide uppercase">Koleksi Buku</span>
            </div>
            <div class="glass px-8 py-5 rounded-3xl flex flex-col items-center min-w-[200px] hover:-translate-y-1 transition-all duration-300 hover:shadow-xl border-t border-white/80 cursor-default group">
                <span class="text-5xl font-extrabold text-brand-yellow-500 mb-1 group-hover:scale-110 transition-transform duration-300">{{ $totalAnggota }}</span>
                <span class="text-gray-500 font-medium text-sm tracking-wide uppercase">Anggota Aktif</span>
            </div>
        </div>

        <div>
            @auth
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('katalog') }}" class="btn-primary inline-flex items-center justify-center px-8 py-4 text-lg focus:outline-none focus-visible:ring-4 focus-visible:ring-brand-blue-500/50">
                    Masuk ke Dashboard &rarr;
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-primary inline-flex items-center justify-center px-8 py-4 text-lg focus:outline-none focus-visible:ring-4 focus-visible:ring-brand-blue-500/50">
                    Mulai Sekarang &rarr;
                </a>
            @endauth
        </div>

    </div>

    <!-- Pencarian OPAC -->
    <div id="pencarian" class="w-full max-w-4xl mx-auto px-4 scroll-mt-24" x-cloak x-show="show" x-transition:enter="transition ease-[cubic-bezier(0.34,1.56,0.64,1)] duration-1000 delay-200" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="glass-panel p-6 md:p-8 rounded-[2rem] border border-white/60 shadow-xl flex flex-col md:flex-row items-center gap-6 relative overflow-hidden group">
            <!-- Hiasan Background -->
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-brand-blue-400/10 rounded-full blur-2xl group-hover:bg-brand-blue-400/20 transition-colors duration-500 pointer-events-none"></div>
            
            <div class="md:w-1/3 text-left w-full">
                <h3 class="text-2xl font-bold text-gray-800">Cari Buku</h3>
                <p class="text-gray-500 text-sm mt-1">Temukan buku yang Anda butuhkan di katalog kami.</p>
            </div>
            <form action="{{ route('katalog') }}" method="GET" class="relative w-full md:w-2/3">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul, pengarang, atau ISBN..." class="w-full px-6 py-4 rounded-full border border-gray-200 bg-white/80 focus:bg-white focus:ring-4 focus:ring-brand-blue-500/20 focus:border-brand-blue-500 transition-all shadow-md hover:shadow-lg outline-none pl-14 text-gray-700 font-medium z-10 relative">
                <div class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 z-20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-brand-blue-600 hover:bg-brand-blue-700 text-white px-6 py-2.5 rounded-full transition-all duration-300 font-bold shadow-md hover:shadow-lg z-20 hover:scale-105 active:scale-95">
                    Cari
                </button>
            </form>
        </div>
    </div>

    @if($popularBooks && $popularBooks->count() > 0)
    <!-- Buku Populer -->
    <div id="koleksi-populer" class="w-full max-w-6xl mx-auto px-4 scroll-mt-24 mt-8" x-cloak x-show="show" x-transition:enter="transition ease-[cubic-bezier(0.34,1.56,0.64,1)] duration-1000 delay-300" x-transition:enter-start="opacity-0 translate-y-12" x-transition:enter-end="opacity-100 translate-y-0">
        
        <div class="flex flex-col sm:flex-row justify-between items-end mb-8 gap-4">
            <div>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800 tracking-tight relative inline-block">
                    Paling Banyak Dipinjam
                </h2>
                <p class="text-gray-500 font-medium mt-3">Eksplorasi koleksi terpopuler pilihan pembaca.</p>
            </div>
        </div>

        <!-- Slider Coverflow -->
        <div class="relative max-w-7xl mx-auto mt-6">
            <div class="swiper popular-swiper w-full pt-8 pb-16">
                <div class="swiper-wrapper pb-10">
                    
                    @php
                        // Gandakan data buku agar loop Swiper berjalan mulus
                        $loopBooks = $popularBooks;
                        if($popularBooks->count() > 0 && $popularBooks->count() <= 5) {
                            $loopBooks = $popularBooks->concat($popularBooks)->concat($popularBooks);
                        }
                    @endphp
                    
                    @foreach($loopBooks as $i => $buku)
                    @php $realIndex = $i % $popularBooks->count(); @endphp
                    <div class="swiper-slide relative group">
                        <a href="{{ route('buku.detail', $buku->id) }}" class="block w-full h-[380px] md:h-[450px] relative glass-panel rounded-[2.5rem] p-4 md:p-5 shadow-xl border border-white/60 group-hover:border-brand-blue-300 transition-colors duration-300 overflow-hidden">
                            
                            <!-- Peringkat Buku -->
                            <div class="absolute top-0 left-0 z-20 w-16 h-16 bg-gradient-to-br {{ $realIndex === 0 ? 'from-brand-yellow-300 to-brand-yellow-500 text-yellow-900' : ($realIndex === 1 ? 'from-gray-200 to-gray-300 text-gray-700' : ($realIndex === 2 ? 'from-amber-500 to-amber-700 text-white' : 'from-brand-blue-100 to-brand-blue-200 text-brand-blue-800')) }} rounded-br-3xl flex items-center justify-center font-black text-xl shadow-md border-b-2 border-r-2 border-white/50 transition-colors duration-300">
                                #{{ $realIndex + 1 }}
                            </div>
                            
                            <!-- Sampul Buku -->
                            <div class="w-full h-3/5 bg-white/50 backdrop-blur-sm rounded-[1.5rem] p-3 mb-5 relative overflow-hidden flex items-center justify-center border border-white/60 group-hover:bg-brand-blue-50/50 transition-colors shadow-inner mt-2">
                                @if($buku->cover_image)
                                    <img src="{{ Storage::url($buku->cover_image) }}" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-500 drop-shadow-md">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-brand-blue-500 to-brand-blue-700 flex items-center justify-center rounded-xl p-2 shadow-inner border border-white/20">
                                        <span class="text-white text-xs font-bold text-center leading-tight">{{ $buku->judul }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Info Buku -->
                            <div class="text-center px-2 flex flex-col h-[calc(40%-1.75rem)] relative z-10">
                                <h3 class="text-lg md:text-xl font-extrabold text-gray-800 line-clamp-2 leading-tight mb-1 group-hover:text-brand-blue-600 transition-colors">{{ $buku->judul }}</h3>
                                <p class="text-xs md:text-sm text-gray-500 font-medium mb-3 truncate">{{ $buku->pengarang }}</p>
                                
                                <div class="mt-auto pb-2">
                                    <span class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-gray-50 group-hover:bg-brand-blue-50 text-gray-700 group-hover:text-brand-blue-700 text-xs font-bold rounded-xl border border-gray-200 group-hover:border-brand-blue-200 transition-colors shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-brand-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                        {{ $buku->peminjamans_count }} Dipinjam
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
                <!-- Pagination Dihapus Karena Infinite Loop 15 Data -->
            </div>
        </div>
    </div>

    <!-- Inisialisasi Swiper -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(() => {
                const swiperEl = document.querySelector('.popular-swiper');
                if (!swiperEl) return;

                const popularSwiper = new Swiper(swiperEl, {
                    effect: 'coverflow',
                    grabCursor: true,
                    centeredSlides: true,
                    slidesPerView: 'auto',
                    initialSlide: 5,
                    loop: true,
                    observer: true,
                    observeParents: true,    
                    mousewheel: {
                        forceToAxis: true,
                        sensitivity: 1,
                    },
                    keyboard: {
                        enabled: true,
                    },
                    coverflowEffect: {
                        rotate: 15,
                        stretch: 0,
                        depth: 300,
                        modifier: 1,
                        slideShadows: false,
                    },
                    autoplay: {
                        delay: 3000,
                        disableOnInteraction: false,
                    },
                    speed: 600,
                });

                // Sesuaikan ukuran Swiper setelah inisialisasi selesai
                const resizeObserver = new ResizeObserver(() => {
                    popularSwiper.update();
                });
                resizeObserver.observe(swiperEl);
            }, 150);
        });
    </script>
    @endif

    <!-- Bagian Koleksi Baru -->
    <div id="koleksi-baru" class="w-full max-w-6xl mx-auto px-4 scroll-mt-24 -mb-8" x-cloak x-show="show" x-transition:enter="transition ease-[cubic-bezier(0.34,1.56,0.64,1)] duration-1000 delay-300" x-transition:enter-start="opacity-0 translate-y-12" x-transition:enter-end="opacity-100 translate-y-0"
         x-data="{ 
             activeInterval: null,
             scrollSpeed: 304,
             isMouseOver: false,
             init() {
                 this.$nextTick(() => {
                     const el = this.$refs.slider;
                     if (el) {
                         // Atur posisi scroll awal ke Set 2
                         const setWidth = ({{ $latestBooks->count() > 0 ? $latestBooks->count() : 6 }}) * this.scrollSpeed;
                         el.scrollLeft = setWidth;
                     }
                     this.startAutoScroll();
                 });
             },
             startAutoScroll() {
                 this.activeInterval = setInterval(() => {
                     if (this.isMouseOver) return;
                     const el = this.$refs.slider;
                     if (el) {
                         // Geser scroll secara bertahap
                         el.scrollLeft += 1;
                     }
                 }, 20); // Interval pergeseran
             },
             stopAutoScroll() {
                 if (this.activeInterval) {
                     clearInterval(this.activeInterval);
                 }
             },
             handleScroll() {
                 const el = this.$refs.slider;
                 if (!el) return;
                 
                 const count = {{ $latestBooks->count() > 0 ? $latestBooks->count() : 6 }};
                 const setWidth = count * this.scrollSpeed;
                 
                 // Lakukan perulangan geser tak terbatas (looping scroll)
                 if (el.scrollLeft >= setWidth * 2) {
                     el.scrollLeft -= setWidth;
                 }
                 else if (el.scrollLeft <= this.scrollSpeed) {
                     el.scrollLeft += setWidth;
                 }
             }
         }"
         @mouseenter="isMouseOver = true"
         @mouseleave="isMouseOver = false">
         
        <div class="flex flex-col sm:flex-row justify-between items-center mb-8 gap-4">
            <div class="text-center sm:text-left">
                <h2 class="text-4xl font-extrabold text-gray-800 tracking-tight">Koleksi Baru</h2>
                <p class="text-gray-500 font-medium mt-1">Gunakan kursor untuk menggeser katalog secara melingkar tanpa batas.</p>
            </div>
            
            <div>
                @auth
                    @if(auth()->user()->role === 'anggota')
                        <a href="{{ route('katalog') }}" class="text-sm font-bold text-brand-blue-600 hover:text-brand-blue-800 flex items-center gap-2 group transition-colors">
                            Lihat Semua Katalog 
                            <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="text-sm font-bold text-brand-blue-600 hover:text-brand-blue-800 flex items-center gap-2 group transition-colors">
                        Lihat Semua Katalog 
                        <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                    </a>
                @endauth
            </div>
        </div>

        @php
            $booksToDisplay = $latestBooks;
        @endphp

        <!-- Slider Wrapper -->
        <div x-ref="slider" @scroll="handleScroll()" class="flex gap-6 overflow-x-auto no-scrollbar pt-6 pb-8 px-4 -mx-4">
            
            <!-- Set 1 (Left Buffer) -->
            @foreach($booksToDisplay as $buku)
                @php
                    $gradientClass = 'from-brand-blue-600 to-brand-blue-900';
                    $iconColor = 'text-brand-yellow-400';
                    $badgeClass = 'text-brand-yellow-400 bg-brand-yellow-400/10 border-brand-yellow-400/20';

                    $lowerKategori = strtolower($buku->kategori);
                    if (str_contains($lowerKategori, 'novel') || str_contains($lowerKategori, 'fiksi')) {
                        if (str_contains($lowerKategori, 'sejarah')) {
                            $gradientClass = 'from-amber-700 to-red-900';
                            $iconColor = 'text-amber-300';
                            $badgeClass = 'text-amber-300 bg-amber-300/10 border-amber-300/20';
                        } else {
                            $gradientClass = 'from-indigo-600 to-brand-blue-900';
                            $iconColor = 'text-brand-yellow-400';
                            $badgeClass = 'text-brand-yellow-400 bg-brand-yellow-400/10 border-brand-yellow-400/20';
                        }
                    } elseif (str_contains($lowerKategori, 'teknologi') || str_contains($lowerKategori, 'komputer') || str_contains($lowerKategori, 'web') || str_contains($lowerKategori, 'pemrograman')) {
                        $gradientClass = 'from-teal-600 to-blue-800';
                        $iconColor = 'text-teal-300';
                        $badgeClass = 'text-teal-300 bg-teal-300/10 border-teal-300/20';
                    } else {
                        $gradientClass = 'from-purple-600 to-violet-900';
                        $iconColor = 'text-purple-300';
                        $badgeClass = 'text-purple-300 bg-purple-300/10 border-purple-300/20';
                    }
                @endphp

                <div class="glass-panel p-5 rounded-[1.5rem] flex flex-col justify-between hover:-translate-y-2 hover:shadow-2xl transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] group border border-white/60 relative w-[280px] shrink-0 will-change-transform transform-gpu">
                    <div>
                        <div class="bg-gradient-to-br from-brand-blue-50 to-gray-100 w-full aspect-[3/4] rounded-2xl mb-5 flex items-center justify-center shadow-inner overflow-hidden relative border border-gray-200/50 group-hover:shadow-md transition-shadow p-4">
                            @if($buku->cover_image)
                                <img src="{{ Storage::url($buku->cover_image) }}" alt="{{ $buku->judul }}" class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500 drop-shadow-md">
                            @else
                                <div class="w-full h-full bg-gradient-to-br {{ $gradientClass }} flex flex-col justify-between p-4 rounded-xl shadow-md border border-white/10 group-hover:scale-105 transition-transform duration-500">
                                    <div class="flex justify-between items-start">
                                        <span class="text-[9px] font-extrabold uppercase tracking-widest px-2 py-0.5 rounded-full border {{ $badgeClass }}">
                                            {{ $buku->kategori }}
                                        </span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ $iconColor }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    </div>
                                    <div class="my-auto text-center py-4">
                                        <h4 class="font-extrabold text-white text-sm leading-snug tracking-tight px-1 drop-shadow-sm line-clamp-3">{{ $buku->judul }}</h4>
                                        <p class="text-[10px] text-white/70 mt-1.5 font-semibold">{{ $buku->pengarang }}</p>
                                    </div>
                                    <div class="border-t border-white/10 pt-2 flex justify-between items-center text-[9px] text-white/50 font-bold">
                                        <span>SIPUS SBY</span>
                                        <span>{{ $buku->tahun_terbit }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <p class="text-xs font-bold text-gray-400 tracking-wider uppercase mb-1">{{ $buku->pengarang }}</p>
                        <h3 class="font-extrabold text-lg text-gray-800 leading-snug mb-3 group-hover:text-brand-blue-600 transition-colors line-clamp-2">
                            <a href="{{ route('buku.detail', $buku->id) }}">{{ $buku->judul }}</a>
                        </h3>
                        
                        <div class="flex flex-wrap items-center gap-2 mb-5">
                            <span class="px-2.5 py-1 bg-brand-blue-50 text-brand-blue-700 text-[10px] font-extrabold rounded-lg border border-brand-blue-100">
                                Stok: {{ $buku->stok }}
                            </span>
                            <span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-lg">
                                {{ $buku->tahun_terbit }}
                            </span>
                        </div>
                    </div>

                    <div>
                        @if($buku->stok <= 0)
                            <button disabled class="w-full py-3.5 rounded-xl font-bold bg-gray-100 text-gray-400 border border-gray-200 cursor-not-allowed text-center text-sm">
                                Stok Habis
                            </button>
                        @else
                            @auth
                                @if(auth()->user()->role === 'anggota')
                                    <form method="POST" action="{{ $buku instanceof \App\Models\Buku ? route('katalog.pinjam', $buku->id) : route('katalog') }}">
                                        @csrf
                                        <button type="submit" class="w-full py-3.5 rounded-xl font-bold btn-primary text-center text-sm shadow-sm focus:outline-none focus:ring-4 focus:ring-brand-blue-500/50">
                                            Pinjam Buku
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('admin.buku') }}" class="btn-primary block text-center w-full py-3.5 rounded-xl font-bold text-sm shadow-sm">
                                        Kelola Buku
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn-primary block text-center w-full py-3.5 rounded-xl font-bold text-sm shadow-sm">
                                    Login untuk Pinjam
                                </a>
                            @endauth
                        @endif
                    </div>
                </div>
            @endforeach

            <!-- Set 2 (Middle Original) -->
            @foreach($booksToDisplay as $buku)
                @php
                    $gradientClass = 'from-brand-blue-600 to-brand-blue-900';
                    $iconColor = 'text-brand-yellow-400';
                    $badgeClass = 'text-brand-yellow-400 bg-brand-yellow-400/10 border-brand-yellow-400/20';

                    $lowerKategori = strtolower($buku->kategori);
                    if (str_contains($lowerKategori, 'novel') || str_contains($lowerKategori, 'fiksi')) {
                        if (str_contains($lowerKategori, 'sejarah')) {
                            $gradientClass = 'from-amber-700 to-red-900';
                            $iconColor = 'text-amber-300';
                            $badgeClass = 'text-amber-300 bg-amber-300/10 border-amber-300/20';
                        } else {
                            $gradientClass = 'from-indigo-600 to-brand-blue-900';
                            $iconColor = 'text-brand-yellow-400';
                            $badgeClass = 'text-brand-yellow-400 bg-brand-yellow-400/10 border-brand-yellow-400/20';
                        }
                    } elseif (str_contains($lowerKategori, 'teknologi') || str_contains($lowerKategori, 'komputer') || str_contains($lowerKategori, 'web') || str_contains($lowerKategori, 'pemrograman')) {
                        $gradientClass = 'from-teal-600 to-blue-800';
                        $iconColor = 'text-teal-300';
                        $badgeClass = 'text-teal-300 bg-teal-300/10 border-teal-300/20';
                    } else {
                        $gradientClass = 'from-purple-600 to-violet-900';
                        $iconColor = 'text-purple-300';
                        $badgeClass = 'text-purple-300 bg-purple-300/10 border-purple-300/20';
                    }
                @endphp

                <div class="glass-panel p-5 rounded-[1.5rem] flex flex-col justify-between hover:-translate-y-2 hover:shadow-2xl transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] group border border-white/60 relative w-[280px] shrink-0 will-change-transform transform-gpu">
                    <div>
                        <div class="bg-gradient-to-br from-brand-blue-50 to-gray-100 w-full aspect-[3/4] rounded-2xl mb-5 flex items-center justify-center shadow-inner overflow-hidden relative border border-gray-200/50 group-hover:shadow-md transition-shadow p-4">
                            @if($buku->cover_image)
                                <img src="{{ Storage::url($buku->cover_image) }}" alt="{{ $buku->judul }}" class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500 drop-shadow-md">
                            @else
                                <div class="w-full h-full bg-gradient-to-br {{ $gradientClass }} flex flex-col justify-between p-4 rounded-xl shadow-md border border-white/10 group-hover:scale-105 transition-transform duration-500">
                                    <div class="flex justify-between items-start">
                                        <span class="text-[9px] font-extrabold uppercase tracking-widest px-2 py-0.5 rounded-full border {{ $badgeClass }}">
                                            {{ $buku->kategori }}
                                        </span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ $iconColor }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    </div>
                                    <div class="my-auto text-center py-4">
                                        <h4 class="font-extrabold text-white text-sm leading-snug tracking-tight px-1 drop-shadow-sm line-clamp-3">{{ $buku->judul }}</h4>
                                        <p class="text-[10px] text-white/70 mt-1.5 font-semibold">{{ $buku->pengarang }}</p>
                                    </div>
                                    <div class="border-t border-white/10 pt-2 flex justify-between items-center text-[9px] text-white/50 font-bold">
                                        <span>SIPUS SBY</span>
                                        <span>{{ $buku->tahun_terbit }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <p class="text-xs font-bold text-gray-400 tracking-wider uppercase mb-1">{{ $buku->pengarang }}</p>
                        <h3 class="font-extrabold text-lg text-gray-800 leading-snug mb-3 group-hover:text-brand-blue-600 transition-colors line-clamp-2">
                            <a href="{{ route('buku.detail', $buku->id) }}">{{ $buku->judul }}</a>
                        </h3>
                        
                        <div class="flex flex-wrap items-center gap-2 mb-5">
                            <span class="px-2.5 py-1 bg-brand-blue-50 text-brand-blue-700 text-[10px] font-extrabold rounded-lg border border-brand-blue-100">
                                Stok: {{ $buku->stok }}
                            </span>
                            <span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-lg">
                                {{ $buku->tahun_terbit }}
                            </span>
                        </div>
                    </div>

                    <div>
                        @if($buku->stok <= 0)
                            <button disabled class="w-full py-3.5 rounded-xl font-bold bg-gray-100 text-gray-400 border border-gray-200 cursor-not-allowed text-center text-sm">
                                Stok Habis
                            </button>
                        @else
                            @auth
                                @if(auth()->user()->role === 'anggota')
                                    <form method="POST" action="{{ $buku instanceof \App\Models\Buku ? route('katalog.pinjam', $buku->id) : route('katalog') }}">
                                        @csrf
                                        <button type="submit" class="w-full py-3.5 rounded-xl font-bold btn-primary text-center text-sm shadow-sm focus:outline-none focus:ring-4 focus:ring-brand-blue-500/50">
                                            Pinjam Buku
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('admin.buku') }}" class="btn-primary block text-center w-full py-3.5 rounded-xl font-bold text-sm shadow-sm">
                                        Kelola Buku
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn-primary block text-center w-full py-3.5 rounded-xl font-bold text-sm shadow-sm">
                                    Login untuk Pinjam
                                </a>
                            @endauth
                        @endif
                    </div>
                </div>
            @endforeach

            <!-- Set 3 (Right Buffer) -->
            @foreach($booksToDisplay as $buku)
                @php
                    $gradientClass = 'from-brand-blue-600 to-brand-blue-900';
                    $iconColor = 'text-brand-yellow-400';
                    $badgeClass = 'text-brand-yellow-400 bg-brand-yellow-400/10 border-brand-yellow-400/20';

                    $lowerKategori = strtolower($buku->kategori);
                    if (str_contains($lowerKategori, 'novel') || str_contains($lowerKategori, 'fiksi')) {
                        if (str_contains($lowerKategori, 'sejarah')) {
                            $gradientClass = 'from-amber-700 to-red-900';
                            $iconColor = 'text-amber-300';
                            $badgeClass = 'text-amber-300 bg-amber-300/10 border-amber-300/20';
                        } else {
                            $gradientClass = 'from-indigo-600 to-brand-blue-900';
                            $iconColor = 'text-brand-yellow-400';
                            $badgeClass = 'text-brand-yellow-400 bg-brand-yellow-400/10 border-brand-yellow-400/20';
                        }
                    } elseif (str_contains($lowerKategori, 'teknologi') || str_contains($lowerKategori, 'komputer') || str_contains($lowerKategori, 'web') || str_contains($lowerKategori, 'pemrograman')) {
                        $gradientClass = 'from-teal-600 to-blue-800';
                        $iconColor = 'text-teal-300';
                        $badgeClass = 'text-teal-300 bg-teal-300/10 border-teal-300/20';
                    } else {
                        $gradientClass = 'from-purple-600 to-violet-900';
                        $iconColor = 'text-purple-300';
                        $badgeClass = 'text-purple-300 bg-purple-300/10 border-purple-300/20';
                    }
                @endphp

                <div class="glass-panel p-5 rounded-[1.5rem] flex flex-col justify-between hover:-translate-y-2 hover:shadow-2xl transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] group border border-white/60 relative w-[280px] shrink-0 will-change-transform transform-gpu">
                    <div>
                        <div class="bg-gradient-to-br from-brand-blue-50 to-gray-100 w-full aspect-[3/4] rounded-2xl mb-5 flex items-center justify-center shadow-inner overflow-hidden relative border border-gray-200/50 group-hover:shadow-md transition-shadow p-4">
                            @if($buku->cover_image)
                                <img src="{{ Storage::url($buku->cover_image) }}" alt="{{ $buku->judul }}" class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500 drop-shadow-md">
                            @else
                                <div class="w-full h-full bg-gradient-to-br {{ $gradientClass }} flex flex-col justify-between p-4 rounded-xl shadow-md border border-white/10 group-hover:scale-105 transition-transform duration-500">
                                    <div class="flex justify-between items-start">
                                        <span class="text-[9px] font-extrabold uppercase tracking-widest px-2 py-0.5 rounded-full border {{ $badgeClass }}">
                                            {{ $buku->kategori }}
                                        </span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ $iconColor }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    </div>
                                    <div class="my-auto text-center py-4">
                                        <h4 class="font-extrabold text-white text-sm leading-snug tracking-tight px-1 drop-shadow-sm line-clamp-3">{{ $buku->judul }}</h4>
                                        <p class="text-[10px] text-white/70 mt-1.5 font-semibold">{{ $buku->pengarang }}</p>
                                    </div>
                                    <div class="border-t border-white/10 pt-2 flex justify-between items-center text-[9px] text-white/50 font-bold">
                                        <span>SIPUS SBY</span>
                                        <span>{{ $buku->tahun_terbit }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <p class="text-xs font-bold text-gray-400 tracking-wider uppercase mb-1">{{ $buku->pengarang }}</p>
                        <h3 class="font-extrabold text-lg text-gray-800 leading-snug mb-3 group-hover:text-brand-blue-600 transition-colors line-clamp-2">
                            <a href="{{ route('buku.detail', $buku->id) }}">{{ $buku->judul }}</a>
                        </h3>
                        
                        <div class="flex flex-wrap items-center gap-2 mb-5">
                            <span class="px-2.5 py-1 bg-brand-blue-50 text-brand-blue-700 text-[10px] font-extrabold rounded-lg border border-brand-blue-100">
                                Stok: {{ $buku->stok }}
                            </span>
                            <span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-lg">
                                {{ $buku->tahun_terbit }}
                            </span>
                        </div>
                    </div>

                    <div>
                        @if($buku->stok <= 0)
                            <button disabled class="w-full py-3.5 rounded-xl font-bold bg-gray-100 text-gray-400 border border-gray-200 cursor-not-allowed text-center text-sm">
                                Stok Habis
                            </button>
                        @else
                            @auth
                                @if(auth()->user()->role === 'anggota')
                                    <form method="POST" action="{{ $buku instanceof \App\Models\Buku ? route('katalog.pinjam', $buku->id) : route('katalog') }}">
                                        @csrf
                                        <button type="submit" class="w-full py-3.5 rounded-xl font-bold btn-primary text-center text-sm shadow-sm focus:outline-none focus:ring-4 focus:ring-brand-blue-500/50">
                                            Pinjam Buku
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('admin.buku') }}" class="btn-primary block text-center w-full py-3.5 rounded-xl font-bold text-sm shadow-sm">
                                        Kelola Buku
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn-primary block text-center w-full py-3.5 rounded-xl font-bold text-sm shadow-sm">
                                    Login untuk Pinjam
                                </a>
                            @endauth
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
