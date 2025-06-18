@extends('layouts.app')
@section('content')
<div class="bg-white rounded-md mb-4 ">
    <!-- Form Input Pelaksana -->
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
        <div id="graphContainerBox" class="overflow-auto">
            <div id="graphContainer"></div>
        </div>
        <button id="printXml" class="bg-gray-500 hover:bg-gray-600 cursor-pointer h-10 text-base text-white py-2 px-4 rounded-sm">Print XML</button>
    </div>

    <!-- console.log XML Diagram -->

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
            preview,
            draw,
            printXml, //console.log XML Diagram
        } from "{{ Vite::asset('resources/js/graph.js') }}";

        // Inisialisasi event listeners
        document.addEventListener("DOMContentLoaded", () => {
            // Tambahkan form pertama
            addActor();

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

            // console.log XML Diagram
            const printXmlBtn = document.querySelector("#printXml");
            if (printXmlBtn) printXmlBtn.addEventListener("click", printXml);

            const outputXmlBtn = document.querySelector("#outputXml");
            if (outputXmlBtn) outputXmlBtn.addEventListener("click", outputXml);

            // Event delegation untuk actor-select
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

        <!-- Load Vite script khusus untuk page ini -->
        @vite('resources/js/graph.js')
@endpush
