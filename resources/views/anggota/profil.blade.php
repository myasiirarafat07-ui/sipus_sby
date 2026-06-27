@extends('layouts.app')

@section('title', 'Profil Anggota')

@section('content')
<div x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)" x-cloak x-show="show" x-transition:enter="transition ease-[cubic-bezier(0.34,1.56,0.64,1)] duration-1000" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Profil Saya</h1>
            <p class="text-gray-500 mt-1">Perbarui informasi pribadi dan password Anda.</p>
        </div>
    </div>

    <div class="glass-panel p-8 sm:p-10 rounded-[2.5rem] shadow-xl border border-white/60">
        <form action="{{ route('anggota.profil.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- NIK (Read-only) -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">NIK</label>
                    <input type="text" value="{{ $anggota->nik }}" readonly disabled
                        class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-2xl text-gray-500 cursor-not-allowed">
                    <p class="text-xs text-gray-400 mt-1">NIK tidak dapat diubah setelah registrasi.</p>
                </div>

                <!-- Username (Read-only) -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Username Login</label>
                    <input type="text" value="{{ Auth::user()->username }}" readonly disabled
                        class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-2xl text-gray-500 cursor-not-allowed">
                </div>

                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $anggota->nama_lengkap) }}" required 
                        class="w-full px-4 py-3 bg-white/50 border border-gray-200 focus:bg-white focus:border-brand-blue-500 focus:ring-4 focus:ring-brand-blue-500/20 rounded-2xl transition-all outline-none font-medium text-gray-800">
                    @error('nama_lengkap') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $anggota->email) }}" required 
                        class="w-full px-4 py-3 bg-white/50 border border-gray-200 focus:bg-white focus:border-brand-blue-500 focus:ring-4 focus:ring-brand-blue-500/20 rounded-2xl transition-all outline-none font-medium text-gray-800">
                    @error('email') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>

                <!-- Alamat -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                    <textarea name="alamat" required rows="2"
                        class="w-full px-4 py-3 bg-white/50 border border-gray-200 focus:bg-white focus:border-brand-blue-500 focus:ring-4 focus:ring-brand-blue-500/20 rounded-2xl transition-all outline-none font-medium text-gray-800">{{ old('alamat', $anggota->alamat) }}</textarea>
                    @error('alamat') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>

                <!-- No Telepon -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">No. HP / WA <span class="text-red-500">*</span></label>
                    <input type="text" name="no_telepon" value="{{ old('no_telepon', $anggota->no_telepon) }}" required 
                        class="w-full px-4 py-3 bg-white/50 border border-gray-200 focus:bg-white focus:border-brand-blue-500 focus:ring-4 focus:ring-brand-blue-500/20 rounded-2xl transition-all outline-none font-medium text-gray-800">
                    @error('no_telepon') <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>
            </div>

            <hr class="border-gray-200/50 my-6">

            <div class="mb-4">
                <h3 class="text-lg font-bold text-gray-800">Ubah Password</h3>
                <p class="text-sm text-gray-500">Kosongkan jika tidak ingin mengubah password.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data="{ showPw: false, showConfirmPw: false }">
                <!-- Password Baru -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Password Baru</label>
                    <div class="relative">
                        <input :type="showPw ? 'text' : 'password'" name="password" 
                            class="w-full px-4 pr-12 py-3 bg-white/50 border border-gray-200 focus:bg-white focus:border-brand-blue-500 focus:ring-4 focus:ring-brand-blue-500/20 rounded-2xl transition-all outline-none font-medium text-gray-800">
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

                <!-- Konfirmasi Password -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Konfirmasi Password Baru</label>
                    <div class="relative">
                        <input :type="showConfirmPw ? 'text' : 'password'" name="password_confirmation" 
                            class="w-full px-4 pr-12 py-3 bg-white/50 border border-gray-200 focus:bg-white focus:border-brand-blue-500 focus:ring-4 focus:ring-brand-blue-500/20 rounded-2xl transition-all outline-none font-medium text-gray-800">
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

            <div class="pt-6 flex justify-end">
                <button type="submit" class="px-8 py-3.5 rounded-2xl bg-brand-blue-600 hover:bg-brand-blue-700 text-white font-bold shadow-lg transition-all hover:scale-105">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
</div>
@endsection
