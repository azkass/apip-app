@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow-md rounded-xl">
        <h2 class="text-lg font-semibold mb-6 text-gray-800">Tambah Regulasi</h2>

    <form action="{{ route('regulasi.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="pembuat_id" value="{{ Auth::id() }}">

        <div class="mb-4">
            <label for="tahun" class="block font-medium text-gray-700">Tahun</label>
            <input type="text" name="tahun" id="tahun" autocomplete="off" required
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200"
                   placeholder="Contoh: 2024">
        </div>

        <div class="mb-4">
            <label for="nomor" class="block font-medium text-gray-700">Nomor</label>
            <input type="text" name="nomor" id="nomor" autocomplete="off" required
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200"
                   placeholder="Contoh: 01">
        </div>

        <div class="mb-4">
            <label for="tentang" class="block font-medium text-gray-700">Tentang</label>
            <textarea name="tentang" id="tentang" required rows="3"
                      class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200"
                      placeholder="Isi dengan topik atau subjek regulasi"></textarea>
        </div>

        <div class="mb-4">
            <label for="jenis_peraturan" class="block font-medium text-gray-700">Jenis Peraturan</label>
            <select name="jenis_peraturan" id="jenis_peraturan" required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
                <option value="">Pilih Jenis Peraturan</option>
                <option value="peraturan_bps">Peraturan BPS</option>
                <option value="peraturan_kepala_bps">Peraturan Kepala BPS</option>
                <option value="surat_edaran_kepala_bps">Surat Edaran Kepala BPS</option>
                <option value="keputusan_kepala_bps">Keputusan Kepala BPS</option>
                <option value="surat_edaran_irtama_bps">Surat Edaran Irtama BPS</option>
                <option value="keputusan_irtama_bps">Keputusan Irtama BPS</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="status" class="block font-medium text-gray-700">Status</label>
            <select name="status" id="status" required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
                <option value="">Pilih Status</option>
                <option value="berlaku">Berlaku</option>
                <option value="tidak_berlaku">Tidak Berlaku</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="tautan" class="block font-medium text-gray-700">Tautan <span class="text-gray-500">(Opsional)</span></label>
            <input type="url" name="tautan" id="tautan" autocomplete="off"
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200"
                   placeholder="https://example.com">
        </div>

        <div class="mb-4">
            <label for="file" class="block font-medium text-gray-700">File PDF <span class="text-gray-500">(Opsional)</span></label>
            <input type="file" name="file" id="file" autocomplete="off" accept="application/pdf"
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
        </div>

        <div class="flex justify-between">
            <a href="{{ route('regulasi.index') }}"
               class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-md transition">
                   Batal
            </a>
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
