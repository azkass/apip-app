@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-10 p-6 bg-white shadow-md rounded-xl">
    <h2 class="text-xl font-semibold mb-6">Tambah Monitoring Evaluasi</h2>
    <form action="{{ route('monitoring-evaluasi.store') }}" method="POST">
        @csrf
        <div class="mb-6">
            <h3 class="font-medium text-gray-700 mb-2">Evaluasi SOP:</h3>
            <p class="text-lg font-semibold">{{ $sop_nama }}</p>
            <input type="hidden" name="sop_id" value="{{ $sop_id }}">
        </div>

        <div class="mb-6">
            <label for="penilaian_penerapan" class="block text-gray-700 font-medium mb-2">Penilaian Penerapan</label>
            <input type="text" name="penilaian_penerapan" autocomplete="off" class="w-full p-2 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
        </div>

        <div class="mb-6">
            <label for="catatan_penilaian" class="block text-gray-700 font-medium mb-2">Catatan Penilaian</label>
            <input type="text" name="catatan_penilaian" autocomplete="off" class="w-full p-2 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
        </div>

        <div class="mb-6">
            <label for="tindakan" class="block text-gray-700 font-medium mb-2">Tindakan</label>
            <input type="text" name="tindakan" autocomplete="off" class="w-full p-2 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
        </div>

        <div class="mb-6">
            <h3 class="font-medium text-gray-700 mb-3">Evaluasi SOP:</h3>
            <div class="space-y-4">
                @foreach ([
                    'mampu_mendorong_kinerja' => 'Mampu Mendorong Kinerja',
                    'mampu_dipahami' => 'Mampu Dipahami',
                    'mudah_dilaksanakan' => 'Mudah Dilaksanakan',
                    'dapat_menjalankan_peran' => 'Dapat Menjalankan Peran',
                    'mampu_mengatasi_permasalahan' => 'Mampu Mengatasi Permasalahan',
                    'mampu_menjawab_kebutuhan' => 'Mampu Menjawab Kebutuhan',
                    'sinergi_dengan_lainnya' => 'Sinergi Dengan Lainnya',
                ] as $field => $label)
                    <div class="border border-gray-200 rounded-md p-4">
                        <div class="flex items-start mb-2">
                            <span class="font-medium text-gray-800">{{ $label }}</span>
                        </div>
                        <div class="mt-3 flex space-x-6">
                            <label class="inline-flex items-center">
                                <input type="radio" name="{{ $field }}" value="ya" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                <span class="ml-2 text-black font-medium">Ya</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="{{ $field }}" value="tidak" class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50" required>
                                <span class="ml-2 text-black font-medium">Tidak</span>
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex justify-between mt-6">
            <a href="{{ route('monitoring-evaluasi.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition">
                Batal
            </a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                Simpan
            </button>
        </div>
    </form>
</div>
<script>
document.querySelector('form').addEventListener('submit', function(e) {
    const radioGroups = document.querySelectorAll('input[type="radio"]');
    const requiredFields = document.querySelectorAll('input[required], select[required]');
    let isValid = true;

    requiredFields.forEach(field => {
        if (!field.value) {
            isValid = false;
            field.classList.add('border-red-500');
        } else {
            field.classList.remove('border-red-500');
        }
    });

    if (!isValid) {
        e.preventDefault();
        alert('Harap lengkapi semua field yang wajib diisi.');
    }
});
</script>
@endsection
