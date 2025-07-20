@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow-md rounded-xl">
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('regulasi.update', $regulasi->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="pembuat_id" value="{{ $regulasi->pembuat_id }}">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-semibold text-gray-800">Edit Regulasi</h2>
            <a href="{{ route('regulasi.index') }}"
               class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-md transition">
                Kembali
            </a>
        </div>

        <div class="mb-4">
            <label for="tahun" class="block font-medium text-gray-700">Tahun</label>
            <input type="text" name="tahun" id="tahun" value="{{ $regulasi->tahun }}" required
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200"
                   placeholder="Contoh: 2024">
        </div>

        <div class="mb-4">
            <label for="nomor" class="block font-medium text-gray-700">Nomor</label>
            <input type="text" name="nomor" id="nomor" value="{{ $regulasi->nomor }}" required
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200"
                   placeholder="Contoh: 01">
        </div>

        <div class="mb-4">
            <label for="tentang" class="block font-medium text-gray-700">Tentang</label>
            <textarea name="tentang" id="tentang" required rows="3"
                      class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200"
                      placeholder="Isi dengan topik atau subjek regulasi">{{ $regulasi->tentang }}</textarea>
        </div>

        <div class="mb-4">
            <label for="jenis_peraturan" class="block font-medium text-gray-700">Jenis Peraturan</label>
            <select name="jenis_peraturan" id="jenis_peraturan" required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
                <option value="">Pilih Jenis Peraturan</option>
                <option value="peraturan_bps" {{ $regulasi->jenis_peraturan == 'peraturan_bps' ? 'selected' : '' }}>Peraturan BPS</option>
                <option value="peraturan_kepala_bps" {{ $regulasi->jenis_peraturan == 'peraturan_kepala_bps' ? 'selected' : '' }}>Peraturan Kepala BPS</option>
                <option value="surat_edaran_kepala_bps" {{ $regulasi->jenis_peraturan == 'surat_edaran_kepala_bps' ? 'selected' : '' }}>Surat Edaran Kepala BPS</option>
                <option value="keputusan_kepala_bps" {{ $regulasi->jenis_peraturan == 'keputusan_kepala_bps' ? 'selected' : '' }}>Keputusan Kepala BPS</option>
                <option value="surat_edaran_irtama_bps" {{ $regulasi->jenis_peraturan == 'surat_edaran_irtama_bps' ? 'selected' : '' }}>Surat Edaran Irtama BPS</option>
                <option value="keputusan_irtama_bps" {{ $regulasi->jenis_peraturan == 'keputusan_irtama_bps' ? 'selected' : '' }}>Keputusan Irtama BPS</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="status" class="block font-medium text-gray-700">Status</label>
            <select name="status" id="status" required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
                <option value="">Pilih Status</option>
                <option value="berlaku" {{ $regulasi->status == 'berlaku' ? 'selected' : '' }}>Berlaku</option>
                <option value="tidak_berlaku" {{ $regulasi->status == 'tidak_berlaku' ? 'selected' : '' }}>Tidak Berlaku</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="tautan" class="block font-medium text-gray-700">Tautan <span class="text-gray-500">(Opsional)</span></label>
            <input type="url" name="tautan" id="tautan" value="{{ $regulasi->tautan }}"
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200"
                   placeholder="https://example.com">
        </div>

        <div class="mb-4">
            <label for="file" class="block font-medium text-gray-700">File PDF <span class="text-gray-500">(Opsional)</span></label>
            <input type="file" name="file" id="file" accept="application/pdf"
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">

            @if($regulasi->file)
            <div class="mt-2 text-sm text-gray-600">
                File saat ini: {{ $regulasi->file }}
                <a href="{{ route('regulasi.download', $regulasi->id) }}" class="text-blue-500 hover:underline">
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
    document.getElementById('file').addEventListener('change', function() {
        var fileName = this.files[0] ? this.files[0].name : 'Pilih file...';
        this.nextElementSibling ? this.nextElementSibling.textContent = fileName : null;
    });
</script>
@endsection
