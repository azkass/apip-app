@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Daftar Evaluasi Prosedur</h1>

    @if (session('success'))
        <div class="bg-green-200 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif



    <table class="w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                <th class="border p-2">Nomor SOP</th>
                <th class="border p-2">Judul SOP</th>
                <th class="border p-2">Judul</th>
                <th class="border p-2">Isi</th>
                <th class="border p-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $evaluasi)
                <tr>
                    <td class="border p-2">{{ $evaluasi->sop_nomor }}</td>
                    <td class="border p-2">{{ Str::limit($evaluasi->sop_judul, 50) }}</td>
                    <td class="border p-2">{{ $evaluasi->judul }}</td>
                    <td class="border p-2">{{ Str::limit($evaluasi->isi, 100) }}</td>
                    <td class="border p-2">
                        <a href="{{ route('evaluasi.show', $evaluasi->id) }}" class="text-green-600 hover:cursor-pointer">Lihat</a>
                        <a href="{{ route('evaluasi.edit', $evaluasi->id) }}" class="text-blue-600 hover:cursor-pointer ml-2">Edit</a>
                        <form action="{{ route('evaluasi.destroy', $evaluasi->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 ml-2 hover:cursor-pointer">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
