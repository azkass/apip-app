@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-10 p-6 bg-white shadow-md rounded-xl">
    <h2 class="text-xl font-semibold mb-2">Edit Evaluasi Prosedur</h2>
    <div class="mb-6">
        <p class="text-gray-600">Nomor SOP: {{ $sop_nomor }}</p>
        <p class="text-gray-600">Judul SOP: {{ $sop_judul }}</p>
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
                        <div class="mt-3 flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="jawaban[{{ $item->id }}]" value="1" 
                                    {{ isset($jawabanMap[$item->id]) && $jawabanMap[$item->id] == 1 ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2">Ya</span>
                            </label>
                            <span class="text-gray-500">(Biarkan tidak dicentang untuk jawaban "Tidak")</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex justify-between mt-6">
            <a href="{{ route('evaluasi.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition">
                Kembali
            </a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
