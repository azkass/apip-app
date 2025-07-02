@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow-md rounded-xl">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Detail Regulasi</h2>

        <div class="mb-4">
            <h3 class="text-sm font-medium text-gray-500">Kode</h3>
            <p class="text-base text-gray-800">{{ $regulasi->kode }}</p>
        </div>

        <div class="mb-4">
            <h3 class="text-sm font-medium text-gray-500">Hasil Kerja</h3>
            <p class="text-base text-gray-800">{{ $regulasi->hasil_kerja }}</p>
        </div>

        <div class="mb-4">
            <h3 class="text-sm font-medium text-gray-500">Judul</h3>
            <p class="text-base text-gray-800">{{ $regulasi->judul }}</p>
        </div>

        <div class="mb-4">
            <h3 class="text-sm font-medium text-gray-500">Tautan</h3>
            <div class="flex items-center mt-1">
                <p class="text-base text-gray-800 mr-3">{{ $regulasi->tautan }}</p>
                <a href="{{ $regulasi->tautan }}" target="_blank" 
                   class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm transition">
                    Buka
                </a>
            </div>
        </div>

        <div class="mb-4">
            <h3 class="text-sm font-medium text-gray-500">File</h3>
            <div class="flex items-center mt-1">
                <p class="text-base text-gray-800 mr-3">{{ $regulasi->file }}</p>
                <a href="{{ route('perencana.regulasi.download', $regulasi->id) }}" 
                   class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition">
                    <i class="fas fa-download mr-1"></i> Download
                </a>
            </div>
        </div>

        <div class="mb-4">
            <h3 class="text-sm font-medium text-gray-500">Pembuat</h3>
            <p class="text-base text-gray-800">{{ $regulasi->perencana_nama }}</p>
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
        <a href="{{ route('perencana.regulasi.edit', $regulasi->id) }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition">
            Edit
        </a>
        <form action="{{ route('perencana.regulasi.delete', $regulasi->id) }}" method="POST" class="inline-block">
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
