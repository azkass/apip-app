@extends('layouts.app')

@section('content')
<div class="container mx-auto">

    @if (session('success'))
        <div class="bg-green-200 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    <form action="{{ route('periode.update') }}" method="POST">
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

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
    </form>
</div>
@endsection
