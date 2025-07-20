@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow-md rounded-xl">
    <form action="{{ route('instrumen-pengawasan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="pembuat_id" value="{{ Auth::id() }}">

        <!-- Kode Instrumen -->
        <div class="mb-4">
            <label for="kode" class="block font-medium text-gray-700">Kode Instrumen</label>
            <input type="text" name="kode" id="kode" autocomplete="off" required
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
        </div>

        <!-- Hasil Kerja -->
        <div class="mb-4">
            <label for="hasil_kerja" class="block font-medium text-gray-700">Hasil Kerja</label>
            <input type="text" name="hasil_kerja" id="hasil_kerja" autocomplete="off" required
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
        </div>

        <!-- Nama Instrumen -->
        <div class="mb-4">
            <label for="nama" class="block font-medium text-gray-700">Nama Instrumen</label>
            <input type="text" name="nama" id="nama" autocomplete="off" required
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
        </div>

        <div class="mb-4">
            <label for="penyusun_id" class="block font-medium text-gray-700">Petugas Pengelola</label>
            <select name="penyusun_id" id="penyusun_id" required
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
                @foreach ($is_pjk as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="deskripsi" class="block font-medium text-gray-700">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi"
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200"></textarea>
        </div>

        <div class="mb-4">
            <label for="pdf" class="block font-medium text-gray-700">File PDF</label>
            <input type="file" name="pdf" id="pdf" accept="application/pdf" required
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
        </div>

        <div class="mb-4">
            <label for="status" class="block font-medium text-gray-700">Status</label>
            <select name="status" id="status" required
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
                <option value="draft">Draft</option>
                <option value="diajukan">Diajukan</option>
                <option value="disetujui">Disetujui</option>
            </select>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                Simpan Instrumen
            </button>
        </div>
    </form>
</div>
@endsection
