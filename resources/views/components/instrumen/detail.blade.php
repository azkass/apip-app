<div class="container p-4">
    <div class="card">
        <div class="card-body">
            <p class="card-text"><strong>Judul:</strong> {{ $instrumenPengawasan->judul }}</p>
            <p class="card-text"><strong>Nama Petugas Pengelola:</strong> {{ $instrumenPengawasan->petugas_nama }}</p>
            <p class="card-text"><strong>Status:</strong> {{ $instrumenPengawasan->status }}</p>
            <p class="card-text"><strong>Nama Perencana:</strong> {{ $instrumenPengawasan->perencana_nama }}</p>
            <p class="card-text"><strong>Isi:</strong> {{ $instrumenPengawasan->isi }}</p>

            @if (Auth::user()->role == 'pjk' || Auth::user()->role == 'perencana')
            <a href="{{ route(Auth::user()->role . '.instrumen-pengawasan.edit', $instrumenPengawasan->id) }}" class="">Edit</a>
            @endif
            @if (Auth::user()->role == 'perencana')
                <form action="{{ route('instrumen-pengawasan.delete', $instrumenPengawasan->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="">Delete</button>
                </form>
            @endif

        </div>
    </div>
</div>
