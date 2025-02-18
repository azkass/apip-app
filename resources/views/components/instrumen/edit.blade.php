@if (Auth::user()->role == 'perencana')
    <form action="{{ route('instrumen-pengawasan.update', $instrumenPengawasan->id) }}" method="POST">
@elseif (Auth::user()->role == 'pjk')
    <form action="{{ route('pjk-instrumen-pengawasan.update', $instrumenPengawasan->id) }}" method="POST">
@endif
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="judul">Judul</label>
        <input type="text" name="judul" class="form-control" value="{{ $instrumenPengawasan->judul }}" required>
    </div>
    <div class="form-group">
        <label for="petugas_pengelola_id">Petugas Pengelola</label>
        <select name="petugas_pengelola_id" class="form-control" required>
            @foreach ($is_pjk as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="isi">Isi</label>
        <textarea name="isi" class="form-control">{{ $instrumenPengawasan->isi }}</textarea>
    </div>
    <div class="form-group">
        <label for="status">Status</label>
        <select name="status" class="form-control" required>
            <option value="draft" {{ $instrumenPengawasan->status == 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="diajukan" {{ $instrumenPengawasan->status == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
            <option value="disetujui" {{ $instrumenPengawasan->status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
        </select>
    </div>
    <!-- Sembunyikan atau nonaktifkan input perencana_id -->
    <input type="hidden" name="perencana_id" value="{{ $instrumenPengawasan->perencana_id }}">
    <button type="submit" class="">Submit</button>
</form>
