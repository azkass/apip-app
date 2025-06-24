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
<script type="module">
        import {
            addActor,
            deleteLastActor,
            saveActor,
            addCustomActor,
            addActivity,
            deleteLastActivity,
            setupActivityForm,
            loadData,
            loadExistingData,
            preview,
            draw,
        } from "{{ Vite::asset('resources/js/graph.js') }}";

        // Inisialisasi event listeners
        document.addEventListener("DOMContentLoaded", () => {
            // Cek apakah ada data yang sudah tersimpan
            const prosedurContainer = document.getElementById('prosedur-container');
            const prosedurIsi = prosedurContainer.dataset.prosedurIsi;
            
            // For debugging - uncomment in development
            // const debugInfo = document.getElementById('debugInfo');
            // const debugContent = document.getElementById('debugContent');
            // debugInfo.classList.remove('hidden');
            
            if (prosedurIsi && prosedurIsi !== '') {
                try {
                    // console.log('Loading existing data from JSON');
                    const jsonData = JSON.parse(prosedurIsi);
                    
                    // Display JSON data in debug area for inspection
                    // if (debugContent) {
                    //     debugContent.textContent = JSON.stringify(jsonData, null, 2);
                    // }
                    
                    if (jsonData && jsonData.actorName && jsonData.actorName.length > 0) {
                        // Step 1: First create all the actor forms needed
                        for (let i = 0; i < jsonData.actorName.length; i++) {
                            addActor();
                        }
                        
                        // Step 2: Set the actor values in the dropdowns
                        const actorSelects = document.querySelectorAll('.actor-select');
                        actorSelects.forEach((select, index) => {
                            if (index < jsonData.actorName.length) {
                                const actorValue = jsonData.actorName[index];
                                
                                // Check if the actor value exists in the options
                                const optionExists = Array.from(select.options).some(
                                    option => option.value === actorValue
                                );
                                
                                if (optionExists) {
                                    select.value = actorValue;
                                } else if (actorValue) {
                                    // Handle custom actor
                                    select.value = 'new-actor';
                                    addCustomActor(select);
                                    const customInput = select.parentElement.querySelector('.custom-actor-input');
                                    if (customInput) {
                                        customInput.value = actorValue;
                                        customInput.classList.remove('hidden');
                                    }
                                }
                            }
                        });
                        
                        // Ubah urutan: load existing data terlebih dahulu
                        // untuk mengisi data aktivitas dan bentuk
                        loadExistingData(jsonData);
                        
                        // Lalu panggil saveActor tapi cegah menimpa data
                        window.doNotOverwriteActivities = true;
                        saveActor();
                        window.doNotOverwriteActivities = false;
                    } else {
                        // console.warn('No valid actor data found in JSON');
                        addActor(); // Add default first actor form
                    }
                } catch (e) {
                    console.error('Error parsing JSON data:', e);
                    addActor(); // Add default first actor form if loading fails
                }
            } else {
                // console.log('No existing data found, starting with a blank form');
                addActor(); // Add default first actor form
            }

            // Set up event listeners
            const addActorBtn = document.querySelector("#add-actor");
            if (addActorBtn) addActorBtn.addEventListener("click", addActor);

            const deleteActorBtn = document.querySelector("#delete-last-actor");
            if (deleteActorBtn) deleteActorBtn.addEventListener("click", deleteLastActor);

            const saveActorBtn = document.querySelector("#save-actor");
            if (saveActorBtn) saveActorBtn.addEventListener("click", saveActor);

            const addActivityBtn = document.querySelector("#add-activity");
            if (addActivityBtn) addActivityBtn.addEventListener("click", addActivity);

            const deleteActivityBtn = document.querySelector("#delete-last-activity");
            if (deleteActivityBtn) deleteActivityBtn.addEventListener("click", deleteLastActivity);

            const previewBtn = document.querySelector("#preview");
            if (previewBtn) previewBtn.addEventListener("click", preview);

            // Event delegation for actor-select changes
            const formContainer = document.querySelector("#formContainer");
            if (formContainer) {
                formContainer.addEventListener("change", function(event) {
                    if (event.target.classList.contains("actor-select")) {
                        addCustomActor(event.target);
                    }
                });
            }
        });
    </script>

    <!-- Set mxBasePath sebelum load mxClient -->
        <script>
            window.mxBasePath = '/vendor/mxgraph';
        </script>

        <!-- Load mxGraph core -->
        <script src="/vendor/mxgraph/js/mxClient.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

        <!-- Load Vite script khusus untuk page ini -->
        @vite('resources/js/graph.js')
@endpush
