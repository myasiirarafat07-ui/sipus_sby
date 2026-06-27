@extends('layouts.app')

@section('title', 'Daftar Anggota')

@section('content')
<div x-data="{ show: false }" x-init="setTimeout(() => show = true, 50)">
<div class="max-w-xl mx-auto mt-10 mb-20 px-4" x-cloak x-show="show" x-transition:enter="transition ease-[cubic-bezier(0.34,1.56,0.64,1)] duration-1000" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
    <div class="glass-panel p-8 sm:p-12 rounded-[2.5rem] shadow-xl border border-white/60">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight mb-2">Daftar Anggota Baru</h1>
            <p class="text-gray-500 font-medium">Buat akun untuk mulai meminjam buku.</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-6">
            @csrf

            <!-- NIK -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">NIK <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                        </svg>
                    </div>
                    <input type="text" name="nik" value="{{ old('nik') }}" required 
                        class="w-full pl-11 pr-4 py-3.5 bg-white/50 border border-gray-200 focus:bg-white focus:border-brand-blue-500 focus:ring-4 focus:ring-brand-blue-500/20 rounded-2xl transition-all outline-none font-medium text-gray-800"
                        placeholder="Masukkan 16 digit NIK">
                </div>
                @error('nik') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <!-- Nama Lengkap -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required 
                        class="w-full pl-11 pr-4 py-3.5 bg-white/50 border border-gray-200 focus:bg-white focus:border-brand-blue-500 focus:ring-4 focus:ring-brand-blue-500/20 rounded-2xl transition-all outline-none font-medium text-gray-800"
                        placeholder="Masukkan nama lengkap">
                </div>
                @error('nama_lengkap') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <!-- Alamat -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex mt-4 pointer-events-none text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <textarea name="alamat" required rows="2"
                        class="w-full pl-11 pr-4 py-3.5 bg-white/50 border border-gray-200 focus:bg-white focus:border-brand-blue-500 focus:ring-4 focus:ring-brand-blue-500/20 rounded-2xl transition-all outline-none font-medium text-gray-800 resize-none"
                        placeholder="Masukkan alamat domisili saat ini">{{ old('alamat') }}</textarea>
                </div>
                @error('alamat') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <!-- No Telepon & Email -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">No. HP / WA <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <input type="text" name="no_telepon" value="{{ old('no_telepon') }}" required 
                            class="w-full pl-11 pr-4 py-3.5 bg-white/50 border border-gray-200 focus:bg-white focus:border-brand-blue-500 focus:ring-4 focus:ring-brand-blue-500/20 rounded-2xl transition-all outline-none font-medium text-gray-800"
                            placeholder="Contoh: 0812...">
                    </div>
                    @error('no_telepon') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" required 
                            class="w-full pl-11 pr-4 py-3.5 bg-white/50 border border-gray-200 focus:bg-white focus:border-brand-blue-500 focus:ring-4 focus:ring-brand-blue-500/20 rounded-2xl transition-all outline-none font-medium text-gray-800"
                            placeholder="Email aktif">
                    </div>
                    @error('email') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>
            </div>

            <hr class="border-gray-200/50 my-4">

            <!-- Username & Password -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Username Login <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <input type="text" name="username" value="{{ old('username') }}" required 
                        class="w-full pl-11 pr-4 py-3.5 bg-white/50 border border-gray-200 focus:bg-white focus:border-brand-blue-500 focus:ring-4 focus:ring-brand-blue-500/20 rounded-2xl transition-all outline-none font-medium text-gray-800"
                        placeholder="Buat username (tanpa spasi)">
                </div>
                @error('username') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data="{ showPw: false, showConfirmPw: false }">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input :type="showPw ? 'text' : 'password'" name="password" required 
                            class="w-full pl-11 pr-12 py-3.5 bg-white/50 border border-gray-200 focus:bg-white focus:border-brand-blue-500 focus:ring-4 focus:ring-brand-blue-500/20 rounded-2xl transition-all outline-none font-medium text-gray-800"
                            placeholder="Minimal 6 karakter">
                        <button type="button" @click="showPw = !showPw" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg x-show="!showPw" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="showPw" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" style="display: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    @error('password') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Konfirmasi Password <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input :type="showConfirmPw ? 'text' : 'password'" name="password_confirmation" required 
                            class="w-full pl-11 pr-12 py-3.5 bg-white/50 border border-gray-200 focus:bg-white focus:border-brand-blue-500 focus:ring-4 focus:ring-brand-blue-500/20 rounded-2xl transition-all outline-none font-medium text-gray-800"
                            placeholder="Ketik ulang password">
                        <button type="button" @click="showConfirmPw = !showConfirmPw" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg x-show="!showConfirmPw" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="showConfirmPw" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" style="display: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full btn-primary py-4 rounded-2xl font-bold text-lg shadow-lg hover:shadow-xl mt-4">
                Daftar Sekarang
            </button>
        </form>

        <div class="mt-8 text-center">
            <p class="text-sm text-gray-500 font-medium">Sudah memiliki akun? 
                <a href="{{ route('login') }}" class="text-brand-blue-600 font-bold hover:underline hover:text-brand-blue-800 transition-colors">Login di sini</a>
            </p>
        </div>
    </div>
</div>
</div>
@endsection
