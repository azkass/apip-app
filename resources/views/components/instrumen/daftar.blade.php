<div class="container p-4">
    <h1 class="text-2xl font-medium mb-4">Daftar Instrumen Pengawasan</h1>
    @if (Auth::user()->role == 'perencana')
    <a href="{{ route('instrumen-pengawasan.create') }}" class="">Create New</a>
    @endif
    <table class="table mt-4">
        <thead>
            <tr class="text-center">
                <th class="w-32">Judul</th>
                <th class="w-48">Petugas Pengelola</th>
                <th class="w-32">Status</th>
                <th class="w-48">Perencana</th>
                <th class="w-48">Terakhir diubah</th>
                @if (Auth::user()->role == 'perencana'||Auth::user()->role == 'pjk')
                    <th class="w-32">Actions</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($instrumenPengawasan as $instrumen)
                <tr class="text-center">
                    <td>
                        <a href="{{ route(Auth::user()->role . '.instrumen-pengawasan.detail', $instrumen->id) }}">
                            {{ $instrumen->judul }}
                        </a>
                    </td>
                    <td>{{ $instrumen->petugas_nama }}</td>
                    <td>{{ ucfirst($instrumen->status) }}</td>
                    <td>{{ $instrumen->perencana_nama }}</td>
                    <td>{{ $instrumen->updated_at }}</td>
                    <td>
                        @if (Auth::user()->role == 'pjk')
                        <a href="{{ route('pjk.instrumen-pengawasan.edit', $instrumen->id) }}" class="">Edit</a>
                        @endif
                        @if (Auth::user()->role == 'perencana')
                        <a href="{{ route('perencana.instrumen-pengawasan.edit', $instrumen->id) }}" class="">Edit</a>
                            <form action="{{ route('instrumen-pengawasan.delete', $instrumen->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="cursor-pointer">Delete</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
