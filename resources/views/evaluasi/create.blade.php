@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-10 p-6 bg-white shadow-md rounded-xl">
    <h2 class="text-xl font-semibold mb-6">Tambah Evaluasi Prosedur</h2>
    <form method="POST" action="{{ route('evaluasi.store') }}">
        @csrf
        <input type="hidden" name="sop_id" value="{{ $sop_id }}">

        @if(empty($pertanyaan))
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4">
                Belum ada pertanyaan evaluasi. Silakan tambahkan pertanyaan terlebih dahulu.
            </div>
        @else
            <div class="mb-6">
                <h3 class="font-medium text-gray-700 mb-3">Jawab pertanyaan berikut:</h3>
                <div class="space-y-4">
                    @foreach($pertanyaan as $index => $item)
                        <input type="hidden" name="pertanyaan_id[]" value="{{ $item->id }}">
                        <div class="border border-gray-200 rounded-md p-4">
                            <div class="flex items-start mb-2">
                                <span class="font-medium text-gray-800 mr-2">{{ $index + 1 }}.</span>
                                <span>{{ $item->pertanyaan }}</span>
                            </div>
                            <div class="mt-3 flex space-x-6">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="jawaban[{{ $item->id }}]" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                    <span class="ml-2 text-black font-medium">Ya</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="jawaban[{{ $item->id }}]" value="0" class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50" required>
                                    <span class="ml-2 text-black font-medium">Tidak</span>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="flex justify-between mt-6">
            <a href="{{ route('evaluasi.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition">
                Kembali
            </a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                Simpan Evaluasi
            </button>
        </div>
    </form>
</div>
<script>
document.querySelector('form').addEventListener('submit', function(e) {
    const radioGroups = document.querySelectorAll('input[type="radio"]');
    const pertanyaanCount = {{ count($pertanyaan) }};
    const answeredCount = document.querySelectorAll('input[type="radio"]:checked').length;

    if (answeredCount !== pertanyaanCount) {
        e.preventDefault();
        alert('Harap jawab semua pertanyaan sebelum mengirim formulir.');
    }
});
</script>
@endsection
