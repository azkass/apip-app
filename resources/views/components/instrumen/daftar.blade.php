<div class="w-full p-8">
    <!-- Filter section with responsive flex-wrap -->
    <div class="flex flex-wrap items-center gap-2 sm:gap-4 mb-6">
        <!-- Search Bar -->
        <div class="flex flex-col w-full mb-2 sm:mb-0 sm:w-auto">
            <label class="text-sm font-medium text-gray-700 mb-1">Cari</label>
            <div class="relative">
                <input type="text" id="search" placeholder="Cari instrumen pengawasan" autocomplete="off"
                       class="w-full sm:w-64 md:w-80 px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200"/>
            </div>
        </div>

        <!-- Dropdowns container for mobile layout -->
        <div class="flex w-full sm:w-auto gap-2">
            <!-- Filter Tahun -->
            <div class="flex flex-col w-1/2 sm:w-auto">
                <label class="text-sm font-medium text-gray-700 mb-1">Tahun</label>
                <div class="relative">
                    <select
                        id="tahunDropdown"
                        class="block w-full sm:w-24 px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm appearance-none"
                        onchange="window.location.href=this.value"
                    >
                        @if (Auth::user()->role == 'perencana' || Auth::user()->role == 'pjk')
                            @foreach (range(date('Y'), 2025) as $year)
                                <option value="{{ route('instrumen-pengawasan.index', ['status' => $activeTab, 'tahun' => $year]) }}"
                                        {{ request('tahun', date('Y')) == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Filter Status -->
            @if (Auth::user()->role != 'pegawai')
            <div class="flex flex-col w-1/2 sm:w-auto">
                <label class="text-sm font-medium text-gray-700 mb-1">Status</label>
                <div class="relative">
                    <select
                        id="statusDropdown"
                        class="block w-full sm:w-24 px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm appearance-none"
                        onchange="window.location.href=this.value"
                    >
                    @if (Auth::user()->role == 'perencana' || Auth::user()->role == 'pjk')
                        <option value="{{ route('instrumen-pengawasan.index', ['status' => 'semua']) }}" {{ $activeTab == 'semua' ? 'selected' : '' }}>
                            Semua
                        </option>
                        <option value="{{ route('instrumen-pengawasan.index', ['status' => 'draft']) }}" {{ $activeTab == 'draft' ? 'selected' : '' }}>
                            Draft
                        </option>
                        <option value="{{ route('instrumen-pengawasan.index', ['status' => 'diajukan']) }}" {{ $activeTab == 'diajukan' ? 'selected' : '' }}>
                            Diajukan
                        </option>
                        <option value="{{ route('instrumen-pengawasan.index', ['status' => 'disetujui']) }}" {{ $activeTab == 'disetujui' ? 'selected' : '' }}>
                            Disetujui
                        </option>
                    @endif
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Button Tambah -->
        @if (Auth::user()->role == 'perencana')
        <div class="flex flex-col w-full sm:w-auto mt-2 sm:mt-0">
            <label class="text-sm font-medium text-gray-700 mb-1 invisible">Aksi</label>
            <a href="{{ route('instrumen-pengawasan.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition w-full sm:w-auto text-center">
                Tambah
            </a>
        </div>
        @endif
    </div>

    <!-- Isi tabel dengan overflow handling -->
    <div class="bg-white rounded-lg">
        <div class="overflow-x-auto">
            <table class="border-collapse w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 text-center font-semibold">No</th>
                        <th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 text-left font-semibold w-xl">Nama Instrumen</th>
                        <th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 text-center font-semibold">Status</th>
                        <th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 text-center font-semibold">Dokumen</th>
                        @if (Auth::user()->role != 'pegawai' || Auth::user()->role != 'admin')
                        <th class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 text-center font-semibold">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($instrumenPengawasan as $index => $instrumen)
                    <tr class="hover:bg-gray-50 transition-colors" data-instrumen-row>
                        <td class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 text-center">{{ $index + 1 }}</td>
                        <td class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3">
                            <a href="{{ route('instrumen-pengawasan.detail', $instrumen->id) }}" class="hover:text-blue-600">
                               {{ $instrumen->nama }}
                            </a>
                        </td>
                        <td class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 text-center">
                            <span class="inline-flex px-2 py-1 text-base font-semibold rounded-full
                                @if($instrumen->status == 'draft')
                                bg-yellow-100 text-yellow-800
                                @elseif($instrumen->status == 'diajukan')
                                bg-blue-100 text-blue-800
                                @elseif($instrumen->status == 'disetujui')
                                bg-green-100 text-green-800
                                @endif">
                                {{ ucfirst($instrumen->status) }}
                            </span>
                        </td>

                        <td class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 text-center">
                            <a href="{{ route('instrumen-pengawasan.view', $instrumen->id) }}" target="_blank" class="inline-flex px-2 sm:px-4 py-2 text-xs sm:text-sm font-semibold rounded bg-green-500 hover:bg-green-600 text-white transition">
                                <i class="fas fa-eye mr-1"></i> Lihat PDF
                            </a>
                        </td>

                        @if (Auth::user()->role != 'pegawai' || Auth::user()->role != 'admin')
                            <td class="border border-gray-300 px-2 sm:px-4 py-2 sm:py-3 text-center">
                                <div class="flex flex-wrap justify-center gap-1 sm:gap-2">
                                    <a href="{{ route('instrumen-pengawasan.detail', $instrumen->id) }}" class="bg-green-500 hover:bg-green-600 text-white px-2 sm:px-3 py-1 rounded text-xs sm:text-sm transition">
                                        Lihat
                                    </a>
                                    <a href="{{ route('instrumen-pengawasan.edit', $instrumen->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-2 sm:px-3 py-1 rounded text-xs sm:text-sm transition">
                                        Edit
                                    </a>
                                    @if (Auth::user()->role == 'perencana')
                                    <form action="{{ route('instrumen-pengawasan.delete', $instrumen->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus instrumen ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 sm:px-3 py-1 rounded text-xs sm:text-sm transition">
                                            Hapus
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="border border-gray-300 px-4 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-base sm:text-lg font-medium">Belum ada data instrumen</p>
                                <p class="text-xs sm:text-sm">Klik "Tambah Instrumen" untuk membuat instrumen baru</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Legend with responsive layout -->
    <div class="mt-4 text-xs sm:text-sm text-gray-600">
        <div class="flex flex-wrap items-center gap-2 sm:gap-4">
            <div class="flex items-center">
                <span class="inline-block w-3 h-3 bg-yellow-100 rounded-full mr-1 sm:mr-2"></span>
                <span>Draft: Instrumen pengawasan dalam proses perancangan</span>
            </div>
            <div class="flex items-center">
                <span class="inline-block w-3 h-3 bg-blue-100 rounded-full mr-1 sm:mr-2"></span>
                <span>Diajukan: Instrumen pengawasan diajukan untuk ditinjau</span>
            </div>
            <div class="flex items-center">
                <span class="inline-block w-3 h-3 bg-green-100 rounded-full mr-1 sm:mr-2"></span>
                <span>Disetujui: Instrumen pengawasan telah ditinjau dan disetujui</span>
            </div>
        </div>
    </div>

    {{-- Live search for instrumen --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            initLiveSearchTable('#search', 'table tbody', {
                colIndex: 1, // kolom Judul
                colspan: {{ (Auth::user()->role != 'pegawai' && Auth::user()->role != 'admin') ? 5 : 4 }},
                rowSelector: 'tr[data-instrumen-row]',
                noResultText: 'Tidak ada hasil'
            });
        });
    </script>
</div>
