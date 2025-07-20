@extends('layouts.app')

@section('content')
<div class="container max-w-2xl mx-auto md:my-8 p-6 bg-white shadow-md rounded-xl">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Edit Prosedur Pengawasan</h2>
    <form action="{{ route('periode.update', $periode->id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="pembuat_id" value="{{ $periode?->pembuat_id ?? Auth::id() }}">

        <div class="mb-4">
            <label class="block mb-1">Tanggal Mulai</label>
            <input type="date" name="mulai" value="{{ old('mulai', $periode?->mulai) }}"
                   class="w-full border border-gray-300 rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block mb-1">Tanggal Selesai</label>
            <input type="date" name="berakhir" value="{{ old('berakhir', $periode?->berakhir) }}"
                   class="w-full border border-gray-300 rounded p-2">
        </div>

        <div class="flex justify-between mt-6">
            <a href="{{ route('periode.index', $periode->id) }}"
                class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-md transition shadow-md">
                    Batal
            </a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                Simpan
            </button>
        </div>

    </form>
</div>
@endsection
