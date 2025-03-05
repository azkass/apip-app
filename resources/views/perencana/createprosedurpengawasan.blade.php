@extends('layouts.app')
@section('content')
    <h1>Buat Prosedur Pengawasan</h1>
    <button id="btnTambah" class="cursor-pointer bg-blue-400 hover:bg-blue-500 text-white px-4 py-2 rounded-md">Tambah</button>
    <!-- Container untuk form dropdown yang akan ditambahkan -->
    <div id="formContainer" class="mt-4"></div>
    <!-- Template untuk form -->
    <template id="formTemplate">
        <div class="form-item bg-gray-50 px-4 py-2 rounded-md mb-1">
            <div class="flex items-center">
                <div class="mb-1 flex">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Aktor <span class="actor-number">1</span> :</label>
                    <select class="form-select w-52 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-1 px-3 border ml-2">
                        <option value="" selected disabled>-- Pilih Aktor --</option>
                        <option value="koordinator">Koordinator</option>
                        <option value="tim_audit">Tim Audit</option>
                    </select>
                </div>
                <div class="ml-3 self-end mb-3">
                    <button class="delete-form bg-red-500 hover:bg-red-600 text-white font-medium py-1 px-3 rounded text-sm focus:outline-none focus:ring-2 focus:ring-red-300 transition duration-300">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </template>
    <button id="btnLanjut" class="cursor-pointer bg-blue-400 hover:bg-blue-500 text-white px-4 py-2 mt-4 rounded-md">Lanjut</button>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnTambah = document.getElementById('btnTambah');
            const formContainer = document.getElementById('formContainer');
            const formTemplate = document.getElementById('formTemplate');

            // Fungsi untuk memperbarui penomoran
            function updateNumbering() {
                const formItems = formContainer.querySelectorAll('.form-item');
                formItems.forEach((item, index) => {
                    const actorNumber = item.querySelector('.actor-number');
                    actorNumber.textContent = index + 1;
                });
            }

            btnTambah.addEventListener('click', function() {
                // Clone template form
                const formClone = document.importNode(formTemplate.content, true);

                // Tambahkan event listener untuk tombol hapus
                const deleteButton = formClone.querySelector('.delete-form');
                deleteButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Hapus elemen form
                    this.closest('.form-item').remove();
                    // Perbarui penomoran setelah menghapus
                    updateNumbering();
                });

                // Tambahkan form ke container
                formContainer.appendChild(formClone);

                // Perbarui penomoran setelah menambah
                updateNumbering();
            });
        });
    </script>
@endsection
