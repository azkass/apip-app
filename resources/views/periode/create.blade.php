@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow-md rounded-xl">
    <form method="POST" action="{{ route('periode.store') }}">
        @csrf
        <input type="hidden" name="pembuat_id" value="{{ Auth::id() }}">

        <div class="mb-4">
            <label for="mulai" class="block font-medium text-gray-700">Tanggal Mulai</label>
            <input type="date" name="mulai" id="mulai" required
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
        </div>

        <div class="mb-4">
            <label for="berakhir" class="block font-medium text-gray-700">Tanggal Berakhir</label>
            <input type="date" name="berakhir" id="berakhir" required
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
        </div>

        <div class="flex justify-end">
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
