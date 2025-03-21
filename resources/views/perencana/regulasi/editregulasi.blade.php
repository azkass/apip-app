@extends('layouts.app')
@section('content')
    <h1>Edit Regulasi</h1>
    <form action="{{ route('perencana.regulasi.update', $regulasi->id) }}" method="POST">
        @csrf
        @method('PUT')
        <label for="judul">Judul</label>
        <input type="text" name="judul" id="judul" value="{{ $regulasi->judul }}" required>
        <label for="tautan">Tautan</label>
        <input type="text" name="tautan" id="tautan" value="{{ $regulasi->tautan }}" required>
        <input type="hidden" name="perencana_id" value="{{ $regulasi->perencana_id }}">
        <button type="submit">Update</button>
    </form>
@endsection
