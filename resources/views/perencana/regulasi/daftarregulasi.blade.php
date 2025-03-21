@extends('layouts.app')
@section('content')
    <h1>Daftar Regulasi</h1>
    <a href="{{ route('perencana.regulasi.create') }}" class="">Tambah Regulasi</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Judul</th>
                <th>Tautan</th>
                <th>File</th>
                <th>Perencana</th>
                <th>Terakhir diubah</th>
                @if (Auth::user()->role == 'perencana')
                    <th>Actions</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($regulasi as $regulasi)
                <tr>
                    <td>{{ $regulasi->id }}</td>
                    <td>
                        <a href="{{ route(Auth::user()->role . '.regulasi.detail', $regulasi->id) }}">
                            {{ $regulasi->judul }}
                        </a>
                    </td>
                    <td>{{ $regulasi->tautan }}</td>
                    <td>FILE</td>
                    <td> {{ $regulasi->perencana_nama }}</td>
                    <td>{{ $regulasi->updated_at }}</td>
                    <td>
                        @if (Auth::user()->role == 'perencana')
                        <a href="{{ route('perencana.regulasi.edit', $regulasi->id) }}" class="">Edit</a>
                            <form action="{{ route('perencana.regulasi.delete', $regulasi->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="cursor-pointer">Delete</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
