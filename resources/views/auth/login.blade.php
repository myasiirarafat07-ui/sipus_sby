@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-[85vh] flex flex-col justify-center items-center" x-data="{ show: false }" x-init="setTimeout(() => show = true, 50)">
    <div class="w-full max-w-md" x-cloak x-show="show" x-transition:enter="transition ease-[cubic-bezier(0.34,1.56,0.64,1)] duration-700" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="text-center mb-8">
            <img src="{{ asset('images/City_of_Surabaya_Logo.png') }}" alt="Logo Kota Surabaya" class="w-24 h-auto mx-auto mb-6 drop-shadow-lg hover:scale-110 transition-transform duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)]">
            <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Masuk ke Sistem</h2>
            <p class="text-gray-500 mt-2 font-medium">Silakan login untuk mengakses layanan perpustakaan</p>
        </div>

        <div class="glass-panel p-8 rounded-3xl shadow-xl border border-white/60 relative overflow-hidden">
            <!-- Decorative inner glow -->
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-brand-yellow-400/20 rounded-full blur-3xl pointer-events-none"></div>
            
            <form method="POST" action="/login" class="relative z-10">
                @csrf
                <div class="mb-5 group">
                    <label class="block text-sm font-semibold text-gray-700 mb-2 transition-colors group-focus-within:text-brand-blue-600">Username</label>
                    <input type="text" name="username" class="w-full px-5 py-3.5 rounded-2xl border border-gray-200 bg-white/60 focus:bg-white focus:ring-4 focus:ring-brand-blue-500/20 focus:border-brand-blue-500 transition-all outline-none shadow-sm" required autofocus value="{{ old('username') }}" placeholder="Masukkan username Anda">
                    @error('username')
                        <div class="flex items-center gap-1.5 mt-2 text-red-500 text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <div class="mb-8 group">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-semibold text-gray-700 transition-colors group-focus-within:text-brand-blue-600">Password</label>
                    </div>
                    <input type="password" name="password" class="w-full px-5 py-3.5 rounded-2xl border border-gray-200 bg-white/60 focus:bg-white focus:ring-4 focus:ring-brand-blue-500/20 focus:border-brand-blue-500 transition-all outline-none shadow-sm" required placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;">
                </div>

                <button type="submit" class="btn-primary w-full py-4 px-4 text-white text-lg rounded-2xl shadow-lg focus:outline-none focus:ring-4 focus:ring-brand-blue-500/50">
                    Masuk
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
