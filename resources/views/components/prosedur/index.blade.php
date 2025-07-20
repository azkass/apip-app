<div class="w-fit p-8">
    <div class="flex items-center gap-4 mb-6">
        <!-- Search Bar -->
        <div class="flex flex-col">
            <label class="text-sm font-medium text-gray-700 mb-1">Cari</label>
            <div class="relative">
                <input type="text" id="search" placeholder="Cari prosedur pengawasan" autocomplete="off"
                       class="w-80 px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200"/>
            </div>
        </div>

        <!-- Filter Tahun -->
        <div class="flex flex-col">
            <label class="text-sm font-medium text-gray-700 mb-1">Tahun</label>
            <div class="relative">
                <select
                    id="tahunDropdown"
                    class="block w-32 px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md appearance-none focus:ring focus:ring-blue-200"
                    onchange="window.location.href=this.value"
                >
                    @if (Auth::user()->role == 'perencana' || Auth::user()->role == 'pjk')
                        @foreach (range(date('Y'), 2025) as $year)
                            <option value="{{ route('prosedur-pengawasan.index', ['status' => $activeTab, 'tahun' => $year]) }}"
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
        <div class="flex flex-col">
            <label class="text-sm font-medium text-gray-700 mb-1">Status</label>
            <div class="relative">
                <select
                    id="statusDropdown"
                    class="block w-44 px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md appearance-none focus:ring focus:ring-blue-200"
                    onchange="window.location.href=this.value"
                >
                    @if (Auth::user()->role == 'perencana' || Auth::user()->role == 'pjk')
                        <option value="{{ route('prosedur-pengawasan.index', ['status' => 'semua', 'tahun' => request('tahun', date('Y'))]) }}" {{ $activeTab == 'semua' ? 'selected' : '' }}>
                            Semua
                        </option>

                        <option value="{{ route('prosedur-pengawasan.index', ['status' => 'draft', 'tahun' => request('tahun', date('Y'))]) }}" {{ $activeTab == 'draft' ? 'selected' : '' }}>
                            Draft
                        </option>

                        <option value="{{ route('prosedur-pengawasan.index', ['status' => 'diajukan', 'tahun' => request('tahun', date('Y'))]) }}" {{ $activeTab == 'diajukan' ? 'selected' : '' }}>
                            Diajukan
                        </option>

                        <option value="{{ route('prosedur-pengawasan.index', ['status' => 'revisi', 'tahun' => request('tahun', date('Y'))]) }}" {{ $activeTab == 'revisi' ? 'selected' : '' }}>
                            Revisi
                        </option>

                        <option value="{{ route('prosedur-pengawasan.index', ['status' => 'menunggu_disetujui', 'tahun' => request('tahun', date('Y'))]) }}" {{ $activeTab == 'menunggu_disetujui' ? 'selected' : '' }}>
                            Menunggu disetujui
                        </option>

                        <option value="{{ route('prosedur-pengawasan.index', ['status' => 'disetujui', 'tahun' => request('tahun', date('Y'))]) }}" {{ $activeTab == 'disetujui' ? 'selected' : '' }}>
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

        <!-- Button Tambah -->
        @if (Auth::user()->role == 'perencana')
        <div class="flex flex-col">
            <label class="text-sm font-medium text-gray-700 mb-1 invisible">Aksi</label>
            <a href="{{ route('prosedur-pengawasan.create') }}"
               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition">
                Tambah
            </a>
        </div>
        @endif
    </div>

    <!-- Isi tabel -->
    <div class="bg-white rounded-lg w-full">
        <table class="border-collapse w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">No</th>
                    <th class="border border-gray-300 px-4 py-3 text-left font-semibold">Nomor SOP</th>
                    <th class="border border-gray-300 px-4 py-3 text-left font-semibold">Nama SOP</th>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">Pembuat</th>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">Penyusun</th>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">Status</th>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">Aksi</th>
                    @if (Auth::user()->role == 'pjk')
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">Evaluasi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($prosedurPengawasan as $index => $prosedur)
                <tr class="hover:bg-gray-50 transition-colors" data-prosedur-row>
                    <td class="border border-gray-300 px-4 py-3 text-center">{{ $index + 1 }}</td>
                    <td class="border border-gray-300 px-4 py-3">
                        <a href="{{ route('prosedur-pengawasan.show', $prosedur->id) }}" class="hover:text-blue-600">
                            {{ $prosedur->nomor }}
                        </a>
                    </td>
                    <td class="border border-gray-300 px-4 py-3">
                        <a href="{{ route('prosedur-pengawasan.show', $prosedur->id) }}" class="hover:text-blue-600">
                           {{ $prosedur->nama }}
                        </a>
                    </td>
                    <td class="border border-gray-300 px-4 py-3 text-center">
                        {{ $prosedur->perencana_nama }}
                    </td>
                    <td class="border border-gray-300 px-4 py-3 text-center">
                        {{ $prosedur->petugas_nama }}
                    </td>
                    <td class="border border-gray-300 px-4 py-3 text-center">
                        <span class="inline-flex px-2 py-1 text-base font-semibold rounded-full
                            @if($prosedur->status == 'draft')
                                bg-yellow-100 text-yellow-800
                            @elseif($prosedur->status == 'diajukan')
                                bg-blue-100 text-blue-800
                            @elseif($prosedur->status == 'revisi')
                                bg-orange-100 text-orange-800
                            @elseif($prosedur->status == 'menunggu_disetujui')
                                bg-purple-100 text-purple-800
                            @elseif($prosedur->status == 'disetujui')
                                bg-green-100 text-green-800
                            @endif">
                            {{ str_replace('_', ' ', ucfirst($prosedur->status)) }}
                        </span>
                    </td>

                    <td class="border border-gray-300 px-4 py-3 text-center">
                        <div class="flex justify-center space-x-2">
                            <a href="{{ route('prosedur-pengawasan.show', $prosedur->id) }}"
                               class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm transition">
                                Lihat
                            </a>
                            @if (Auth::user()->role == 'perencana' || Auth::user()->role == 'pjk')
                                <a href="{{ route('prosedur-pengawasan.edit', $prosedur->id) }}"
                               class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition">
                                Edit
                            </a>
                            @endif
                        </div>
                    </td>

                    @if (Auth::user()->role == 'pjk')
                    <td class="border border-gray-300 px-4 py-3 text-center">
                        @php
                            $periode = DB::table("periode_evaluasi_prosedur")->latest()->first();
                            $now = now()->toDateString();
                            $isPeriodeActive = $periode && $now >= $periode->mulai && $now <= $periode->berakhir;
                        @endphp

                        @if ($isPeriodeActive)
                            <a href="{{ route('monitoring-evaluasi.create', $prosedur->id) }}"
                               class="bg-purple-500 hover:bg-purple-600 text-white px-3 py-1 rounded text-sm transition">
                                Evaluasi
                            </a>
                        @endif
                    </td>
                    @endif
                </tr>
                @endforeach

                @if(count($prosedurPengawasan) == 0)
                <tr>
                    <td colspan="{{ Auth::user()->role == 'pjk' ? '8' : '7' }}" class="border border-gray-300 px-4 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-lg font-medium">Belum ada prosedur pengawasan</p>
                            <p class="text-sm">Klik "Tambah" untuk membuat prosedur pengawasan baru</p>
                        </div>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="mt-4 text-sm text-gray-600">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex items-center">
                <span class="inline-block w-3 h-3 bg-yellow-100 rounded-full mr-2"></span>
                <span>Draft: Prosedur dalam proses perancangan</span>
            </div>
            <div class="flex items-center">
                <span class="inline-block w-3 h-3 bg-blue-100 rounded-full mr-2"></span>
                <span>Diajukan: Prosedur diajukan untuk ditinjau</span>
            </div>
            <div class="flex items-center">
                <span class="inline-block w-3 h-3 bg-green-100 rounded-full mr-2"></span>
                <span>Disetujui: Prosedur telah ditinjau dan disetujui</span>
            </div>
        </div>
    </div>
</div>

{{-- Live search --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        initLiveSearchTable('#search', 'table tbody', {
            colIndex: 2,
            colspan: {{ Auth::user()->role == 'pjk' ? 8 : 7 }},
            rowSelector: 'tr[data-prosedur-row]',
            noResultText: 'Tidak ada hasil'
        });
    });
</script>
