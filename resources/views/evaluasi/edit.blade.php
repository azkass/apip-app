@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto md:my-8 p-6 bg-white shadow-md rounded-xl">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Detail Evaluasi Prosedur</h2>

    </div>
    <div class="mb-6">
        <p class="text-black font-semibold">Nomor SOP: </p>
        <p class="text-gray-600">{{ $sop_nomor }}</p>
    </div>
    <div class="mb-6">
        <p class="text-black font-semibold">Nama SOP: </p>
        <p class="text-gray-600">{{ $sop_nama }}</p>
    </div>

    <form method="POST" action="{{ route('evaluasi.update', $sop_id) }}">
        @csrf
        @method('PUT')

        <div class="mb-6">
            <h3 class="font-medium text-gray-700 mb-3">Jawab pertanyaan berikut:</h3>
            <div class="space-y-4">
                @foreach($allPertanyaan as $index => $item)
                    <input type="hidden" name="pertanyaan_id[]" value="{{ $item->id }}">
                    <div class="border border-gray-200 rounded-md p-4">
                        <div class="flex items-start mb-2">
                            <span class="font-medium text-gray-800 mr-2">{{ $index + 1 }}.</span>
                            <span>{{ $item->pertanyaan }}</span>
                        </div>
                        <div class="mt-3 flex space-x-6">
                            @php
                                $jawabanValue = isset($jawabanMap[$item->id]) ? $jawabanMap[$item->id] : null;
                            @endphp
                            <label class="inline-flex items-center">
                                <input type="radio" name="jawaban[{{ $item->id }}]" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                    {{ $jawabanValue === 1 ? 'checked' : '' }} required>
                                <span class="ml-2 text-black font-medium">Ya</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="jawaban[{{ $item->id }}]" value="0" class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50"
                                    {{ $jawabanValue === 0 ? 'checked' : '' }} required>
                                <span class="ml-2 text-black font-medium">Tidak</span>
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex justify-between mt-6">
            <a href="{{ route('evaluasi.show', $sop_id) }}"
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
