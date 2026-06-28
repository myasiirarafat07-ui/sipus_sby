<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perpus SBY') - Sistem Informasi Perpustakaan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-gray-800 antialiased min-h-screen relative overflow-x-hidden overflow-y-scroll font-sans">

    <!-- Background Hiasan -->
    <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[40vw] h-[40vw] bg-brand-blue-100 rounded-full mix-blend-multiply filter blur-[100px] opacity-70"></div>
        <div class="absolute top-[20%] right-[-10%] w-[35vw] h-[35vw] bg-brand-yellow-400 rounded-full mix-blend-multiply filter blur-[100px] opacity-30"></div>
        <div class="absolute bottom-[-10%] left-[20%] w-[40vw] h-[40vw] bg-brand-blue-50 rounded-full mix-blend-multiply filter blur-[100px] opacity-60"></div>
    </div>


    <nav class="floating-nav" x-data="{ mobileMenuOpen: false }">
        <div class="flex items-center justify-between w-full lg:w-auto">
            <a href="{{ route('home') }}" class="flex items-center gap-4 hover:opacity-80 transition-opacity">
                <img src="{{ asset('images/City_of_Surabaya_Logo.png') }}" alt="Logo Surabaya" class="w-10 h-10 object-contain drop-shadow-md hover:scale-110 transition-transform duration-300">
                <span class="font-extrabold text-xl text-gray-800 tracking-tight">SIPUS</span>
            </a>
            
            <!-- Hamburger Button (Mobile Only) -->
            <button @click="mobileMenuOpen = true" class="lg:hidden p-2 rounded-xl text-gray-600 hover:bg-gray-100/50 transition-colors focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <!-- 3 Dots Horizontal for "Titik Tiga" -->
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                </svg>
            </button>
        </div>
        
        <div x-data="navHover()" x-init="initNav()" @mouseleave="resetNav()" class="hidden lg:flex relative items-center gap-1 sm:gap-2 z-10 py-1" x-ref="navContainer">
            <!-- Efek Pil Navigasi -->
            <div class="absolute top-0 left-0 bg-gradient-to-b from-white/40 to-white/10 backdrop-blur-[24px] border border-white/60 shadow-[inset_0_2px_4px_rgba(255,255,255,0.9),inset_0_-2px_4px_rgba(37,99,235,0.1),0_4px_12px_rgba(37,99,235,0.15)] rounded-full pointer-events-none transition-all duration-400 ease-[cubic-bezier(0.34,1.56,0.64,1)] z-0" :style="`width: ${pillWidth}px; height: ${pillHeight}px; transform: translate(${pillLeft}px, ${pillTop}px); opacity: ${pillOpacity}`"></div>

            @auth
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" @mouseenter="movePill($event.currentTarget)" @focus="movePill($event.currentTarget)" data-active="{{ request()->routeIs('admin.dashboard') ? 'true' : 'false' }}" class="nav-link relative z-10 text-sm font-bold transition-colors duration-300 px-5 py-2.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue-500 rounded-full flex items-center justify-center {{ request()->routeIs('admin.dashboard') ? 'text-brand-blue-700' : 'text-gray-600 hover:text-brand-blue-600' }}">Dashboard</a>
                <a href="{{ route('admin.buku') }}" @mouseenter="movePill($event.currentTarget)" @focus="movePill($event.currentTarget)" data-active="{{ request()->routeIs('admin.buku') ? 'true' : 'false' }}" class="nav-link relative z-10 text-sm font-bold transition-colors duration-300 px-5 py-2.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue-500 rounded-full flex items-center justify-center {{ request()->routeIs('admin.buku') ? 'text-brand-blue-700' : 'text-gray-600 hover:text-brand-blue-600' }}">Buku</a>
                <a href="{{ route('admin.anggota') }}" @mouseenter="movePill($event.currentTarget)" @focus="movePill($event.currentTarget)" data-active="{{ request()->routeIs('admin.anggota') ? 'true' : 'false' }}" class="nav-link relative z-10 text-sm font-bold transition-colors duration-300 px-5 py-2.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue-500 rounded-full flex items-center justify-center {{ request()->routeIs('admin.anggota') ? 'text-brand-blue-700' : 'text-gray-600 hover:text-brand-blue-600' }}">Anggota</a>
                <a href="{{ route('admin.peminjaman') }}" @mouseenter="movePill($event.currentTarget)" @focus="movePill($event.currentTarget)" data-active="{{ request()->routeIs('admin.peminjaman') ? 'true' : 'false' }}" class="nav-link relative z-10 text-sm font-bold transition-colors duration-300 px-5 py-2.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue-500 rounded-full flex items-center justify-center {{ request()->routeIs('admin.peminjaman') ? 'text-brand-blue-700' : 'text-gray-600 hover:text-brand-blue-600' }}">Peminjaman</a>
                <a href="{{ route('admin.denda') }}" @mouseenter="movePill($event.currentTarget)" @focus="movePill($event.currentTarget)" data-active="{{ request()->routeIs('admin.denda') ? 'true' : 'false' }}" class="nav-link relative z-10 text-sm font-bold transition-colors duration-300 px-5 py-2.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue-500 rounded-full flex items-center justify-center {{ request()->routeIs('admin.denda') ? 'text-brand-blue-700' : 'text-gray-600 hover:text-brand-blue-600' }}">Denda</a>
                <a href="{{ route('admin.laporan') }}" @mouseenter="movePill($event.currentTarget)" @focus="movePill($event.currentTarget)" data-active="{{ request()->routeIs('admin.laporan') ? 'true' : 'false' }}" class="nav-link relative z-10 text-sm font-bold transition-colors duration-300 px-5 py-2.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue-500 rounded-full flex items-center justify-center {{ request()->routeIs('admin.laporan') ? 'text-brand-blue-700' : 'text-gray-600 hover:text-brand-blue-600' }}">Laporan</a>
            @else
                <a href="{{ route('katalog') }}" @mouseenter="movePill($event.currentTarget)" @focus="movePill($event.currentTarget)" data-active="{{ request()->routeIs('katalog') ? 'true' : 'false' }}" class="nav-link relative z-10 text-sm font-bold transition-colors duration-300 px-5 py-2.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue-500 rounded-full flex items-center justify-center {{ request()->routeIs('katalog') ? 'text-brand-blue-700' : 'text-gray-600 hover:text-brand-blue-600' }}">Katalog</a>
                <a href="{{ route('anggota.peminjaman') }}" @mouseenter="movePill($event.currentTarget)" @focus="movePill($event.currentTarget)" data-active="{{ request()->routeIs('anggota.peminjaman') ? 'true' : 'false' }}" class="nav-link relative z-10 text-sm font-bold transition-colors duration-300 px-5 py-2.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue-500 rounded-full flex items-center justify-center {{ request()->routeIs('anggota.peminjaman') ? 'text-brand-blue-700' : 'text-gray-600 hover:text-brand-blue-600' }}">Peminjaman</a>
                <a href="{{ route('anggota.pengembalian') }}" @mouseenter="movePill($event.currentTarget)" @focus="movePill($event.currentTarget)" data-active="{{ request()->routeIs('anggota.pengembalian') ? 'true' : 'false' }}" class="nav-link relative z-10 text-sm font-bold transition-colors duration-300 px-5 py-2.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue-500 rounded-full flex items-center justify-center {{ request()->routeIs('anggota.pengembalian') ? 'text-brand-blue-700' : 'text-gray-600 hover:text-brand-blue-600' }}">Denda</a>
            @endif
            @endauth
            @guest
                <a href="{{ route('home') }}" @mouseenter="movePill($event.currentTarget)" @focus="movePill($event.currentTarget)" data-active="{{ request()->routeIs('home') ? 'true' : 'false' }}" data-hash="" class="nav-link relative z-10 text-sm font-bold transition-colors duration-300 px-5 py-2.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue-500 rounded-full flex items-center justify-center {{ request()->routeIs('home') ? 'text-brand-blue-700' : 'text-gray-600 hover:text-brand-blue-600' }}">Beranda</a>
                <a href="{{ route('home') }}#pencarian" @mouseenter="movePill($event.currentTarget)" @focus="movePill($event.currentTarget)" data-active="false" data-hash="#pencarian" class="nav-link relative z-10 text-sm font-bold transition-colors duration-300 px-5 py-2.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue-500 rounded-full flex items-center justify-center text-gray-600 hover:text-brand-blue-600">Pencarian</a>
                <a href="{{ route('home') }}#koleksi-baru" @mouseenter="movePill($event.currentTarget)" @focus="movePill($event.currentTarget)" data-active="false" data-hash="#koleksi-baru" class="nav-link relative z-10 text-sm font-bold transition-colors duration-300 px-5 py-2.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue-500 rounded-full flex items-center justify-center text-gray-600 hover:text-brand-blue-600">Koleksi Baru</a>
            @endguest
            
            @auth
            <div class="h-6 w-px bg-gray-200 mx-2 relative z-10"></div>
            <form method="POST" action="{{ route('logout') }}" class="m-0 z-10">
                @csrf
                <button type="submit" @mouseenter="movePill($event.currentTarget)" @focus="movePill($event.currentTarget)" data-active="false" class="nav-link text-sm font-medium text-red-500 hover:text-red-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 rounded-full flex items-center justify-center transition-colors px-5 py-2.5">Logout</button>
            </form>
            @else
            <div class="h-6 w-px bg-gray-200 mx-2 relative z-10"></div>
            <a href="{{ route('login') }}" @mouseenter="movePill($event.currentTarget)" @focus="movePill($event.currentTarget)" data-active="{{ request()->routeIs('login') ? 'true' : 'false' }}" class="nav-link text-sm font-bold text-gray-600 hover:text-brand-blue-600 px-5 py-2.5 rounded-full flex items-center justify-center transition-colors relative z-10">Login</a>
            @endauth
        </div>

        <!-- Mobile Drawer Menu -->
        <template x-teleport="body">
            <div x-show="mobileMenuOpen" style="display: none;" class="fixed inset-0 z-[100] lg:hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
                <!-- Background backdrop -->
                <div x-show="mobileMenuOpen" x-transition:enter="ease-in-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/30 backdrop-blur-sm transition-opacity" @click="mobileMenuOpen = false"></div>

                <div class="fixed inset-0 overflow-hidden pointer-events-none">
                    <div class="absolute inset-0 overflow-hidden">
                        <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                            <!-- Drawer panel -->
                            <div x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false" x-transition:enter="transform transition ease-in-out duration-300 sm:duration-500" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-300 sm:duration-500" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="pointer-events-auto w-screen max-w-xs">
                                <div class="flex h-full flex-col overflow-y-scroll glass-panel py-6 shadow-2xl rounded-l-3xl border-l border-white/60">
                                    <div class="px-4 sm:px-6">
                                        <div class="flex items-start justify-between">
                                            <h2 class="text-xl font-bold text-gray-900 flex items-center gap-3" id="slide-over-title">
                                                <img src="{{ asset('images/City_of_Surabaya_Logo.png') }}" alt="Logo" class="w-8 h-8">
                                                Menu
                                            </h2>
                                            <div class="ml-3 flex h-7 items-center">
                                                <button type="button" @click="mobileMenuOpen = false" class="rounded-md glass text-gray-500 hover:text-gray-800 focus:outline-none p-2">
                                                    <span class="sr-only">Close panel</span>
                                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="relative mt-6 flex-1 px-4 sm:px-6 flex flex-col gap-2">
                                        <div x-data="navHover()" x-init="initNav()" @mouseleave="resetNav()" class="relative flex-1 flex flex-col gap-2 pb-4" x-ref="navContainer">
                                            <!-- Efek Pil Navigasi Mobile -->
                                            <div class="absolute top-0 left-0 bg-gradient-to-b from-white/40 to-white/10 backdrop-blur-[24px] border border-white/60 shadow-[inset_0_2px_4px_rgba(255,255,255,0.9),inset_0_-2px_4px_rgba(37,99,235,0.1),0_4px_12px_rgba(37,99,235,0.15)] rounded-xl pointer-events-none transition-all duration-400 ease-[cubic-bezier(0.34,1.56,0.64,1)] z-0" :style="`width: ${pillWidth}px; height: ${pillHeight}px; transform: translate(${pillLeft}px, ${pillTop}px); opacity: ${pillOpacity}`"></div>

                                            <!-- Mobile Links -->
                                            @auth
                                            @if(Auth::user()->role === 'admin')
                                                <a href="{{ route('admin.dashboard') }}" @click="mobileMenuOpen = false" @mouseenter="movePill($event.currentTarget)" @focus="movePill($event.currentTarget)" data-active="{{ request()->routeIs('admin.dashboard') ? 'true' : 'false' }}" class="nav-link relative z-10 block px-4 py-3 rounded-xl font-bold {{ request()->routeIs('admin.dashboard') ? 'text-brand-blue-700' : 'text-gray-600 hover:text-brand-blue-600' }}">Dashboard</a>
                                                <a href="{{ route('admin.buku') }}" @click="mobileMenuOpen = false" @mouseenter="movePill($event.currentTarget)" @focus="movePill($event.currentTarget)" data-active="{{ request()->routeIs('admin.buku') ? 'true' : 'false' }}" class="nav-link relative z-10 block px-4 py-3 rounded-xl font-bold {{ request()->routeIs('admin.buku') ? 'text-brand-blue-700' : 'text-gray-600 hover:text-brand-blue-600' }}">Buku</a>
                                                <a href="{{ route('admin.anggota') }}" @click="mobileMenuOpen = false" @mouseenter="movePill($event.currentTarget)" @focus="movePill($event.currentTarget)" data-active="{{ request()->routeIs('admin.anggota') ? 'true' : 'false' }}" class="nav-link relative z-10 block px-4 py-3 rounded-xl font-bold {{ request()->routeIs('admin.anggota') ? 'text-brand-blue-700' : 'text-gray-600 hover:text-brand-blue-600' }}">Anggota</a>
                                                <a href="{{ route('admin.peminjaman') }}" @click="mobileMenuOpen = false" @mouseenter="movePill($event.currentTarget)" @focus="movePill($event.currentTarget)" data-active="{{ request()->routeIs('admin.peminjaman') ? 'true' : 'false' }}" class="nav-link relative z-10 block px-4 py-3 rounded-xl font-bold {{ request()->routeIs('admin.peminjaman') ? 'text-brand-blue-700' : 'text-gray-600 hover:text-brand-blue-600' }}">Peminjaman</a>
                                                <a href="{{ route('admin.denda') }}" @click="mobileMenuOpen = false" @mouseenter="movePill($event.currentTarget)" @focus="movePill($event.currentTarget)" data-active="{{ request()->routeIs('admin.denda') ? 'true' : 'false' }}" class="nav-link relative z-10 block px-4 py-3 rounded-xl font-bold {{ request()->routeIs('admin.denda') ? 'text-brand-blue-700' : 'text-gray-600 hover:text-brand-blue-600' }}">Denda</a>
                                                <a href="{{ route('admin.laporan') }}" @click="mobileMenuOpen = false" @mouseenter="movePill($event.currentTarget)" @focus="movePill($event.currentTarget)" data-active="{{ request()->routeIs('admin.laporan') ? 'true' : 'false' }}" class="nav-link relative z-10 block px-4 py-3 rounded-xl font-bold {{ request()->routeIs('admin.laporan') ? 'text-brand-blue-700' : 'text-gray-600 hover:text-brand-blue-600' }}">Laporan</a>
                                            @else
                                                <a href="{{ route('katalog') }}" @click="mobileMenuOpen = false" @mouseenter="movePill($event.currentTarget)" @focus="movePill($event.currentTarget)" data-active="{{ request()->routeIs('katalog') ? 'true' : 'false' }}" class="nav-link relative z-10 block px-4 py-3 rounded-xl font-bold {{ request()->routeIs('katalog') ? 'text-brand-blue-700' : 'text-gray-600 hover:text-brand-blue-600' }}">Katalog</a>
                                                <a href="{{ route('anggota.peminjaman') }}" @click="mobileMenuOpen = false" @mouseenter="movePill($event.currentTarget)" @focus="movePill($event.currentTarget)" data-active="{{ request()->routeIs('anggota.peminjaman') ? 'true' : 'false' }}" class="nav-link relative z-10 block px-4 py-3 rounded-xl font-bold {{ request()->routeIs('anggota.peminjaman') ? 'text-brand-blue-700' : 'text-gray-600 hover:text-brand-blue-600' }}">Peminjaman</a>
                                                <a href="{{ route('anggota.pengembalian') }}" @click="mobileMenuOpen = false" @mouseenter="movePill($event.currentTarget)" @focus="movePill($event.currentTarget)" data-active="{{ request()->routeIs('anggota.pengembalian') ? 'true' : 'false' }}" class="nav-link relative z-10 block px-4 py-3 rounded-xl font-bold {{ request()->routeIs('anggota.pengembalian') ? 'text-brand-blue-700' : 'text-gray-600 hover:text-brand-blue-600' }}">Denda</a>
                                            @endif
                                            <hr class="my-2 border-white/60 relative z-10">
                                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                                @csrf
                                                <button type="submit" @click="mobileMenuOpen = false" @mouseenter="movePill($event.currentTarget)" @focus="movePill($event.currentTarget)" data-active="false" class="nav-link relative z-10 w-full text-left px-4 py-3 rounded-xl font-bold text-red-500 hover:text-red-600 transition-colors">Logout</button>
                                            </form>
                                            @endauth
                                            @guest
                                                <a href="{{ route('home') }}" @click="mobileMenuOpen = false" @mouseenter="movePill($event.currentTarget)" @focus="movePill($event.currentTarget)" data-active="{{ request()->routeIs('home') ? 'true' : 'false' }}" data-hash="" class="nav-link relative z-10 block px-4 py-3 rounded-xl font-bold {{ request()->routeIs('home') ? 'text-brand-blue-700' : 'text-gray-600 hover:text-brand-blue-600' }}">Beranda</a>
                                                <a href="{{ route('home') }}#pencarian" @click="mobileMenuOpen = false" @mouseenter="movePill($event.currentTarget)" @focus="movePill($event.currentTarget)" data-active="false" data-hash="#pencarian" class="nav-link relative z-10 block px-4 py-3 rounded-xl font-bold text-gray-600 hover:text-brand-blue-600">Pencarian</a>
                                                <a href="{{ route('home') }}#koleksi-baru" @click="mobileMenuOpen = false" @mouseenter="movePill($event.currentTarget)" @focus="movePill($event.currentTarget)" data-active="false" data-hash="#koleksi-baru" class="nav-link relative z-10 block px-4 py-3 rounded-xl font-bold text-gray-600 hover:text-brand-blue-600">Koleksi Baru</a>
                                                <hr class="my-2 border-white/60 relative z-10">
                                                <a href="{{ route('login') }}" @click="mobileMenuOpen = false" @mouseenter="movePill($event.currentTarget)" @focus="movePill($event.currentTarget)" data-active="{{ request()->routeIs('login') ? 'true' : 'false' }}" class="nav-link relative z-10 block px-4 py-3 rounded-xl font-bold {{ request()->routeIs('login') ? 'text-brand-blue-700' : 'text-gray-600 hover:text-brand-blue-600' }}">Login</a>
                                            @endguest
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </nav>

    <main class="pt-28 pb-6 px-4 max-w-7xl mx-auto">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition.duration.500ms class="mb-6 bg-green-100/50 border border-green-200 text-green-700 px-4 py-3 rounded-2xl glass flex justify-between items-center" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <button @click="show = false" class="text-green-700 hover:text-green-900 font-bold">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition.duration.500ms class="mb-6 bg-red-100/50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl glass flex justify-between items-center" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
                <button @click="show = false" class="text-red-700 hover:text-red-900 font-bold">&times;</button>
            </div>
        @endif

        @if($errors->any())
            <div x-data="{ show: true }" x-show="show" x-transition.duration.500ms class="mb-6 bg-red-100/50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl glass flex flex-col gap-2 shadow-md" role="alert">
                <div class="flex justify-between items-center">
                    <span class="font-bold text-base flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        Terdapat kesalahan input:
                    </span>
                    <button @click="show = false" class="text-red-700 hover:text-red-900 font-bold text-xl">&times;</button>
                </div>
                <ul class="list-disc pl-7 text-sm font-semibold">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('navHover', () => ({
                pillWidth: 0,
                pillHeight: 0,
                pillLeft: 0,
                pillTop: 0,
                pillOpacity: 0,
                activeEl: null,

                initNav() {
                    this.$nextTick(() => {
                        this.updateActive();
                        // Evaluasi ulang setelah animasi halaman
                        setTimeout(() => {
                            this.updateActive();
                        }, 150);

                        window.addEventListener('scroll', () => {
                            this.updateActive();
                        });
                        window.addEventListener('hashchange', () => {
                            this.updateActive();
                        });
                    });
                },

                updateActive() {
                    const links = Array.from(this.$refs.navContainer.querySelectorAll('.nav-link'));
                    const pencarian = document.getElementById('pencarian');
                    const koleksiBaru = document.getElementById('koleksi-baru');
                    
                    let activeHash = '';
                    
                    if (window.location.pathname === '/' || window.location.pathname === '') {
                        const scrollY = window.scrollY;
                        
                        if (koleksiBaru && koleksiBaru.offsetParent !== null && scrollY >= koleksiBaru.offsetTop - 300) {
                            activeHash = '#koleksi-baru';
                        } else if (pencarian && pencarian.offsetParent !== null && scrollY >= pencarian.offsetTop - 300) {
                            activeHash = '#pencarian';
                        }
                    }
                    
                    if (activeHash) {
                        this.activeEl = links.find(el => el.getAttribute('data-hash') === activeHash);
                    } else {
                        this.activeEl = links.find(el => el.getAttribute('data-active') === 'true');
                    }
                    
                    if (this.activeEl) {
                        this.setPillTo(this.activeEl);
                        this.pillOpacity = 1;
                        
                        // Perbarui warna teks menu
                        links.forEach(link => {
                            if (link.classList.contains('text-red-500')) return; // Abaikan tombol logout
                            
                            if (link === this.activeEl) {
                                link.classList.add('text-brand-blue-700');
                                link.classList.remove('text-gray-600');
                            } else {
                                link.classList.add('text-gray-600');
                                link.classList.remove('text-brand-blue-700');
                            }
                        });
                    }
                },

                movePill(el) {
                    this.setPillTo(el);
                    this.pillOpacity = 1;
                },

                resetNav() {
                    if (this.activeEl) {
                        this.setPillTo(this.activeEl);
                    } else {
                        this.pillOpacity = 0;
                    }
                },

                setPillTo(el) {
                    this.pillWidth = el.offsetWidth;
                    this.pillHeight = el.offsetHeight;
                    this.pillLeft = el.offsetLeft;
                    this.pillTop = el.offsetTop;
                }
            }));
        });
    </script>
</body>
</html>
