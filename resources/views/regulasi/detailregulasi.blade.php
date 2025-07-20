@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto md:my-8 p-6 bg-white shadow-md rounded-xl">
    <div class="mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Detail Regulasi</h2>
            <a href="{{ route('regulasi.index') }}"
               class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-md transition">
                Kembali
            </a>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <h3 class="text-sm font-medium text-gray-500">Tahun</h3>
                <p class="text-base text-gray-800">{{ $regulasi->tahun }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Nomor</h3>
                <p class="text-base text-gray-800">{{ $regulasi->nomor }}</p>
            </div>
        </div>

        <div class="mb-4">
            <h3 class="text-sm font-medium text-gray-500">Tentang</h3>
            <p class="text-base text-gray-800">{{ $regulasi->tentang }}</p>
        </div>

        <div class="mb-4">
            <h3 class="text-sm font-medium text-gray-500">Jenis Peraturan</h3>
            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                @if($regulasi->jenis_peraturan == 'peraturan_bps') bg-blue-100 text-blue-800
                @elseif($regulasi->jenis_peraturan == 'peraturan_kepala_bps') bg-green-100 text-green-800
                @elseif($regulasi->jenis_peraturan == 'surat_edaran_kepala_bps') bg-yellow-100 text-yellow-800
                @elseif($regulasi->jenis_peraturan == 'keputusan_kepala_bps') bg-purple-100 text-purple-800
                @elseif($regulasi->jenis_peraturan == 'surat_edaran_irtama_bps') bg-orange-100 text-orange-800
                @elseif($regulasi->jenis_peraturan == 'keputusan_irtama_bps') bg-red-100 text-red-800
                @else bg-gray-100 text-gray-800
                @endif">
                {{ ucwords(str_replace('_', ' ', $regulasi->jenis_peraturan)) }}
            </span>
        </div>

        <div class="mb-4">
            <h3 class="text-sm font-medium text-gray-500">Status</h3>
            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                @if($regulasi->status == 'berlaku') bg-green-100 text-green-800
                @else bg-red-100 text-red-800
                @endif">
                {{ ucfirst($regulasi->status) }}
            </span>
        </div>

        @if($regulasi->tautan)
        <div class="mb-4">
            <h3 class="text-sm font-medium text-gray-500">Tautan</h3>
            <div class="flex items-center mt-1">
                <a href="{{ $regulasi->tautan }}" target="_blank"
                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition">
                    <i class="fas fa-external-link-alt mr-1"></i> Buka Tautan
                </a>
            </div>
        </div>
        @endif

        @if($regulasi->file)
        <div class="mb-4">
            <h3 class="text-sm font-medium text-gray-500">File</h3>
            <p class="text-base text-gray-800 mt-1">{{ $regulasi->file }}</p>
            <div class="flex space-x-2 mt-2">
                <a href="{{ route('regulasi.download', $regulasi->id) }}"
                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition">
                    <i class="fas fa-download mr-1"></i> Unduh
                </a>
                <a href="{{ route('regulasi.view', $regulasi->id) }}"
                   target="_blank"
                   class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm transition">
                    <i class="fas fa-eye mr-1"></i> Lihat
                </a>
            </div>
        </div>
        @endif

        <div class="mb-4">
            <h3 class="text-sm font-medium text-gray-500">Pembuat</h3>
            <p class="text-base text-gray-800">{{ $regulasi->pembuat->name ?? 'Tidak diketahui' }}</p>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <h3 class="text-sm font-medium text-gray-500">Dibuat Pada</h3>
                <p class="text-base text-gray-800">{{ \Carbon\Carbon::parse($regulasi->created_at)->format('d M Y H:i') }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Diperbarui Pada</h3>
                <p class="text-base text-gray-800">{{ \Carbon\Carbon::parse($regulasi->updated_at)->format('d M Y H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="flex space-x-3">
        <a href="{{ route('regulasi.edit', $regulasi->id) }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition">
            Edit
        </a>
        <form action="{{ route('regulasi.delete', $regulasi->id) }}" method="POST" class="inline-block">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Yakin ingin menghapus regulasi ini?')"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md transition">
                Hapus
            </button>
        </form>
    </div>
</div>
@endsection
