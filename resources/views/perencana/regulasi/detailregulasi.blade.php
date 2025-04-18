@extends('layouts.app')
@section('content')
    <div>
        <p>ID: {{ $regulasi->id }}</p>
        <p>Judul: {{ $regulasi->judul }}</p>
        <p>Link: {{ $regulasi->tautan }}</p>
        <p>Perencana: {{ $regulasi->perencana_nama }}</p>
        <p>File:
            @if($regulasi->file)
                <a href="{{ asset('storage/' . $regulasi->file) }}">{{ basename($regulasi->file) }}</a>
            @else
                -
            @endif
        </p>
        <p>Create: {{ $regulasi->created_at }}</p>
        <p>Update: {{ $regulasi->updated_at }}</p>
        <a href="{{ route('perencana.regulasi.edit', $regulasi->id) }}" class="">Edit</a>
        <form action="{{ route('perencana.regulasi.delete', $regulasi->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="cursor-pointer">Delete</button>
        </form>
@endsection
