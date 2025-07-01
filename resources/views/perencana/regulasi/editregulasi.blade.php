@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow-md rounded-xl">
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('perencana.regulasi.update', $regulasi->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="pembuat_id" value="{{ $regulasi->pembuat_id }}">

        <div class="mb-4">
            <label for="judul" class="block font-medium text-gray-700">Judul</label>
            <input type="text" name="judul" id="judul" value="{{ $regulasi->judul }}" required
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
        </div>
        
        <div class="mb-4">
            <label for="tautan" class="block font-medium text-gray-700">Tautan</label>
            <input type="text" name="tautan" id="tautan" value="{{ $regulasi->tautan }}" required
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
        </div>
        
        <div class="mb-4">
            <label for="pdf" class="block font-medium text-gray-700">File PDF</label>
            <input type="file" name="pdf" id="pdf" accept="application/pdf"
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
            
            @if($regulasi->file)
            <div class="mt-2 text-sm text-gray-600">
                File saat ini: {{ $regulasi->file }}
                <a href="{{ route('perencana.regulasi.download', $regulasi->id) }}" class="text-blue-500 hover:underline">
                    Download
                </a>
            </div>
            @endif
        </div>

        <div class="flex justify-end">
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('pdf').addEventListener('change', function() {
        var fileName = this.files[0] ? this.files[0].name : 'Pilih file...';
        this.nextElementSibling ? this.nextElementSibling.textContent = fileName : null;
    });
</script>
@endsection
