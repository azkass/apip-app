@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10 p-6 bg-white shadow-md rounded-xl">
    <div class="flex justify-between mb-6">
        <div>
            <a href="{{ route('evaluasi.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">Kembali</a>
            <a href="{{ route('evaluasi.edit', $evaluasi->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition ml-2">Edit</a>
        </div>
    </div>

    <div class="border-b pb-4 mb-4">
        <div class="bg-gray-50 p-4 rounded-lg mb-4">
            <h4 class="font-semibold text-gray-700 mb-2">Informasi SOP</h4>
            <p><strong>Nomor SOP:</strong> {{ $evaluasi->sop_nomor }}</p>
            <p><strong>Judul SOP:</strong> {{ $evaluasi->sop_judul }}</p>
        </div>
        <h3 class="text-xl font-semibold mb-2">{{ $evaluasi->judul }}</h3>
        <div class="text-gray-500 text-sm">
            Dibuat: {{ date('d M Y H:i', strtotime($evaluasi->created_at)) }}
            @if($evaluasi->updated_at != $evaluasi->created_at)
                | Diupdate: {{ date('d M Y H:i', strtotime($evaluasi->updated_at)) }}
            @endif
        </div>
    </div>

    <div class="prose max-w-none">
        {!! nl2br(e($evaluasi->isi)) !!}
    </div>
</div>
@endsection
