@extends('layouts.app')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container sm:mx-4 sm:my-4 md:mx-8 px-6 py-4 md:my-8">
    <div class="bg-white shadow-md rounded-lg mb-6 px-6" id="prosedur-container" data-prosedur-id="{{ $prosedurPengawasan->id }}" data-prosedur-isi="{{ $prosedurPengawasan->isi ?? '' }}">
        <div class="flex justify-between items-center mb-4 pr-4">
            <h2 class="text-xl font-semibold text-gray-800">Edit Prosedur Pengawasan</h2>
            <a href="{{ route('prosedur-pengawasan.edit-cover', $prosedurPengawasan->id) }}"
                class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-md transition shadow-md">
                Kembali
            </a>
        </div>
        <!-- Form Input Pelaksana -->
        @csrf
        <div class="mb-6" id="formContainer">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Tambahkan Pelaksana</h2>
            <template id="formTemplate">
                <div class="flex items-center mb-3 form-item w-full max-w-lg rounded-sm">
                    <label class="block w-32 text-sm font-medium text-gray-700">Pelaksana <span class="actor-number">1</span> :</label>
                    <select class="form-select actor-select w-64 rounded-md border border-gray-300 shadow-sm py-2 px-3 ml-2 focus:ring focus:ring-blue-200">
                        <option value="" selected disabled>-- Pilih Pelaksana --</option>
                        <option value="Inspektur Wilayah">Inspektur Wilayah</option>
                        <option value="Pengendali Teknis">Pengendali Teknis</option>
                        <option value="Ketua Tim">Ketua Tim</option>
                        <option value="Anggota Tim">Anggota Tim</option>
                        <option value="new-actor">Pelaksana Baru</option>
                    </select>
                    <input type="text" autocomplete="off" class="hidden custom-actor-input w-64 rounded-md border-gray-300 shadow-sm py-2 px-3 ml-2 focus:ring focus:ring-blue-200" placeholder="Masukkan pelaksana baru">
                </div>
            </template>
        </div>
        <!-- Button Input Pelaksana -->
        <div id="formContainer" class="flex justify-between p-4 w-full border-t border-gray-200">
            <div class="space-x-2">
                <button id="add-actor" class="bg-blue-500 hover:bg-blue-600 h-10 text-sm font-semibold text-white px-4 py-2 rounded-md transition">
                    Tambah Pelaksana
                </button>
                <button id="delete-last-actor" class="bg-red-500 hover:bg-red-600 h-10 text-sm font-semibold text-white py-2 px-4 rounded-md transition">
                    Hapus Pelaksana
                </button>
            </div>
            <div>
                <button id="save-actor" class="bg-green-600 hover:bg-green-700 h-10 text-sm font-semibold text-white px-4 py-2 rounded-md transition">
                    Simpan
                </button>
            </div>
        </div>
    </div>

    <!-- Form Input Aktivitas -->
    <div id="diagramSection" class="bg-white shadow-md rounded-lg mb-6 p-6 hidden">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Tambahkan Aktivitas</h2>
        <div id="diagramTable" class="mb-6">
            <!-- Diagram table will be added here -->
        </div>
        <!-- Button Aktivitas -->
        <div class="flex justify-between w-full border-t border-gray-200 pt-4">
            <div class="space-x-2">
                <button id="add-activity" class="bg-blue-500 hover:bg-blue-600 cursor-pointer h-10 text-sm text-white font-semibold py-2 px-4 rounded-md transition">
                    Tambah Aktivitas
                </button>
                <button id="delete-last-activity" class="bg-red-500 hover:bg-red-600 cursor-pointer h-10 text-sm text-white font-semibold py-2 px-4 rounded-md transition">
                    Hapus Aktivitas
                </button>
            </div>
            <button id="preview" class="bg-green-600 hover:bg-green-700 cursor-pointer h-10 text-sm text-white font-semibold py-2 px-4 rounded-md transition">
                Simpan
            </button>
        </div>
    </div>

    <!-- Output Diagram Preview -->
    <div id="previewBox" class="hidden bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Diagram Preview</h2>
        <div class="mb-4">
            <a href="{{ route('prosedur-pengawasan.show', $prosedurPengawasan->id) }}"
               class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-md transition">
                   Selesai
            </a>
        </div>
        <div id="graphContainerBox" class="">
            <div id="graphContainer" style="transform: scale(0.8); transform-origin: top left;"></div>
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

        <!-- Load Vite script khusus untuk page ini -->
        @vite('resources/js/editBody.js')
@endpush
