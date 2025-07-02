@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow-md rounded-xl">
    <form action="{{ route('perencana.regulasi.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="pembuat_id" value="{{ Auth::id() }}">

        <div class="mb-4">
            <label for="kode" class="block font-medium text-gray-700">Kode</label>
            <input type="text" name="kode" id="kode" required
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
        </div>
        
        <div class="mb-4">
            <label for="hasil_kerja" class="block font-medium text-gray-700">Hasil Kerja</label>
            <input type="text" name="hasil_kerja" id="hasil_kerja" required
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
        </div>

        <div class="mb-4">
            <label for="judul" class="block font-medium text-gray-700">Judul</label>
            <input type="text" name="judul" id="judul" required
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
        </div>
        
        <div class="mb-4">
            <label for="tautan" class="block font-medium text-gray-700">Tautan</label>
            <input type="text" name="tautan" id="tautan" required
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
        </div>
        
        <div class="mb-4">
            <label for="pdf" class="block font-medium text-gray-700">File PDF</label>
            <input type="file" name="pdf" id="pdf" accept="application/pdf" required
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
        </div>

        <div class="flex justify-end">
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                Simpan Regulasi
            </button>
        </div>
    </form>
</div>
@endsection
