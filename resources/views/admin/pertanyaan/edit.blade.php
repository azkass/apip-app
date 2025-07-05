@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow-md rounded-xl">
    <h2 class="text-xl font-semibold mb-6">Edit Pertanyaan Evaluasi</h2>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif
    
    <form action="{{ route('pertanyaan.update', $pertanyaan->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label for="pertanyaan" class="block font-medium text-gray-700 mb-1">Pertanyaan</label>
            <textarea id="pertanyaan" name="pertanyaan" 
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                rows="3" required>{{ old('pertanyaan', $pertanyaan->pertanyaan) }}</textarea>
            
            @error('pertanyaan')
                <div class="text-red-500 mt-1 text-sm">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="flex justify-between">
            <a href="{{ route('pertanyaan.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition">Kembali</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection 