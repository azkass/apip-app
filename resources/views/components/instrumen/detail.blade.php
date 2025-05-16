<div class="container p-4">
    <div class="card">
        <div class="card-body">
            <p class="card-text"><strong>Judul:</strong> {{ $instrumenPengawasan->judul }}</p>
            <p class="card-text"><strong>Nama Petugas Pengelola:</strong> {{ $instrumenPengawasan->petugas_nama }}</p>
            <p class="card-text"><strong>Status:</strong> {{ ucfirst($instrumenPengawasan->status) }}</p>
            <p class="card-text"><strong>Nama Perencana:</strong> {{ $instrumenPengawasan->perencana_nama }}</p>
            <p class="card-text"><strong>Deskripsi:</strong> {{ $instrumenPengawasan->deskripsi }} </p>
            <p class="card-text"><strong>Dokumen:</strong> {{ $instrumenPengawasan->file }}
                <a href="{{ route('instrumen-pengawasan.download', $instrumenPengawasan->id) }}" class="ml-4 py-2 px-4 bg-red-500 hover:bg-red-600 rounded-md text-white">
                    <i class="fas fa-download"></i> Unduh
                </a>
            </p>

            @if (Auth::user()->role == 'pjk' || Auth::user()->role == 'perencana')
                <form action="{{ route(Auth::user()->role . '.instrumen-pengawasan.edit', $instrumenPengawasan->id) }}" method="GET" class="mt-4 inline-block">
                    <button type="submit" class="cursor-pointer px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-semibold rounded-md">
                        Edit
                    </button>
                </form>
            @endif
            @if (Auth::user()->role == 'perencana')
                <form action="{{ route('instrumen-pengawasan.delete', $instrumenPengawasan->id) }}" method="POST" class="" style="display:inline;>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="cursor-pointer mt-4 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-md">Delete</button>
                </form>
            @endif

        </div>
    </div>
</div>
