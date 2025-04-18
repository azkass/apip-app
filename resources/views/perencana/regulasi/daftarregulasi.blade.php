@extends('layouts.app')
@section('content')
<div class="container p-4">
    <div class="flex justify-between items-center mb-4">
        <!-- Search Bar -->
        <div class="flex border-2 border-blue-500 overflow-hidden rounded-lg max-w-lg h-10 mr-4">
            <input type="text" placeholder="Cari Regulasi"
                class="w-full outline-none bg-white text-gray-600 text-sm px-4 py-3" />
            <button type='button' class="flex items-center justify-center bg-[#007bff] px-5 text-sm text-white cursor-pointer">
                Cari
            </button>
        </div>

        <!-- Tombol Tambah -->
        <div class="mb-4">
            <a href="{{ route('perencana.regulasi.create') }}" class="bg-blue-500 p-2 rounded-md text-white font-medium">Tambah</a>
        </div>
    </div>

    @foreach ($regulasi as $regulasi)
    <div class="border-1 border-blue-500 rounded-lg w-72 h-40 mb-4 hover:bg-gray-50 transition relative px-4 py-2">
        <a href="{{ route(Auth::user()->role . '.regulasi.detail', $regulasi->id) }}" class="absolute inset-0"></a>
                <h1 class="font-bold text-xl">
                    {{ $regulasi->judul }}
                </h1>
                <td class="">{{ $regulasi->perencana_nama }}</td> <br>
                <td class="">{{ $regulasi->updated_at }}</td> <br>
            </div>
    @endforeach
</div>
@endsection
