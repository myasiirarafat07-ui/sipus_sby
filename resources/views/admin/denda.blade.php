@extends('layouts.app')

@section('title', 'Kelola Denda')

@section('content')
<div x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)" x-cloak x-show="show" x-transition:enter="transition ease-[cubic-bezier(0.34,1.56,0.64,1)] duration-1000" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
<div x-data="{ 
    showDeleteModal: false,
    deleteId: null,
    showModal: false,
    isEdit: false,
    editId: null,
    formData: { peminjaman_id: '', hari_terlambat: '', total_denda: '', keterangan: '', status_bayar: 'belum' },
    openModal(type, data = null) {
        if (type === 'edit') {
            this.isEdit = true;
            this.editId = data.id;
            this.formData = { 
                peminjaman_id: data.peminjaman_id, 
                hari_terlambat: data.hari_terlambat, 
                total_denda: data.total_denda, 
                keterangan: data.keterangan || '',
                status_bayar: data.status_bayar || 'belum'
            };
        } else {
            this.isEdit = false;
            this.formData = { peminjaman_id: '', hari_terlambat: '', total_denda: '', keterangan: '', status_bayar: 'belum' };
        }
        this.showModal = true;
    },
    confirmDelete(id) {
        this.deleteId = id;
        this.showDeleteModal = true;
    }
}">

    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Kelola Denda</h1>
            <p class="text-gray-500 mt-1">Daftar sanksi keterlambatan pengembalian buku.</p>
        </div>
        <button @click="openModal('add')" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl shadow-lg hover:shadow-xl transition-all hover:scale-105 font-semibold flex items-center gap-2">
            <span>+</span> Input Denda Manual
        </button>
    </div>

    <!-- Table -->
    <div class="glass p-6 rounded-3xl overflow-hidden shadow-lg">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="py-4 px-4 font-semibold text-gray-600">ID Pinjam</th>
                        <th class="py-4 px-4 font-semibold text-gray-600">Anggota</th>
                        <th class="py-4 px-4 font-semibold text-gray-600">Buku</th>
                        <th class="py-4 px-4 font-semibold text-gray-600">Terlambat</th>
                        <th class="py-4 px-4 font-semibold text-gray-600">Total Denda</th>
                        <th class="py-4 px-4 font-semibold text-gray-600">Status</th>
                        <th class="py-4 px-4 font-semibold text-gray-600 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dendas as $d)
                    <tr class="border-b border-gray-100/50 hover:bg-white/50 hover:scale-[1.01] transition-all duration-300">
                        <td class="py-4 px-4 text-gray-600">#{{ $d->peminjaman_id }}</td>
                        <td class="py-4 px-4 font-medium text-gray-800">{{ $d->peminjaman->anggota->nama_lengkap ?? 'Unknown' }}</td>
                        <td class="py-4 px-4 text-gray-600">{{ $d->peminjaman->buku->judul ?? 'Unknown' }}</td>
                        <td class="py-4 px-4 text-red-500 font-semibold">{{ $d->hari_terlambat }} Hari</td>
                        <td class="py-4 px-4 font-bold text-gray-800">Rp {{ number_format($d->total_denda, 0, ',', '.') }}</td>
                        <td class="py-4 px-4">
                            @if($d->status_bayar === 'lunas')
                                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-lg border border-green-200">Lunas</span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-lg border border-red-200">Belum Bayar</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-right flex justify-end gap-2">
                            @if($d->status_bayar === 'belum')
                            <form action="{{ route('admin.denda.update', $d->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status_bayar" value="lunas">
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-semibold px-3 py-1 rounded-xl shadow-md transition-all">Lunas</button>
                            </form>
                            @endif
                            <button @click="openModal('edit', {{ json_encode($d) }})" class="text-blue-500 hover:text-blue-700 font-semibold px-3 py-1 rounded-xl hover:bg-blue-50 transition-all">Edit</button>
                            <button @click="confirmDelete({{ $d->id }})" class="text-red-500 hover:text-red-700 font-semibold px-3 py-1 rounded-xl hover:bg-red-50 transition-all">Hapus</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-500">Tidak ada data denda.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Form Modal -->
    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-gray-500/50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showModal" @click.away="showModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="relative z-10 inline-block align-bottom glass rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
                
                <form :action="isEdit ? '{{ url('admin/denda') }}/' + editId : '{{ route('admin.denda.store') }}'" method="POST">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    
                    <div class="px-8 pt-8 pb-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-2xl font-bold text-gray-800" id="modal-title" x-text="isEdit ? 'Edit Denda' : 'Input Denda Manual'"></h3>
                            <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                <span class="text-2xl">&times;</span>
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ID Peminjaman</label>
                                <input type="number" name="peminjaman_id" x-model="formData.peminjaman_id" class="w-full px-4 py-3 rounded-2xl border border-gray-200 bg-white/50 focus:bg-white focus:ring-2 focus:ring-blue-500/50 outline-none transition" required>
                                <p class="text-xs text-gray-500 mt-1">ID Peminjaman yang disetujui atau aktif.</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Hari Terlambat</label>
                                <input type="number" name="hari_terlambat" x-model="formData.hari_terlambat" class="w-full px-4 py-3 rounded-2xl border border-gray-200 bg-white/50 focus:bg-white focus:ring-2 focus:ring-blue-500/50 outline-none transition" required min="0">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Total Denda (Rp)</label>
                                <input type="number" name="total_denda" x-model="formData.total_denda" class="w-full px-4 py-3 rounded-2xl border border-gray-200 bg-white/50 focus:bg-white focus:ring-2 focus:ring-blue-500/50 outline-none transition" required min="0">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status Pembayaran</label>
                                <select name="status_bayar" x-model="formData.status_bayar" class="w-full px-4 py-3 rounded-2xl border border-gray-200 bg-white/50 focus:bg-white focus:ring-2 focus:ring-blue-500/50 outline-none transition" required>
                                    <option value="belum">Belum Bayar</option>
                                    <option value="lunas">Lunas</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan (Opsional)</label>
                                <textarea name="keterangan" x-model="formData.keterangan" rows="2" class="w-full px-4 py-3 rounded-2xl border border-gray-200 bg-white/50 focus:bg-white focus:ring-2 focus:ring-blue-500/50 outline-none transition"></textarea>
                            </div>
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
                        <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Hapus Denda?</h3>
                    <p class="text-gray-500">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="px-8 py-4 bg-gray-50/50 border-t border-gray-100 flex justify-center gap-4 rounded-b-3xl">
                    <button type="button" @click="showDeleteModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100/50 hover:bg-gray-200 rounded-xl transition-all">Batal</button>
                    <form :action="'{{ url('admin/denda') }}/' + deleteId" method="POST" class="m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-6 py-2.5 rounded-2xl bg-red-600 hover:bg-red-700 text-white font-semibold transition-all">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
