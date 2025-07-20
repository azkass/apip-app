@extends('layouts.app')

@section('content')
<div class="container p-8">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold">Daftar Identitas Inspektur Utama</h1>
        <a href="{{ route('admin.inspektur-utama.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded-md transition">
            Tambah
        </a>
    </div>

    <div class="bg-white shadow-md rounded-sm overflow-hidden">
        <table class="w-full border-collapse">
            <thead class="bg-gray-50">
                <tr>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">No</th>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">Nama Lengkap</th>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">NIP</th>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">Jabatan</th>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">Tanggal Dibuat</th>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($inspekturUtama as $index => $inspektur)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="border border-gray-300 px-4 py-3 text-center">{{ $index + 1 }}</td>
                        <td class="border border-gray-300 px-4 py-3">
                            <div class="font-medium">{{ $inspektur->nama }}</div>
                        </td>
                        <td class="border border-gray-300 px-4 py-3">
                            <span class="inline-flex px-2 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $inspektur->nip }}
                            </span>
                        </td>
                        <td class="border border-gray-300 px-4 py-3">{{ $inspektur->jabatan }}</td>
                        <td class="border border-gray-300 px-4 py-3 text-center">
                            {{ \Carbon\Carbon::parse($inspektur->created_at)->format('d M Y') }}
                        </td>
                        <td class="border border-gray-300 px-4 py-3 text-center">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('admin.inspektur-utama.show', $inspektur->id) }}"
                                   class="bg-green-500 hover:bg-green-600 text-white font-semibold px-3 py-1 rounded text-sm transition">
                                    Detail
                                </a>
                                <a href="{{ route('admin.inspektur-utama.edit', $inspektur->id) }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-3 py-1 rounded text-sm transition">
                                    Edit
                                </a>
                                <button type="button"
                                        onclick="confirmDelete({{ $inspektur->id }}, '{{ $inspektur->nama }}')"
                                        class="bg-red-500 hover:bg-red-600 text-white font-semibold cursor-pointer px-3 py-1 rounded text-sm transition">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="border border-gray-300 px-4 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <p class="text-lg font-medium">Belum ada data Inspektur Utama</p>
                                <p class="text-sm">Klik "Tambah Inspektur Utama" untuk membuat data baru</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
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
