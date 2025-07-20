@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto mt-10 p-6 bg-white shadow-md rounded-xl">
    <h2 class="text-lg font-semibold text-gray-800">Unggah Dokumen Prosedur Pengawasan</h2>

<div class="mb-4 text-sm text-gray-700">
    <p><span class="font-medium">Nomor:</span> {{ $prosedurPengawasan->nomor ?? '-' }}</p>
        <p><span class="font-medium">Nama:</span> {{ $prosedurPengawasan->nama ?? '-' }}</p>
    </div>

    @php
        $filePath = $prosedurPengawasan->file_ttd ?? null;
    @endphp


    <form action="{{ route('prosedur-pengawasan.store-ttd', $prosedurPengawasan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <label class="block">
            <span class="text-gray-700">Pilih file PDF</span>
            <input type="file" name="file" accept="application/pdf" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200" />
        </label>
        @error('file')
        <p class="text-red-600 text-sm">{{ $message }}</p>
        @enderror
        @if($filePath)
        <div class="mb-4 text-sm text-gray-600">
            <span class="font-medium">File saat ini:</span> {{ basename($filePath) }}
            <a href="{{ route('prosedur-pengawasan.download-ttd', $prosedurPengawasan->id) }}" target="_blank" class="ml-2 text-blue-500 hover:underline">Lihat PDF</a>
            </div>
        @endif

        <div class="flex justify-between items-center mb-4">
        <a href="{{ route('prosedur-pengawasan.show', $prosedurPengawasan->id) }}" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-md transition">Batal</a>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">Simpan</button>
    </form>
</div>
@endsection
