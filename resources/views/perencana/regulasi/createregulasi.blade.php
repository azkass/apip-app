@extends('layouts.app')
@section('content')
    <h1>Tambah Regulasi</h1>
    <form action="{{ route('perencana.regulasi.store') }}" method="POST">
        @csrf
        <label for="judul">Judul</label>
        <input type="text" name="judul" id="judul" required>
        <label for="tautan">Tautan</label>
        <input type="text" name="tautan" id="tautan" required>
        <button type="submit">Submit</button>
    </form>
@endsection
