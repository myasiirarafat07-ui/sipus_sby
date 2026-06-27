@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="mb-10" x-data="{ showHeader: false }" x-init="setTimeout(() => showHeader = true, 50)" x-show="showHeader" x-transition:enter="transition ease-[cubic-bezier(0.34,1.56,0.64,1)] duration-700" x-transition:enter-start="opacity-0 -translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
    <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">Dashboard Admin</h1>
    <p class="text-gray-500 mt-1 font-medium">Ringkasan aktivitas dan pengelolaan perpustakaan</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10" x-data x-init="
    const stats = document.querySelectorAll('.stat-card');
    stats.forEach((card, i) => {
        setTimeout(() => {
            card.classList.remove('opacity-0', 'translate-y-8');
        }, 150 + (i * 100));
    });
">
    <div class="stat-card opacity-0 translate-y-8 transition-all duration-700 ease-[cubic-bezier(0.34,1.56,0.64,1)] glass-panel p-6 rounded-[2rem] border border-white/60 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-brand-blue-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-out z-0"></div>
        <div class="relative z-10">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-xl bg-white shadow-sm border border-brand-blue-100 flex items-center justify-center text-brand-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                </div>
            </div>
            <h3 class="text-gray-500 font-semibold text-sm tracking-wide uppercase mb-1">Total Buku</h3>
            <p class="text-4xl font-extrabold text-gray-800">{{ $totalBuku }}</p>
        </div>
    </div>
    
    <div class="stat-card opacity-0 translate-y-8 transition-all duration-700 ease-[cubic-bezier(0.34,1.56,0.64,1)] glass-panel p-6 rounded-[2rem] border border-white/60 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-brand-yellow-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-out z-0"></div>
        <div class="relative z-10">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-xl bg-white shadow-sm border border-brand-yellow-200 flex items-center justify-center text-brand-yellow-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </div>
            </div>
            <h3 class="text-gray-500 font-semibold text-sm tracking-wide uppercase mb-1">Total Anggota</h3>
            <p class="text-4xl font-extrabold text-gray-800">{{ $totalAnggota }}</p>
        </div>
    </div>
    
    <div class="stat-card opacity-0 translate-y-8 transition-all duration-700 ease-[cubic-bezier(0.34,1.56,0.64,1)] glass-panel p-6 rounded-[2rem] border border-white/60 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-brand-blue-100/50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-out z-0"></div>
        <div class="relative z-10">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-xl bg-white shadow-sm border border-brand-blue-200 flex items-center justify-center text-brand-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                </div>
            </div>
            <h3 class="text-gray-500 font-semibold text-sm tracking-wide uppercase mb-1">Peminjaman Aktif</h3>
            <p class="text-4xl font-extrabold text-brand-blue-600">{{ $totalPeminjamanAktif }}</p>
        </div>
    </div>
    
    <div class="stat-card opacity-0 translate-y-8 transition-all duration-700 ease-[cubic-bezier(0.34,1.56,0.64,1)] glass-panel p-6 rounded-[2rem] border border-white/60 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-red-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-out z-0"></div>
        <div class="relative z-10">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-xl bg-white shadow-sm border border-red-200 flex items-center justify-center text-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <h3 class="text-gray-500 font-semibold text-sm tracking-wide uppercase mb-1">Denda Belum Dibayar</h3>
            <p class="text-3xl sm:text-4xl font-extrabold text-red-500">Rp {{ number_format($totalDenda, 0, ',', '.') }}</p>
        </div>
    </div>
</div>

<h2 class="text-xl font-bold text-gray-800 mb-4 px-2">Aksi Cepat</h2>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6" x-data x-init="
    const actions = document.querySelectorAll('.action-card');
    actions.forEach((card, i) => {
        setTimeout(() => {
            card.classList.remove('opacity-0', 'translate-y-8');
        }, 550 + (i * 100));
    });
">
    <a href="{{ route('admin.peminjaman') }}" class="action-card opacity-0 translate-y-8 glass-panel p-6 rounded-[2rem] flex items-center justify-between hover:-translate-y-1.5 hover:shadow-xl transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] group border border-white/60">
        <div>
            <h3 class="font-bold text-lg text-gray-800 group-hover:text-brand-blue-600 transition-colors">Kelola Peminjaman</h3>
            <p class="text-sm text-gray-500 font-medium">Setujui atau kembalikan buku</p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-brand-blue-50 text-brand-blue-600 flex items-center justify-center group-hover:bg-brand-blue-500 group-hover:text-white group-hover:scale-110 transition-all duration-300 shadow-sm border border-brand-blue-100 group-hover:border-transparent">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
        </div>
    </a>
    
    <a href="{{ route('admin.buku') }}" class="action-card opacity-0 translate-y-8 glass-panel p-6 rounded-[2rem] flex items-center justify-between hover:-translate-y-1.5 hover:shadow-xl transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] group border border-white/60">
        <div>
            <h3 class="font-bold text-lg text-gray-800 group-hover:text-brand-blue-600 transition-colors">Katalog Buku</h3>
            <p class="text-sm text-gray-500 font-medium">Tambah atau edit buku</p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-brand-yellow-50 text-brand-yellow-600 flex items-center justify-center group-hover:bg-brand-yellow-400 group-hover:text-white group-hover:scale-110 transition-all duration-300 shadow-sm border border-brand-yellow-100 group-hover:border-transparent">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
        </div>
    </a>
    
    <a href="{{ route('admin.anggota') }}" class="action-card opacity-0 translate-y-8 glass-panel p-6 rounded-[2rem] flex items-center justify-between hover:-translate-y-1.5 hover:shadow-xl transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] group border border-white/60">
        <div>
            <h3 class="font-bold text-lg text-gray-800 group-hover:text-brand-blue-600 transition-colors">Data Anggota</h3>
            <p class="text-sm text-gray-500 font-medium">Kelola pengguna perpustakaan</p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-brand-blue-50 text-brand-blue-600 flex items-center justify-center group-hover:bg-brand-blue-500 group-hover:text-white group-hover:scale-110 transition-all duration-300 shadow-sm border border-brand-blue-100 group-hover:border-transparent">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
        </div>
    </a>
</div>
@endsection
