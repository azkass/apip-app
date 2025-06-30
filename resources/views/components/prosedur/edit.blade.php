<div class="p-8">
    @if (Auth::user()->role == 'pjk' || Auth::user()->role == 'perencana')
        <form action="{{ route(Auth::user()->role .'.prosedur-pengawasan.update', $prosedurPengawasan->id) }}" method="POST" enctype="multipart/form-data">
    @endif
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="judul" class="font-bold">Nama SOP : </label>
            <input type="text" name="judul" class="form-control" value="{{ $prosedurPengawasan->judul }}" required>
        </div>
        <div class="form-group">
            <label for="nomor" class="font-bold">Nomor SOP : </label>
            <input type="text" name="nomor" class="form-control" value="{{ $prosedurPengawasan->nomor }}" required>
        </div>
        <div class="form-group">
            <label for="penyusun_id" class="font-bold">Petugas Penyusun : </label>
            <select name="penyusun_id" class="form-control" required>
                @foreach ($is_pjk as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="status" class="font-bold">Status : </label>
            <select name="status" class="form-control" required>
                <option value="draft">Draft</option>
                <option value="diajukan">Diajukan</option>
                <option value="disetujui">Disetujui</option>
            </select>
        </div>
        <input type="hidden" name="pembuat_id" value="{{ $prosedurPengawasan->pembuat_id }}">
        <button type="submit" class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md">Simpan</button>
    </form>
</div>
@push('scripts')
    <!-- Set mxBasePath sebelum load mxClient -->
        <script>
            window.mxBasePath = '/vendor/mxgraph';
        </script>

        <!-- Load mxGraph core -->
        <script src="/vendor/mxgraph/js/mxClient.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

        <!-- Load Vite script khusus untuk page ini -->
        @vite('resources/js/editCover.js')
@endpush
