@extends('layouts.app')
@section('content')
<div class="p-8">
    <form action="{{ route('perencana.regulasi.update', $regulasi->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div>
            <label for="judul" class="font-bold">Judul : </label>
            <input type="text" name="judul" id="judul" value="{{ $regulasi->judul }}" required>
        </div>
        <div>
            <label for="tautan" class="font-bold">Tautan</label>
            <input type="text" name="tautan" id="tautan" value="{{ $regulasi->tautan }}" required>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="custom-file">
                    <label for="pdf" class="font-semibold" class="font-bold">File : </label>
                    <input type="file" name="pdf" id="pdf" accept="application/pdf" class="custom-file-input">
                </div>
            </div>
            @if($regulasi->file)
            <div class="mt-2">
                <small class="text-muted">File saat ini: {{ $regulasi->file }}</small>
                <a href="{{ route('perencana.regulasi.download', $regulasi->id) }}" class="">
                </a>
            </div>
            @endif
        </div>

        <input type="hidden" name="pembuat_id" value="{{ $regulasi->pembuat_id }}">
        <button type="submit" class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md">Simpan</button>
    </form>
</div>

<script>
    // Menampilkan nama file yang dipilih
    document.getElementById('pdf').addEventListener('change', function() {
        var fileName = this.files[0] ? this.files[0].name : 'Pilih file...';
        document.querySelector('.custom-file-label').textContent = fileName;
    });
</script>
@endsection
