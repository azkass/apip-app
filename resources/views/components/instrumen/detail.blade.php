<div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow-md rounded-xl">
    <div class="mb-6">
        <div class="mb-4">
            <h3 class="text-sm font-medium text-gray-500">Judul</h3>
            <p class="text-base text-gray-800">{{ $instrumenPengawasan->judul }}</p>
        </div>

        <div class="mb-4">
            <h3 class="text-sm font-medium text-gray-500">Nama Petugas Pengelola</h3>
            <p class="text-base text-gray-800">{{ $instrumenPengawasan->petugas_nama }}</p>
        </div>

        <div class="mb-4">
            <h3 class="text-sm font-medium text-gray-500">Status</h3>
            <p class="text-base text-gray-800">{{ ucfirst($instrumenPengawasan->status) }}</p>
        </div>

        <div class="mb-4">
            <h3 class="text-sm font-medium text-gray-500">Nama Perencana</h3>
            <p class="text-base text-gray-800">{{ $instrumenPengawasan->perencana_nama }}</p>
        </div>

        <div class="mb-4">
            <h3 class="text-sm font-medium text-gray-500">Deskripsi</h3>
            <p class="text-base text-gray-800">{{ $instrumenPengawasan->deskripsi }}</p>
        </div>

        <div class="mb-4">
            <h3 class="text-sm font-medium text-gray-500">File</h3>
            <div class="flex items-center mt-1">
                <p class="text-base text-gray-800 mr-3">{{ $instrumenPengawasan->file }}</p>
                <a href="{{ route('instrumen-pengawasan.download', $instrumenPengawasan->id) }}"
                   class="text-center bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition">
                    <i class="fas fa-download mr-1"></i> Unduh
                </a>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <h3 class="text-sm font-medium text-gray-500">Dibuat Pada</h3>
                <p class="text-base text-gray-800">{{ \Carbon\Carbon::parse($instrumenPengawasan->created_at)->format('d M Y H:i') }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Diperbarui Pada</h3>
                <p class="text-base text-gray-800">{{ \Carbon\Carbon::parse($instrumenPengawasan->updated_at)->format('d M Y H:i') }}</p>
            </div>
        </div>
    </div>

    @if (Auth::user()->role == 'pjk' || Auth::user()->role == 'perencana')
    <div class="flex space-x-3">
        <a href="{{ route('instrumen-pengawasan.index') }}"
           class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-md transition">
            Kembali
        </a>
        <a href="{{ route('instrumen-pengawasan.edit', $instrumenPengawasan->id) }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition">
            Edit
        </a>
        @if (Auth::user()->role == 'perencana')
        <form action="{{ route('instrumen-pengawasan.delete', $instrumenPengawasan->id) }}" method="POST" class="inline-block">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Yakin ingin menghapus instrumen ini?')"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md transition">
                Hapus
            </button>
        </form>
        @endif
    </div>
    @endif
</div>
