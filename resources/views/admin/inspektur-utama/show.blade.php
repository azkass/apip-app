@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-10 p-6 bg-white shadow-md rounded-xl">
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Detail Inspektur Utama</h2>
                <p class="text-gray-600">Informasi lengkap data inspektur utama</p>
            </div>
            <a href="{{ route('admin.inspektur-utama.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition">
                Kembali
            </a>
        </div>
    </div>

    <div class="bg-gray-50 rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 gap-6">
            <div class="space-y-4">
                <div class="border-b border-gray-200 pb-4">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Nama Lengkap</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $inspekturUtama->nama }}</p>
                </div>

                <div class="border-b border-gray-200 pb-4">
                    <label class="block text-sm font-medium text-gray-500 mb-1">NIP</label>
                    <p class="text-lg text-gray-900">
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $inspekturUtama->nip }}
                        </span>
                    </p>
                </div>

                <div class="border-b border-gray-200 pb-4">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Jabatan</label>
                    <p class="text-lg text-gray-900">{{ $inspekturUtama->jabatan }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Dibuat</label>
                        <p class="text-sm text-gray-700">
                            {{ \Carbon\Carbon::parse($inspekturUtama->created_at)->format('d M Y') }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ \Carbon\Carbon::parse($inspekturUtama->created_at)->format('H:i:s') }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Terakhir Diperbarui</label>
                        <p class="text-sm text-gray-700">
                            {{ \Carbon\Carbon::parse($inspekturUtama->updated_at)->format('d M Y') }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ \Carbon\Carbon::parse($inspekturUtama->updated_at)->format('H:i:s') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-end space-x-4">
        <div class="flex space-x-2">
            <a href="{{ route('admin.inspektur-utama.edit', $inspekturUtama->id) }}"
               class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-2 rounded-md transition flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>

            <button type="button"
                    onclick="confirmDelete({{ $inspekturUtama->id }}, '{{ $inspekturUtama->nama }}')"
                    class="bg-red-500 hover:bg-red-600 text-white font-semibold px-6 py-2 rounded-md transition flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Hapus
            </button>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-2">Konfirmasi Penghapusan</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus data Inspektur Utama <strong id="inspekturName"></strong>?</p>
                <p class="text-xs text-red-500 mt-2">Data yang sudah dihapus tidak dapat dikembalikan.</p>
            </div>
            <div class="items-center px-4 py-3">
                <button id="confirmDelete" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-auto shadow-sm hover:bg-red-600 mr-2">
                    Ya, Hapus
                </button>
                <button id="cancelDelete" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-auto shadow-sm hover:bg-gray-600">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
let deleteId = null;

function confirmDelete(id, nama) {
    deleteId = id;
    document.getElementById('inspekturName').textContent = nama;
    document.getElementById('deleteModal').classList.remove('hidden');
}

document.getElementById('confirmDelete').addEventListener('click', function() {
    if (deleteId) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/inspektur-utama/${deleteId}`;
        form.submit();
    }
});

document.getElementById('cancelDelete').addEventListener('click', function() {
    document.getElementById('deleteModal').classList.add('hidden');
    deleteId = null;
});

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.add('hidden');
        deleteId = null;
    }
});
</script>
@endsection
