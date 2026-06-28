@extends('layouts.app')

@section('title', 'Kelola Anggota')

@section('content')
<div x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)" x-cloak x-show="show" x-transition:enter="transition ease-[cubic-bezier(0.34,1.56,0.64,1)] duration-1000" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
<div x-data="{ 
    showModal: false, 
    isEdit: false,
    showDeleteModal: false,
    deleteId: null,
    formData: { id: '', nama_lengkap: '', nik: '', alamat: '', no_telepon: '', email: '', username: '', password: '' },
    openAddModal() {
        this.isEdit = false;
        this.formData = { id: '', nama_lengkap: '', nik: '', alamat: '', no_telepon: '', email: '', username: '', password: '' };
        this.showModal = true;
    },
    openEditModal(data) {
        this.isEdit = true;
        this.formData = { ...data };
        this.formData.username = data.user ? data.user.username : '';
        this.formData.password = '';
        this.showModal = true;
    },
    confirmDelete(id) {
        this.deleteId = id;
        this.showDeleteModal = true;
    }
}">

    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Kelola Anggota</h1>
            <p class="text-gray-500 mt-1">Daftar anggota perpustakaan.</p>
        </div>
        <button @click="openAddModal()" class="btn-primary px-6 py-2.5 rounded-2xl flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Tambah Anggota
        </button>
    </div>

    <!-- Table -->
    <div class="glass p-6 rounded-3xl overflow-hidden shadow-lg">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="py-4 px-4 font-semibold text-gray-600">NIK</th>
                        <th class="py-4 px-4 font-semibold text-gray-600">Nama Lengkap</th>
                        <th class="py-4 px-4 font-semibold text-gray-600">Email</th>
                        <th class="py-4 px-4 font-semibold text-gray-600">Tgl Registrasi</th>
                        <th class="py-4 px-4 font-semibold text-gray-600 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($anggotas as $a)
                    <tr class="border-b border-gray-100/50 hover:bg-white/50 hover:scale-[1.01] transition-all duration-300">
                        <td class="py-4 px-4 text-gray-600">{{ $a->nik }}</td>
                        <td class="py-4 px-4 font-medium text-gray-800">{{ $a->nama_lengkap }}</td>
                        <td class="py-4 px-4 text-gray-600">{{ $a->email }}</td>
                        <td class="py-4 px-4 text-gray-600">{{ \Carbon\Carbon::parse($a->tgl_registrasi)->format('d M Y') }}</td>
                        <td class="py-4 px-4 text-right flex justify-end gap-2">
                            <button @click="openEditModal({{ json_encode($a->load('user')) }})" class="text-blue-500 hover:text-blue-700 font-semibold px-3 py-1 rounded-xl hover:bg-blue-50 transition-all">Edit</button>
                            <button @click="confirmDelete({{ $a->id }})" class="text-red-500 hover:text-red-700 font-semibold px-3 py-1 rounded-xl hover:bg-red-50 transition-all">Hapus</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-500">Belum ada anggota terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Form Modal -->
    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500/50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showModal" @click.away="showModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative z-10 inline-block align-bottom glass rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                
                <form :action="isEdit ? '{{ url('admin/anggota') }}/' + formData.id : '{{ route('admin.anggota.store') }}'" method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="PUT" x-bind:disabled="!isEdit">
                    
                    <div class="px-8 pt-8 pb-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-2xl font-bold text-gray-800" id="modal-title" x-text="isEdit ? 'Edit Anggota' : 'Tambah Anggota'"></h3>
                            <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                <span class="text-2xl">&times;</span>
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                                <input type="text" name="nik" x-model="formData.nik" class="w-full px-4 py-3 rounded-2xl border border-gray-200 bg-white/50 focus:bg-white focus:ring-2 focus:ring-blue-500/50 outline-none transition" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" x-model="formData.nama_lengkap" class="w-full px-4 py-3 rounded-2xl border border-gray-200 bg-white/50 focus:bg-white focus:ring-2 focus:ring-blue-500/50 outline-none transition" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                <input type="text" name="alamat" x-model="formData.alamat" class="w-full px-4 py-3 rounded-2xl border border-gray-200 bg-white/50 focus:bg-white focus:ring-2 focus:ring-blue-500/50 outline-none transition" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">No Telepon</label>
                                <input type="text" name="no_telepon" x-model="formData.no_telepon" class="w-full px-4 py-3 rounded-2xl border border-gray-200 bg-white/50 focus:bg-white focus:ring-2 focus:ring-blue-500/50 outline-none transition" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" name="email" x-model="formData.email" class="w-full px-4 py-3 rounded-2xl border border-gray-200 bg-white/50 focus:bg-white focus:ring-2 focus:ring-blue-500/50 outline-none transition" required>
                            </div>
                            
                            <template x-if="!isEdit">
                                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 mt-2 pt-4 border-t border-gray-100">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Username (Login)</label>
                                        <input type="text" name="username" x-model="formData.username" class="w-full px-4 py-3 rounded-2xl border border-gray-200 bg-white/50 focus:bg-white focus:ring-2 focus:ring-blue-500/50 outline-none transition" :required="!isEdit">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                        <input type="password" name="password" x-model="formData.password" class="w-full px-4 py-3 rounded-2xl border border-gray-200 bg-white/50 focus:bg-white focus:ring-2 focus:ring-blue-500/50 outline-none transition" :required="!isEdit">
                                    </div>
                                </div>
                            </template>
                            
                        </div>
                    </div>
                    <div class="px-8 py-4 bg-gray-50/50 border-t border-gray-100 flex justify-end gap-4 rounded-b-3xl">
                        <button type="button" @click="showModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100/50 hover:bg-gray-200 hover:scale-105 rounded-xl transition-all duration-300">Batal</button>
                        <button type="submit" class="px-6 py-2.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-md transition-all hover:scale-105">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showDeleteModal" x-transition.opacity class="fixed inset-0 bg-gray-500/50 backdrop-blur-sm" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showDeleteModal" @click.away="showDeleteModal = false" x-transition.scale class="relative z-10 inline-block align-bottom glass rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-8 pt-8 pb-6 text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 text-red-600 mb-6">
                        <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Hapus Anggota?</h3>
                    <p class="text-gray-500">Tindakan ini tidak dapat dibatalkan. Data anggota beserta akun loginnya akan dihapus permanen.</p>
                </div>
                <div class="px-8 py-4 bg-gray-50/50 border-t border-gray-100 flex justify-center gap-4 rounded-b-3xl">
                    <button type="button" @click="showDeleteModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100/50 hover:bg-gray-200 hover:scale-105 rounded-xl transition-all duration-300">Batal</button>
                    <form :action="'{{ url('admin/anggota') }}/' + deleteId" method="POST" class="m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-6 py-2.5 rounded-2xl bg-red-600 hover:bg-red-700 text-white font-semibold shadow-md transition-all hover:scale-105">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
</div>
@endsection
