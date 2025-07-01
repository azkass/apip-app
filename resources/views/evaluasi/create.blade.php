@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-10 p-6 bg-white shadow-md rounded-xl">
    <form method="POST" action="{{ route('evaluasi.store') }}">
        @csrf
        <input type="hidden" name="sop_id" value="{{ $sop_id }}">
        <div class="mb-4">
            <label for="judul" class="block font-medium text-gray-700">Pertanyaan 1</label>
            <input type="text" name="judul" id="judul" required
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
        </div>

        <div class="mb-4">
            <label for="isi" class="block font-medium text-gray-700">Pertanyaan 2</label>
            <textarea name="isi" id="isi" rows="5" required
                      class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200"></textarea>
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
