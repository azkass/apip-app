@extends('layouts.app')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
window.coverData = {
    dasarHukum: @json($prosedurPengawasan->dasar_hukum ?? []),
    keterkaitan: @json($prosedurPengawasan->keterkaitan ?? []),
    peringatan: @json($prosedurPengawasan->peringatan ?? []),
    kualifikasi: @json($prosedurPengawasan->kualifikasi ?? []),
    peralatan: @json($prosedurPengawasan->peralatan ?? []),
    pencatatan: @json($prosedurPengawasan->pencatatan ?? []),
};
</script>
    <div class="bg-white shadow-md rounded-lg px-6 py-4 sm:m-4 lg:mx-32 md:my-8">
    <form id="editCoverForm" method="POST" action="{{ route('prosedur-pengawasan.update-cover', $prosedurPengawasan->id) }}">
    @csrf
    @method('PUT')
        <div class="space-y-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-semibold text-gray-800">Edit Prosedur Pengawasan</h2>
                <a href="{{ route('prosedur-pengawasan.edit', $prosedurPengawasan->id) }}"
                    class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-md transition shadow-md">
                    Kembali
                </a>
            </div>

            <!-- Dasar Hukum -->
            <div>
                <label class="block text-md font-medium text-black mb-1">Dasar Hukum</label>
                <div id="dasarHukumList"></div>
                <div class="flex mt-2">
                    <button type="button"
                        class="mr-2 px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded transition"
                        onclick="addField('dasarHukum')">
                        Tambah
                    </button>
                    <button type="button" id="dasarHukum-remove-btn"
                        class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded transition"
                        onclick="removeLastField('dasarHukum')">
                        Hapus
                    </button>
                </div>
            </div>
            <!-- Keterkaitan -->
            <div>
                <label class="block text-md font-medium text-black mb-1">Keterkaitan</label>
                <div id="keterkaitanList"></div>
                <div class="flex mt-2">
                    <button type="button" class="mr-2 px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded transition" onclick="addField('keterkaitan')">Tambah</button>
                    <button type="button" id="keterkaitan-remove-btn" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded transition" onclick="removeLastField('keterkaitan')">Hapus</button>
                </div>
            </div>
            <!-- Peringatan -->
            <div>
                <label class="block text-md font-medium text-black mb-1">Peringatan</label>
                <div id="peringatanList"></div>
                <div class="flex mt-2">
            </div>
            </div>
            <!-- Kualifikasi Pelaksanaan -->
            <div>
                <label class="block text-md font-medium text-black mb-1">Kualifikasi Pelaksanaan</label>
                <div id="kualifikasiList"></div>
            </div>
            <!-- Peralatan/Perlengkapan -->
            <div>
                <label class="block text-md font-medium text-black mb-1">Peralatan/Perlengkapan</label>
                <div id="peralatanList"></div>
                <button type="button" class="mr-1 px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded mt-2 transition" onclick="addField('peralatan')">Tambah</button>
                <button type="button" id="peralatan-remove-btn" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded transition" onclick="removeLastField('peralatan')">Hapus</button>
            </div>
            <!-- Pencatatan dan Pendataan -->
            <div>
                <label class="block text-md font-medium text-black mb-1">Pencatatan dan Pendataan</label>
                <div id="pencatatanList"></div>

            </div>
        </div>
        <div class="flex justify-start items-center mt-6">
            <button type="submit" id="btnPreview" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">Preview</button>
            <a href="{{ route('prosedur-pengawasan.edit-body', $prosedurPengawasan->id) }}" id="btnLanjut" class="ml-2 px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition hidden">Simpan</a>
        </div>
    </form>
    <!-- Container hasil generate cover mxGraph -->
    <div id="coverContainer" class="mt-8" style="transform: scale(0.75); transform-origin: top left;"></div>

</div>
@endsection
@push('scripts')
    <!-- Set mxBasePath sebelum load mxClient -->
    <script>
        window.mxBasePath = '/vendor/mxgraph';
        function showLanjutButton() {
            var btn = document.getElementById('btnLanjut');
            if (btn) btn.classList.remove('hidden');
        }
    </script>
    <script src="/vendor/mxgraph/js/mxClient.js"></script>
    @vite('resources/js/editCover.js')
@endpush
