@extends('layouts.app')
@section('content')
<div class="w-full p-8">
    <!-- Filter section with responsive flex-wrap -->
    <div class="flex flex-wrap items-center gap-2 sm:gap-4 mb-6">
        <!-- Search Bar -->
        <div class="flex flex-col w-full mb-2 sm:mb-0 sm:w-auto">
            <label class="text-sm font-medium text-gray-700 mb-1">Cari</label>
            <div class="relative">
                <input type="text" id="search" placeholder="Cari regulasi (tahun, nomor, atau tentang)" autocomplete="off"
                       class="w-full sm:w-64 md:w-80 px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200"/>
            </div>
        </div>

        <!-- Dropdowns container for mobile layout -->
        <div class="flex w-full sm:w-auto gap-2">
            <!-- Filter Jenis Peraturan -->
            <div class="flex flex-col w-1/2 sm:w-auto">
                <label class="text-sm font-medium text-gray-700 mb-1">Jenis</label>
                <div class="relative">
                    <select id="jenisPeraturanFilter"
                            class="block w-full sm:w-52 px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm appearance-none focus:ring focus:ring-blue-200">
                        <option value="">Semua Jenis</option>
                        @foreach($jenisPeraturanOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Filter Status -->
            <div class="flex flex-col w-1/2 sm:w-auto">
                <label class="text-sm font-medium text-gray-700 mb-1">Status</label>
                <div class="relative">
                    <select id="statusFilter"
                            class="block w-full sm:w-32 px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm appearance-none focus:ring focus:ring-blue-200">
                        <option value="">Semua Status</option>
                        @foreach($statusOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Button Tambah -->
        @if (Auth::user()->role == 'perencana')
            <div class="flex flex-col w-full sm:w-auto mt-2 sm:mt-0">
                <label class="text-sm font-medium text-gray-700 mb-1 invisible">Aksi</label>
                <a href="{{ route('regulasi.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition w-full sm:w-auto text-center">
                    <i class="fas fa-plus mr-1"></i> Tambah
                </a>
            </div>
        @endif
    </div>

    <!-- Table Section with overflow handling -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="overflow-x-auto">
            <table class="border-collapse w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 text-center font-semibold">No</th>
                        <th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 text-center font-semibold">Tahun</th>
                        <th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 text-center font-semibold">Nomor</th>
                        <th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 text-center font-semibold">Tentang</th>
                        <th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 text-center font-semibold">Jenis Peraturan</th>
                        <th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 text-center font-semibold">Status</th>
                        <th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 text-center font-semibold">File</th>
                        <th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 text-center font-semibold">Tautan</th>
                        <th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($regulasi as $index => $item)
                        <tr class="hover:bg-gray-50 transition-colors" data-regulasi-row>
                            <td class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 text-center">{{ $index + 1 }}</td>
                            <td class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 text-center font-medium">{{ $item->tahun }}</td>
                            <td class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 text-center font-medium">{{ $item->nomor }}</td>
                            <td class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3">
                                <div class="max-w-md">
                                    {{ $item->tentang }}
                                </div>
                            </td>
                            <td class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 text-center">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    @if($item->jenis_peraturan == 'peraturan_bps') bg-blue-100 text-blue-800
                                    @elseif($item->jenis_peraturan == 'peraturan_kepala_bps') bg-green-100 text-green-800
                                    @elseif($item->jenis_peraturan == 'surat_edaran_kepala_bps') bg-yellow-100 text-yellow-800
                                    @elseif($item->jenis_peraturan == 'keputusan_kepala_bps') bg-purple-100 text-purple-800
                                    @elseif($item->jenis_peraturan == 'surat_edaran_irtama_bps') bg-orange-100 text-orange-800
                                    @elseif($item->jenis_peraturan == 'keputusan_irtama_bps') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ str_replace('Bps', 'BPS', ucwords(str_replace('_', ' ', $item->jenis_peraturan))) }}
                                </span>
                            </td>
                            <td class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 text-center">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    @if($item->status == 'berlaku') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 text-center">
                                @if($item->file)
                                    <a href="{{ route('regulasi.view', $item->id) }}" target="_blank"
                                    class="inline-flex px-2 sm:px-3 py-1 text-xs font-semibold rounded bg-green-500 hover:bg-green-600 text-white transition">
                                        <i class="fas fa-eye mr-1"></i> <span class="hidden sm:inline">Lihat PDF</span><span class="sm:hidden">PDF</span>
                                    </a>
                                @else
                                    <span class="text-gray-400 text-xs">Tidak ada file</span>
                                @endif
                            </td>
                            <td class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 text-center">
                                @if($item->tautan)
                                    <a href="{{ $item->tautan }}" target="_blank"
                                    class="inline-flex px-2 sm:px-3 py-1 text-xs font-semibold rounded bg-blue-500 hover:bg-blue-600 text-white transition">
                                        <i class="fas fa-external-link-alt mr-1"></i> <span class="hidden sm:inline font-semibold">Buka</span><span class="sm:hidden">Link</span>
                                    </a>
                                @else
                                    <span class="text-gray-400 text-xs">Tidak ada tautan</span>
                                @endif
                            </td>
                            <td class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 text-center">
                                <div class="flex flex-wrap justify-center gap-1">
                                    <a href="{{ route('regulasi.detail', $item->id) }}"
                                    class="bg-green-500 hover:bg-green-600 text-white  px-2 sm:px-3 py-1 rounded font-semibold text-xs sm:text-sm transition">
                                        <i class="fas fa-eye"></i>
                                        Lihat
                                    </a>
                                    <a href="{{ route('regulasi.edit', $item->id) }}"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-2 sm:px-3 py-1 rounded font-semibold text-xs sm:text-sm transition">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </a>
                                    <form action="{{ route('regulasi.delete', $item->id) }}" method="POST" class="inline-block"
                                        onsubmit="return confirm('Yakin ingin menghapus regulasi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 sm:px-3 py-1 rounded font-semibold text-xs sm:text-sm transition">
                                            <i class="fas fa-trash"></i>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="border border-gray-300 px-4 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    @if($currentSearch || $currentJenisPeraturan || $currentStatus)
                                        <p class="text-base sm:text-lg font-medium">Tidak ada hasil yang ditemukan</p>
                                        <p class="text-xs sm:text-sm">Coba ubah kriteria pencarian atau filter Anda</p>
                                        <a href="{{ route('regulasi.index') }}" class="mt-2 text-blue-500 hover:text-blue-700 text-xs sm:text-sm">
                                            Lihat semua regulasi
                                        </a>
                                    @else
                                        <p class="text-base sm:text-lg font-medium">Belum ada data regulasi</p>
                                        <p class="text-xs sm:text-sm">Klik "Tambah" untuk membuat regulasi baru</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    {{-- Live search for regulasi --}}
    document.addEventListener('DOMContentLoaded', () => {
        initLiveSearchTable('#search', 'table tbody', {
            colIndex: 4,
            colspan: {{ Auth::user()->role == 'perencana' ? 8 : 7 }},
            rowSelector: 'tr[data-regulasi-row]',
            noResultText: 'Tidak ada hasil'
        });
    });
</script>
@endsection
