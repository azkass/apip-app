<div class="container p-4">
    <h1 class="text-2xl font-medium mb-4">Detail Instrumen Pengawasan</h1>
    <div class="card">
        <div class="card-body">
            <p class="card-text"><strong>Judul:</strong> {{ $instrumenPengawasan->judul }}</p>
            <p class="card-text"><strong>Nama Petugas Pengelola:</strong> {{ $instrumenPengawasan->petugas_nama }}</p>
            <p class="card-text"><strong>Status:</strong> {{ $instrumenPengawasan->status }}</p>
            <p class="card-text"><strong>Nama Perencana:</strong> {{ $instrumenPengawasan->perencana_nama }}</p>
            <p class="card-text"><strong>Isi:</strong> {{ $instrumenPengawasan->isi }}</p>

            @if (Auth::user()->role == 'perencana')
                <a href="{{ route('instrumen-pengawasan.edit', $instrumenPengawasan->id) }}" class="">Edit</a>
                <form action="{{ route('instrumen-pengawasan.delete', $instrumenPengawasan->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="">Delete</button>
                </form>
            @elseif (Auth::user()->role == 'pjk')
                <a href="{{ route('pjk-instrumen-pengawasan.edit', $instrumenPengawasan->id) }}" class="">Edit</a>
            @endif

        </div>
    </div>
</div>
