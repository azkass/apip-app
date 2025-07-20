@extends('layouts.app')
@section('content')
<div class="container ml-2 sm:ml-8 mt-1 sm:mt-8">
    <!-- Search and Filter Section -->
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
        <form method="GET" action="{{ route('regulasi.index') }}" class="space-y-4">
            <!-- Search Bar -->
            <div class="flex items-center space-x-4">
                <div class="flex border-2 border-gray-300 overflow-hidden rounded-md h-10">
                    <input type="text" name="search" value="{{ $currentSearch ?? '' }}"
                           placeholder="Cari Regulasi (Tahun, Nomor, atau Tentang)" autocomplete="off"
                           class="w-96 outline-none bg-white text-gray-600 text-sm px-4 py-3" />
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition">
                    <i class="fas fa-search mr-1"></i> Cari
                </button>
                <div class="flex items-center space-x-2">
                    <!-- <label for="jenis_peraturan" class="text-sm font-medium text-gray-700">Jenis:</label> -->
                    <select name="jenis_peraturan" id="jenis_peraturan"
                            class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring focus:ring-blue-200">
                        <option value="">Semua Jenis</option>
                        @foreach($jenisPeraturanOptions as $value => $label)
                            <option value="{{ $value }}" {{ $currentJenisPeraturan == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center space-x-2">
                    <!-- <label for="status" class="text-sm font-medium text-gray-700">Status:</label> -->
                    <select name="status" id="status"
                            class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring focus:ring-blue-200">
                        <option value="">Semua Status</option>
                        @foreach($statusOptions as $value => $label)
                            <option value="{{ $value }}" {{ $currentStatus == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if($currentJenisPeraturan || $currentStatus || $currentSearch)
                    <a href="{{ route('regulasi.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded-md text-sm transition">
                        <i class="fas fa-times mr-1"></i> Reset
                    </a>
                @endif
                <a href="{{ route('regulasi.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md transition">
                    <i class="fas fa-plus mr-1"></i> Tambah
                </a>
            </div>
        </form>

        <!-- Filter Summary -->
        @if($currentJenisPeraturan || $currentStatus || $currentSearch)
            <div class="mt-4 p-3 bg-blue-50 rounded-md">
                <p class="text-sm text-blue-800">
                    <strong>Filter Aktif:</strong>
                    @if($currentSearch)
                        Pencarian: "{{ $currentSearch }}"
                    @endif
                    @if($currentJenisPeraturan)
                        @if($currentSearch) | @endif
                        Jenis: {{ $jenisPeraturanOptions[$currentJenisPeraturan] }}
                    @endif
                    @if($currentStatus)
                        @if($currentSearch || $currentJenisPeraturan) | @endif
                        Status: {{ $statusOptions[$currentStatus] }}
                    @endif
                    ({{ count($regulasi) }} hasil ditemukan)
                </p>
            </div>
        @endif
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-lg shadow-sm">
        <table class="border-collapse w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">No</th>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">Tahun</th>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">Nomor</th>
                    <th class="border border-gray-300 px-4 py-3 text-left font-semibold">Tentang</th>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">Jenis Peraturan</th>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">Status</th>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">File</th>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">Tautan</th>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($regulasi as $index => $item)
                    <tr class="hover:bg-gray-50 transition-colors" data-regulasi-row>
                        <td class="border border-gray-300 px-4 py-3 text-center">{{ $index + 1 }}</td>
                        <td class="border border-gray-300 px-4 py-3 text-center font-medium">{{ $item->tahun }}</td>
                        <td class="border border-gray-300 px-4 py-3 text-center font-medium">{{ $item->nomor }}</td>
                        <td class="border border-gray-300 px-4 py-3">
                            <div class="max-w-md">
                                {{ Str::limit($item->tentang, 100) }}
                                @if(strlen($item->tentang) > 100)
                                    <button onclick="toggleFullText(this)" class="text-blue-500 hover:text-blue-700 text-xs ml-1">
                                        Lihat lebih
                                    </button>
                                    <div class="hidden full-text">{{ $item->tentang }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="border border-gray-300 px-4 py-3 text-center">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                @if($item->jenis_peraturan == 'peraturan_bps') bg-blue-100 text-blue-800
                                @elseif($item->jenis_peraturan == 'peraturan_kepala_bps') bg-green-100 text-green-800
                                @elseif($item->jenis_peraturan == 'surat_edaran_kepala_bps') bg-yellow-100 text-yellow-800
                                @elseif($item->jenis_peraturan == 'keputusan_kepala_bps') bg-purple-100 text-purple-800
                                @elseif($item->jenis_peraturan == 'surat_edaran_irtama_bps') bg-orange-100 text-orange-800
                                @elseif($item->jenis_peraturan == 'keputusan_irtama_bps') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucwords(str_replace('_', ' ', $item->jenis_peraturan)) }}
                            </span>
                        </td>
                        <td class="border border-gray-300 px-4 py-3 text-center">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                @if($item->status == 'berlaku') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td class="border border-gray-300 px-4 py-3 text-center">
                            @if($item->file)
                                <a href="{{ route('regulasi.view', $item->id) }}" target="_blank"
                                   class="inline-flex px-3 py-1 text-xs font-semibold rounded bg-green-500 hover:bg-green-600 text-white transition">
                                    <i class="fas fa-eye mr-1"></i> Lihat PDF
                                </a>
                            @else
                                <span class="text-gray-400 text-xs">Tidak ada file</span>
                            @endif
                        </td>
                        <td class="border border-gray-300 px-4 py-3 text-center">
                            @if($item->tautan)
                                <a href="{{ $item->tautan }}" target="_blank"
                                   class="inline-flex px-3 py-1 text-xs font-semibold rounded bg-blue-500 hover:bg-blue-600 text-white transition">
                                    <i class="fas fa-external-link-alt mr-1"></i> Buka
                                </a>
                            @else
                                <span class="text-gray-400 text-xs">Tidak ada tautan</span>
                            @endif
                        </td>
                        <td class="border border-gray-300 px-4 py-3 text-center">
                            <div class="flex justify-center space-x-1">
                                <a href="{{ route('regulasi.detail', $item->id) }}"
                                   class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs font-semibold transition">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('regulasi.edit', $item->id) }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs font-semibold transition">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('regulasi.delete', $item->id) }}" method="POST" class="inline-block"
                                      onsubmit="return confirm('Yakin ingin menghapus regulasi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs font-semibold transition">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="border border-gray-300 px-4 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                @if($currentSearch || $currentJenisPeraturan || $currentStatus)
                                    <p class="text-lg font-medium">Tidak ada hasil yang ditemukan</p>
                                    <p class="text-sm">Coba ubah kriteria pencarian atau filter Anda</p>
                                    <a href="{{ route('regulasi.index') }}" class="mt-2 text-blue-500 hover:text-blue-700 text-sm">
                                        Lihat semua regulasi
                                    </a>
                                @else
                                    <p class="text-lg font-medium">Belum ada data regulasi</p>
                                    <p class="text-sm">Klik "Tambah" untuk membuat regulasi baru</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
// Toggle full text function
function toggleFullText(button) {
    const fullTextDiv = button.nextElementSibling;
    const shortText = button.previousSibling;

    if (fullTextDiv.classList.contains('hidden')) {
        fullTextDiv.classList.remove('hidden');
        shortText.style.display = 'none';
        button.textContent = 'Lihat sedikit';
    } else {
        fullTextDiv.classList.add('hidden');
        shortText.style.display = 'inline';
        button.textContent = 'Lihat lebih';
    }
}

// Auto-submit filter form when dropdown changes
document.getElementById('jenis_peraturan').addEventListener('change', function() {
    this.form.submit();
});

document.getElementById('status').addEventListener('change', function() {
    this.form.submit();
});

// Clear search when escape key is pressed
document.querySelector('input[name="search"]').addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        this.value = '';
    }
});
</script>
@endsection
