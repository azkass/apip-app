@extends('layouts.app')
@section('content')
<div class="container p-4">
    <div class="flex items-center mb-4">
        <!-- Search Bar -->
        <div class="flex border-2 border-blue-500 overflow-hidden rounded-md max-w-lg h-10 mr-4">
            <input type="text" placeholder="Cari Regulasi"
                class="w-full outline-none bg-white text-gray-600 text-sm px-4 py-3" />
            <button type='button' class="flex items-center justify-center bg-[#007bff] px-5 text-sm text-white cursor-pointer">
                Cari
            </button>
        </div>

        <!-- Tombol Tambah -->
        <div class="">
            <a href="{{ route('perencana.regulasi.create') }}" class="bg-blue-500 h-[38px] px-2 rounded-md text-white flex items-center justify-center font-medium">Tambah</a>
        </div>
    </div>

    <table class="w-full bg-gray-50">
        <thead>
            <tr>
                <th class="border border-gray-300 px-4 py-2 w-[20px] text-center">No</th>
                <th class="border border-gray-300 px-4 py-2 text-center">Judul</th>
                <th class="border border-gray-300 px-4 py-2 text-center">Pembuat</th>
                <th class="border border-gray-300 px-4 py-2 text-center">Tahun</th>
                <th class="border border-gray-300 px-4 py-2 text-center">File</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($regulasi as $index => $item)
            <tr class="hover:bg-white transition-colors duration-200">
                <td class="border border-gray-300 px-4 py-2 text-center">{{ $index + 1 }}</td>
                <td class="border border-gray-300 px-4 py-2 relative h-[60px]">
                    <a href="{{ route(Auth::user()->role . '.regulasi.detail', $item->id) }}"
                       class="absolute inset-0 flex items-center justify-center">
                       {{ $item->judul }}
                    </a>
                </td>
                <td class="border border-gray-300 px-4 py-2 text-center">{{ $item->perencana_nama }}</td>
                <td class="border border-gray-300 px-4 py-2 text-center">
                    {{ $item->updated_at }}
                </td>
                <td class="border border-gray-300 px-4 py-2 text-center">
                    PDF
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
