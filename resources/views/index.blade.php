@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Instrumen Pengawasan</h1>
    <a href="{{ route('instrumen-pengawasan.create') }}" class="btn btn-primary">Create New</a>
    <table class="table">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Petugas Pengelola</th>
                <th>Status</th>
                <th>Perencana</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($instrumenPengawasan as $instrumen)
                @php
                    $petugasPengelola = DB::selectOne('SELECT name FROM users WHERE id = ?', [$instrumen->petugas_pengelola_id]);
                    $perencana = DB::selectOne('SELECT name FROM users WHERE id = ?', [$instrumen->perencana_id]);
                @endphp
                <tr>
                    <td>{{ $instrumen->judul }}</td>
                    <td>{{ $petugasPengelola->name }}</td>
                    <td>{{ $instrumen->status }}</td>
                    <td>{{ $perencana->name }}</td>
                    <td>
                        <a href="{{ route('instrumen-pengawasan.show', $instrumen->id) }}" class="btn btn-info">Show</a>
                        <a href="{{ route('instrumen-pengawasan.edit', $instrumen->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('instrumen-pengawasan.destroy', $instrumen->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
