@extends('layouts.app')
@section('content')
    <div class="p-8">
        <p><strong>Judul: </strong>{{ $regulasi->judul }}</p>
        <p><strong>Tautan: </strong>{{ $regulasi->tautan }}
            <a href="{{ $regulasi->tautan, $regulasi->id }}" target="_blank" class="py-2 px-4 bg-green-500 hover:bg-green-600 rounded-md text-white">
            Buka
            </a>
        </p>
        <p class="mt-2"><strong>Pembuat: </strong>{{ $regulasi->perencana_nama }}</p>
        <p><strong>File: </strong>{{ $regulasi->file }}
            <a href="{{ route('perencana.regulasi.download', $regulasi->id) }}" class="cursor-pointer text-white py-2 px-4 bg-red-500 hover:bg-red-600 rounded-md">
            <i class="fas fa-download"></i> PDF
            </a>
        </p>
        <p><strong>Create: </strong>{{ $regulasi->created_at }}</p>
        <p class="mb-4"><strong>Update: </strong>{{ $regulasi->updated_at }}</p>
        <a href="{{ route('perencana.regulasi.edit', $regulasi->id) }}" class="cursor-pointer px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-semibold rounded-md">Edit</a>
        <form action="{{ route('perencana.regulasi.delete', $regulasi->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="cursor-pointer px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-md">Delete</button>
        </form>
@endsection
