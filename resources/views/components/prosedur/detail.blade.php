<div class="container p-4">
    <div class="card">
        <div class="card-body">
            <p class="card-text"><strong>Nomor:</strong> {{ $prosedurPengawasan->nomor }}</p>
            <p class="card-text"><strong>Judul:</strong> {{ $prosedurPengawasan->judul }}</p>
            <p class="card-text"><strong>Status:</strong> {{ ucfirst($prosedurPengawasan->status) }}</p>
            <p class="card-text"><strong>Nama Perencana:</strong> {{ $prosedurPengawasan->perencana_nama }}</p>
            <p class="card-text"><strong>Nama Petugas Pengelola:</strong> {{ $prosedurPengawasan->petugas_nama }}</p>

            @if (Auth::user()->role == 'pjk' || Auth::user()->role == 'perencana')
                <form action="{{ route(Auth::user()->role . '.prosedur-pengawasan.edit', $prosedurPengawasan->id) }}" method="GET" class="mt-4 inline-block">
                    <button type="submit" class="cursor-pointer px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-semibold rounded-md">
                        Edit
                    </button>
                </form>
            @endif
            @if (Auth::user()->role == 'perencana')
                <form action="{{ route('perencana.prosedur-pengawasan.delete', $prosedurPengawasan->id) }}" method="POST" class="" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="cursor-pointer mt-4 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-md">Delete</button>
                </form>
            @endif

        </div>
    </div>
</div>
