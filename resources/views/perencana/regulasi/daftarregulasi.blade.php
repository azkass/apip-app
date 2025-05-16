@extends('layouts.app')
@section('content')
<div class="container p-4">
    <div>
        <p class="font-semibold mb-1">Cari</p>
    </div>
    <div class="flex items-center mb-4">
        <!-- Search Bar -->
        <div class="flex border-2 border-gray-300 overflow-hidden rounded-md h-10 mr-2">
            <input type="text" placeholder="Cari Regulasi Pengawasan" class="w-96 outline-none bg-white text-gray-600 text-sm px-4 py-3" />
        </div>

        <!-- Tombol Tambah -->
        <div class="">
            <a href="{{ route('perencana.regulasi.create') }}" class="bg-blue-500 h-[38px] px-2 rounded-md text-white flex items-center justify-center font-medium">Tambah</a>
        </div>
    </div>

    <table class="w-full bg-gray-50">
        <thead class="bg-gray-200">
            <tr>
                <th class="border border-gray-300 px-4 py-2 w-[20px] text-center">No</th>
                <th class="border border-gray-300 px-4 py-2 text-center">Judul</th>
                <th class="border border-gray-300 px-4 py-2 text-center w-40">Dokumen</th>
                <th class="border border-gray-300 px-4 py-2 text-center w-36">Tautan</th>
                <th class="border border-gray-300 px-4 py-2 text-center w-36">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($regulasi as $index => $item)
            <tr class="hover:bg-white transition-colors duration-200">
                <td class="border border-gray-300 px-4 py-2 text-center">{{ $index + 1 }}</td>
                <td class="border border-gray-300 px-4 py-2 align-middle">
                    <a href="{{ route(Auth::user()->role . '.regulasi.detail', $item->id) }}" class="inline-block w-full">
                        {{ $item->judul }}
                    </a>
                </td>
                <td class="border border-gray-300 px-4 py-2 text-center text-white font-semibold">
                    <a href="{{ route('perencana.regulasi.download', $item->id) }}" class="py-2 px-4 bg-red-500 hover:bg-red-600 rounded-md">
                        <i class="fas fa-download"></i> PDF
                    </a>
                </td>
                <td class="border border-gray-300 px-4 py-2 text-center">
                    <a href="{{ $item->tautan, $item->id }}" target="_blank" class="py-2 px-4 bg-green-500 hover:bg-green-600 rounded-md text-white">
                        Buka
                    </a>
                </td>
                <td class="border border-gray-300 px-4 py-2 text-center">
                    <a href="{{ route(Auth::user()->role . '.regulasi.edit', $item->id) }}" class="py-2 px-4 bg-sky-500 hover:bg-sky-600 rounded-md text-white">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
