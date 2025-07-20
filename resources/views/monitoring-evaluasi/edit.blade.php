@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto md:my-8 p-6 bg-white shadow-md rounded-xl">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Edit Monitoring Evaluasi</h2>
    </div>
    <div class="mb-6">
        <p class="text-black font-semibold">Nomor SOP: </p>
        <p class="text-gray-600">{{ $sop_nomor }}</p>
    </div>
    <div class="mb-6">
        <p class="text-black font-semibold">Nama SOP: </p>
        <p class="text-gray-600">{{ $sop_nama }}</p>
    </div>

    <form method="POST" action="{{ route('monitoring-evaluasi.update', $item->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="penilaian_penerapan" class="block text-sm font-medium text-gray-700">Penilaian Terhadap Penerapan</label>
            <textarea id="penilaian_penerapan" name="penilaian_penerapan" rows="3" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>{{ old('penilaian_penerapan', $item->penilaian_penerapan) }}</textarea>
        </div>

        <div class="mb-4">
            <label for="catatan_penilaian" class="block text-sm font-medium text-gray-700">Catatan Hasil Penilaian</label>
            <textarea id="catatan_penilaian" name="catatan_penilaian" rows="3" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>{{ old('catatan_penilaian', $item->catatan_penilaian) }}</textarea>
        </div>

        <div class="mb-6">
            <label for="tindakan" class="block text-sm font-medium text-gray-700">Tindakan yang Harus Diambil</label>
            <textarea id="tindakan" name="tindakan" rows="3" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>{{ old('tindakan', $item->tindakan) }}</textarea>
        </div>

        <div class="mb-6">
            <h3 class="font-medium text-gray-700 mb-3">Evaluasi Penerapan SOP</h3>
            <div class="space-y-4">
                @foreach($pertanyaan_map as $field => $pertanyaan)
                    <div class="border border-gray-200 rounded-md p-4">
                        <div class="flex items-start mb-2">
                            <span class="font-medium text-gray-800 mr-2">{{ $loop->iteration }}.</span>
                            <span>{{ $pertanyaan }}</span>
                        </div>
                        <div class="mt-3 flex space-x-6">
                            <label class="inline-flex items-center">
                                <input type="radio" name="{{ $field }}" value="ya" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                    {{ $item->$field == 'ya' ? 'checked' : '' }} required>
                                <span class="ml-2 text-black font-medium">Ya</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="{{ $field }}" value="tidak" class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50"
                                    {{ $item->$field == 'tidak' ? 'checked' : '' }} required>
                                <span class="ml-2 text-black font-medium">Tidak</span>
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex justify-between mt-6">
            <a href="{{ route('monitoring-evaluasi.show', $item->id) }}"
               class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-md transition">
                   Batal
            </a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
