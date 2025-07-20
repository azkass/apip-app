<div class="max-w-2xl mx-auto mt-10 p-6 bg-white shadow-md rounded-xl">
    @if (Auth::user()->role == 'perencana' || Auth::user()->role == 'pjk')
        <form action="{{ route('instrumen-pengawasan.update', $instrumenPengawasan->id) }}" method="POST" enctype="multipart/form-data">
    @endif
        @csrf
        @method('PUT')
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Edit Instrumen Pengawasan</h2>
            <a href="{{ route('instrumen-pengawasan.detail', $instrumenPengawasan->id) }}"
               class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-md transition">
                Kembali
            </a>
        </div>
        <!-- Kode Instrumen -->
        <div class="mb-4">
            <label for="kode" class="block font-medium text-gray-700">Kode Instrumen</label>
            <input type="text" name="kode" id="kode" value="{{ $instrumenPengawasan->kode }}" required
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
        </div>

        <!-- Hasil Kerja -->
        <div class="mb-4">
            <label for="hasil_kerja" class="block font-medium text-gray-700">Hasil Kerja</label>
            <input type="text" name="hasil_kerja" id="hasil_kerja" value="{{ $instrumenPengawasan->hasil_kerja }}" required
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
        </div>

        <!-- Nama Instrumen -->
        <div class="mb-4">
            <label for="nama" class="block font-medium text-gray-700">Nama Instrumen</label>
            <input type="text" name="nama" id="nama" value="{{ $instrumenPengawasan->nama }}" required
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
        </div>

        <div class="mb-4">
            <label for="penyusun_id" class="block font-medium text-gray-700">Petugas Penyusun</label>
            <select name="penyusun_id" id="penyusun_id" required
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
                @foreach ($is_pjk as $user)
                    <option value="{{ $user->id }}" {{ $instrumenPengawasan->penyusun_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="deskripsi" class="block font-medium text-gray-700">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi"
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">{{ $instrumenPengawasan->deskripsi }}</textarea>
        </div>

        <div class="mb-4">
            <label for="pdf" class="block font-medium text-gray-700">File PDF</label>
            <input type="file" name="pdf" id="pdf" accept="application/pdf"
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">

            @if($instrumenPengawasan->file)
            <div class="mt-2 text-sm text-gray-600">
                File saat ini: {{ $instrumenPengawasan->file }}
                <a href="{{ route('instrumen-pengawasan.download', $instrumenPengawasan->id) }}" class="text-blue-500 hover:underline">
                    Download
                </a>
            </div>
            @endif
        </div>

        <div class="mb-4">
            <label for="status" class="block font-medium text-gray-700">Status</label>
            <select name="status" id="status" required
                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
                <option value="draft" {{ $instrumenPengawasan->status == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="diajukan" {{ $instrumenPengawasan->status == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                <option value="disetujui" {{ $instrumenPengawasan->status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
            </select>
        </div>

        <div class="flex justify-end space-x-4"> <!-- Container flex untuk kedua button -->
    <!-- Form Simpan Perubahan -->
    <form method="POST" action="{{ route('instrumen-pengawasan.update', $instrumenPengawasan->id) }}" class="flex-1">
        @csrf
        @method('PUT')
        <input type="hidden" name="pembuat_id" value="{{ $instrumenPengawasan->pembuat_id }}">

        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
            Simpan Perubahan
        </button>
    </form>
</div>

<script>
    document.getElementById('pdf').addEventListener('change', function() {
        var fileName = this.files[0] ? this.files[0].name : 'Pilih file...';
        this.nextElementSibling ? this.nextElementSibling.textContent = fileName : null;
    });
</script>
