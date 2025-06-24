@extends('layouts.app')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="bg-white rounded-md mb-4" id="prosedur-container" data-prosedur-id="{{ $prosedurPengawasan->id }}" data-prosedur-isi="{{ $prosedurPengawasan->isi ?? '' }}">
    <!-- Form Input Pelaksana -->
    @csrf
    <div class="p-4" id="formContainer">
        <h2 class="text-xl font-semibold mb-4">Tambahkan Pelaksana</h2>
        <template id="formTemplate">
            <div class="flex items-center mb-2 form-item w-96 rounded-sm">
                <label class="block w-28 text-base font-medium text-gray-700">Pelaksana <span class="actor-number">1</span> :</label>
                <select class="form-select actor-select w-52 rounded-md border border-gray-300 shadow-sm py-1 px-3 ml-2">
                    <option value="" selected disabled>-- Pilih Pelaksana --</option>
                    <option value="Inspektur Wilayah">Inspektur Wilayah</option>
                    <option value="Pengendali Teknis">Pengendali Teknis</option>
                    <option value="Ketua Tim">Ketua Tim</option>
                    <option value="Anggota Tim">Anggota Tim</option>
                    <option value="new-actor">Pelaksana Baru</option>
                </select>
                <input type="text" class="hidden custom-actor-input w-52 rounded-md border-gray-300 shadow-sm py-1 px-3 ml-2" placeholder="Masukkan pelaksana baru">
            </div>
        </template>
    </div>
    <!-- Button Input Pelaksana -->
    <div id="formContainer"  class="flex justify-between p-4 w-full">
        <div>
            <button id="add-actor" class="cursor-pointer bg-blue-500 hover:bg-blue-600 h-10 text-base text-white px-4 py-2 rounded-sm mb-2 mr-2">Tambah Pelaksana</button>
            <button id="delete-last-actor" class="cursor-pointer bg-red-500 hover:bg-red-600 h-10 text-base text-white py-2 px-4 rounded">Hapus Pelaksana</button>
        </div>
        <div>
            <button id="save-actor" class="cursor-pointer bg-green-600 hover:bg-green-700 h-10 text-base text-white px-4 py-2 rounded-sm">Simpan</button>
        </div>
    </div>

    <!-- Form Input Aktivitas -->
    <div id="diagramSection" class="mb-5 p-4 bg-white rounded-lg hidden">
        <h2 class="text-xl font-semibold mb-3">Tambahkan Aktivitas</h2>
        <div id="diagramTable" class="mb-4">
            <!-- Diagram table will be added here -->
        </div>
        <!-- Button Aktivitas -->
        <div class="flex justify-between w-full">
            <div>
                <button id="add-activity" class="bg-blue-500 hover:bg-blue-600 cursor-pointer h-10 text-base text-white py-2 px-4 rounded-sm mb-2 mr-2">Tambah Aktivitas</button>
                <button id="delete-last-activity" class="bg-red-500 hover:bg-red-600 cursor-pointer h-10 text-base text-white py-2 px-4 rounded-sm mr-2">Hapus Aktivitas</button>
            </div>
            <button id="preview" class="bg-green-600 hover:bg-green-700 cursor-pointer h-10 text-base text-white py-2 px-4 rounded-sm">Preview Diagram</button>
        </div>
    </div>

    <!-- Output Diagram Preview -->
    <div id="previewBox" class="hidden p-4 bg-white rounded-lg">
        <h2 class="text-xl font-semibold mb-3">Diagram Preview</h2>
        <a href="{{ route(Auth::user()->role . '.prosedur-pengawasan.detail', $prosedurPengawasan->id) }}"
           class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded transition duration-200">
           Detail Prosedur Pengawasan
        </a>
        <div id="graphContainerBox" class="overflow-auto mt-4">
            <div id="graphContainer"></div>
        </div>
    </div>

    <!-- Debug Info (Hidden in production) -->
    <div id="debugInfo" class="hidden p-4 bg-gray-100 rounded-lg mt-4">
        <h3 class="text-lg font-semibold mb-2">Debug Info</h3>
        <div id="debugContent" class="whitespace-pre-wrap bg-white p-2 rounded border">
            <!-- Debug content will be inserted here -->
        </div>
    </div>

</div>
@endsection

@push('scripts')
    <!-- Set mxBasePath sebelum load mxClient -->
        <script>
            window.mxBasePath = '/vendor/mxgraph';
        </script>

        <!-- Load mxGraph core -->
        <script src="/vendor/mxgraph/js/mxClient.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

        <!-- Load Vite script khusus untuk page ini -->
        @vite('resources/js/editBody.js')
@endpush
