@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow-md rounded-xl">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Tambah Inspektur Utama</h2>
        <p class="text-gray-600">Lengkapi form di bawah untuk menambahkan data inspektur utama baru</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.inspektur-utama.store') }}">
        @csrf

        <div class="mb-4">
            <label for="nama" class="block font-medium text-gray-700 mb-2">Nama Lengkap</label>
            <input type="text" name="nama" id="nama" required
                   value="{{ old('nama') }}"
                   placeholder="Masukkan nama lengkap inspektur utama"
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-500 @error('nama') border-red-500 @enderror">
            @error('nama')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
            <p class="text-gray-500 text-sm mt-1">Contoh: Dr. Ahmad Susanto, S.H., M.H.</p>
        </div>

        <div class="mb-4">
            <label for="nip" class="block font-medium text-gray-700 mb-2">NIP</label>
            <input type="text" name="nip" id="nip" required
                   value="{{ old('nip') }}"
                   placeholder="Masukkan Nomor Induk Pegawai"
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-500 @error('nip') border-red-500 @enderror">
            @error('nip')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
            <p class="text-gray-500 text-sm mt-1">Masukkan NIP tanpa spasi atau tanda baca. Contoh: 196501011990031001</p>
        </div>

        <div class="mb-6">
            <label for="jabatan" class="block font-medium text-gray-700 mb-2">Jabatan</label>
            <input type="text" name="jabatan" id="jabatan" required
                   value="{{ old('jabatan') }}"
                   placeholder="Masukkan jabatan inspektur utama"
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-500 @error('jabatan') border-red-500 @enderror">
            @error('jabatan')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
            <p class="text-gray-500 text-sm mt-1">Contoh: Inspektur Utama Kementerian Keuangan</p>
        </div>

        <div class="flex justify-between">
            <a href="{{ route('admin.inspektur-utama.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition">
                Batal
            </a>
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                Simpan Data
            </button>
        </div>
    </form>
</div>

<script>
// Format NIP input - hanya angka
document.getElementById('nip').addEventListener('input', function(e) {
    // Hapus semua karakter non-digit
    this.value = this.value.replace(/\D/g, '');

    // Batasi maksimal 18 digit (standar NIP Indonesia)
    if (this.value.length > 18) {
        this.value = this.value.slice(0, 18);
    }
});

// Auto-format nama menjadi title case saat user selesai mengetik
document.getElementById('nama').addEventListener('blur', function(e) {
    if (this.value.trim()) {
        this.value = this.value.toLowerCase().replace(/\b\w/g, l => l.toUpperCase());
    }
});
</script>
@endsection
