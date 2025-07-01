@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="bg-gray-50 p-4 rounded-lg mb-4">
        <h4 class="font-semibold text-gray-700 mb-2">Informasi SOP</h4>
        <p><strong>Nomor SOP:</strong> {{ $evaluasi->sop_nomor }}</p>
        <p><strong>Judul SOP:</strong> {{ $evaluasi->sop_judul }}</p>
    </div>

    <form method="POST" action="{{ route('evaluasi.update', $evaluasi->id) }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="sop_id" value="{{ $evaluasi->sop_id }}">
        <div class="mb-4">
            <label class="block mb-1">Pertanyaan 1</label>
            <input type="text" name="judul" value="{{ old('judul', $evaluasi?->judul) }}"
            class="w-full border border-gray-300 rounded p-2">
        </div>
        <div class="mb-4">
            <label class="block mb-1">Pertanyaan 2</label>
            <textarea name="isi" rows="3"
            class="w-full border border-gray-300 rounded p-2">{{ old('isi', $evaluasi?->isi) }}</textarea>
        </div>

        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Update</button>
    </form>
</div>
@endsection
