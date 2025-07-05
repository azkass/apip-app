@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-10 p-6 bg-white shadow-md rounded-xl">
    <h2 class="text-xl font-semibold mb-2">Detail Evaluasi Prosedur</h2>
    <div class="mb-6">
        <p class="text-gray-600">Nomor SOP: {{ $sop_nomor }}</p>
        <p class="text-gray-600">Judul SOP: {{ $sop_judul }}</p>
    </div>
    
    <div class="mb-8">
        <div class="bg-gray-50 p-4 rounded-lg mb-4">
            <h4 class="font-semibold text-gray-700 mb-2">Ringkasan Hasil Evaluasi</h4>
            <div class="grid grid-cols-3 gap-4 text-center">
                <div class="bg-white p-3 rounded-md shadow-sm">
                    <div class="text-2xl font-bold">{{ $totalPertanyaan }}</div>
                    <div class="text-gray-500 text-sm">Total Pertanyaan</div>
                </div>
                <div class="bg-white p-3 rounded-md shadow-sm">
                    <div class="text-2xl font-bold text-green-600">{{ $jawabanYa }}</div>
                    <div class="text-gray-500 text-sm">Jawaban Ya</div>
                </div>
                <div class="bg-white p-3 rounded-md shadow-sm">
                    <div class="text-2xl font-bold {{ $persentase >= 70 ? 'text-green-600' : ($persentase >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                        {{ number_format($persentase, 1) }}%
                    </div>
                    <div class="text-gray-500 text-sm">Persentase</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mb-6">
        <h3 class="font-medium text-gray-700 mb-3">Hasil Evaluasi:</h3>
        <div class="space-y-4">
            @foreach($evaluasiItems as $index => $item)
                <div class="border border-gray-200 rounded-md p-4">
                    <div class="flex items-start mb-2">
                        <span class="font-medium text-gray-800 mr-2">{{ $index + 1 }}.</span>
                        <span>{{ $item->pertanyaan }}</span>
                    </div>
                    <div class="mt-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $item->jawaban == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $item->jawaban == 1 ? 'Ya' : 'Tidak' }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="flex justify-between mt-8">
        <a href="{{ route('evaluasi.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition">
            Kembali
        </a>
        <div class="space-x-2">
            <a href="{{ route('evaluasi.edit', $sop_id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition">
                Edit Evaluasi
            </a>
            <form action="{{ route('evaluasi.destroy', $sop_id) }}" method="POST" class="inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md transition"
                        onclick="return confirm('Yakin ingin menghapus evaluasi ini?')">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
