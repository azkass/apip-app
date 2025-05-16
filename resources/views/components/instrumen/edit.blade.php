<div class="p-4">
    @if (Auth::user()->role == 'perencana')
        <form action="{{ route('instrumen-pengawasan.update', $instrumenPengawasan->id) }}" method="POST" enctype="multipart/form-data">
    @elseif (Auth::user()->role == 'pjk')
        <form action="{{ route('pjk-instrumen-pengawasan.update', $instrumenPengawasan->id) }}" method="POST" enctype="multipart/form-data">
    @endif
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="judul" class="font-semibold">Judul : </label>
            <input type="text" name="judul" class="form-control" value="{{ $instrumenPengawasan->judul }}" required>
        </div>
        <div class="form-group">
            <label for="pengelola_id" class="font-semibold">Petugas Pengelola : </label>
            <select name="pengelola_id" class="form-control" required>
                @foreach ($is_pjk as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group flex items-start gap-2">
            <label for="deskripsi" class="font-semibold pt-1">Deskripsi :</label>
            <textarea name="deskripsi" class="form-control">{{ $instrumenPengawasan->deskripsi }}</textarea>
        </div>

        <!-- <div class="form-group">
            <label for="file">File</label>
            <input type="file" name="pdf" id="pdf" accept="application/pdf" required>
        </div> -->

        <div class="form-group">
            <div class="input-group">
                <div class="custom-file">
                    <label for="pdf" class="font-semibold">File : </label>
                    <input type="file" name="pdf" id="pdf" accept="application/pdf" class="custom-file-input">
                </div>
            </div>
            @if($instrumenPengawasan->file)
            <div class="mt-2">
                <small class="text-muted">File saat ini: {{ $instrumenPengawasan->file }}</small>
                <a href="{{ route('instrumen-pengawasan.download', $instrumenPengawasan->id) }}" class="">
                </a>
            </div>
            @endif
        </div>


        <div class="form-group" class="">
            <label for="status" class="font-semibold">Status</label>
            <select name="status" class="form-control" required>
                <option value="draft" {{ $instrumenPengawasan->status == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="diajukan" {{ $instrumenPengawasan->status == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                <option value="disetujui" {{ $instrumenPengawasan->status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
            </select>
        </div>
        <!-- Sembunyikan atau nonaktifkan input perencana_id -->
        <input type="hidden" name="pembuat_id" value="{{ $instrumenPengawasan->pembuat_id }}">
        <button type="submit" class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md">Simpan</button>
    </form>
    @if (Auth::user()->role == 'perencana')
        <form action="{{ route('instrumen-pengawasan.delete', $instrumenPengawasan->id) }}" method="POST" class="mt-4">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-md">Delete</button>
        </form>
    @endif
</div>


<script>
    // Menampilkan nama file yang dipilih
    document.getElementById('pdf').addEventListener('change', function() {
        var fileName = this.files[0] ? this.files[0].name : 'Pilih file...';
        document.querySelector('.custom-file-label').textContent = fileName;
    });
</script>
