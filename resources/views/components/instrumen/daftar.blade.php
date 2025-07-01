<div class="container mx-auto p-4">
    <div class="flex items-center mb-6">
        <!-- Search Bar -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
            <div class="relative mr-2">
                <input type="text" id="search" placeholder="Cari instrumen pengawasan" 
                       class="w-80 px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200"/>
            </div>
        </div>

        <!-- Filter Tahun -->
        <div class="mr-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
            <div class="relative">
                <select
                    id="tahunDropdown"
                    class="block w-28 px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm appearance-none"
                    onchange="window.location.href=this.value"
                >
                    @if (Auth::user()->role == 'perencana')
                        @foreach (range(date('Y'), 2025) as $year)
                            <option value="{{ route('perencana.instrumen-pengawasan.index', ['status' => $activeTab, 'tahun' => $year]) }}"
                                    {{ request('tahun', date('Y')) == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    @elseif (Auth::user()->role == 'pjk')
                        @foreach (range(date('Y'), 2025) as $year)
                            <option value="{{ route('pjk.instrumen-pengawasan.index', ['status' => $activeTab, 'tahun' => $year]) }}"
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
        <div class="mr-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
        <div class="relative">
            <select
                id="statusDropdown"
                class="block w-28 px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm appearance-none"
                onchange="window.location.href=this.value"
            >
                @if (Auth::user()->role == 'perencana')
                    <option value="{{ route('perencana.instrumen-pengawasan.index', ['status' => 'semua']) }}" {{ $activeTab == 'semua' ? 'selected' : '' }}>
                        Semua
                    </option>
                    <option value="{{ route('perencana.instrumen-pengawasan.index', ['status' => 'draft']) }}" {{ $activeTab == 'draft' ? 'selected' : '' }}>
                        Draft
                    </option>
                    <option value="{{ route('perencana.instrumen-pengawasan.index', ['status' => 'diajukan']) }}" {{ $activeTab == 'diajukan' ? 'selected' : '' }}>
                        Diajukan
                    </option>
                    <option value="{{ route('perencana.instrumen-pengawasan.index', ['status' => 'disetujui']) }}" {{ $activeTab == 'disetujui' ? 'selected' : '' }}>
                        Disetujui
                    </option>
                @elseif (Auth::user()->role == 'pjk')
                    <option value="{{ route('pjk.instrumen-pengawasan.index', ['status' => 'semua']) }}" {{ $activeTab == 'semua' ? 'selected' : '' }}>
                        Semua
                    </option>
                    <option value="{{ route('pjk.instrumen-pengawasan.index', ['status' => 'draft']) }}" {{ $activeTab == 'draft' ? 'selected' : '' }}>
                        Draft
                    </option>
                    <option value="{{ route('pjk.instrumen-pengawasan.index', ['status' => 'diajukan']) }}" {{ $activeTab == 'diajukan' ? 'selected' : '' }}>
                        Diajukan
                    </option>
                    <option value="{{ route('pjk.instrumen-pengawasan.index', ['status' => 'disetujui']) }}" {{ $activeTab == 'disetujui' ? 'selected' : '' }}>
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
        <div>
            <a href="{{ route('instrumen-pengawasan.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition">
                Tambah Instrumen
            </a>
        </div>
        @endif
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Isi tabel -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="w-full border-collapse">
            <thead class="bg-gray-50">
                <tr>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">No</th>
                    <th class="border border-gray-300 px-4 py-3 text-left font-semibold">Judul</th>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">Status</th>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">Dokumen</th>
                    @if (Auth::user()->role != 'pegawai')
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($instrumenPengawasan as $index => $instrumen)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="border border-gray-300 px-4 py-3 text-center">{{ $index + 1 }}</td>
                    <td class="border border-gray-300 px-4 py-3">
                        <a href="{{ route(Auth::user()->role . '.instrumen-pengawasan.detail', $instrumen->id) }}">
                           {{ $instrumen->judul }}
                        </a>
                    </td>
                    <td class="border border-gray-300 px-4 py-3 text-center">
                        <span class="py-1 px-3 rounded-md text-white text-sm font-semibold
                            @if($instrumen->status == 'draft')
                                bg-yellow-500
                            @elseif($instrumen->status == 'diajukan')
                                bg-blue-600
                            @elseif($instrumen->status == 'disetujui')
                                bg-green-500
                            @endif">
                            {{ ucfirst($instrumen->status) }}
                        </span>
                    </td>

                    <td class="border border-gray-300 px-4 py-3 text-center">
                        <a href="{{ route('instrumen-pengawasan.download', $instrumen->id) }}" class="inline-flex px-3 py-1 text-sm font-semibold rounded bg-red-500 hover:bg-red-600 text-white transition">
                            <i class="fas fa-download mr-1"></i> PDF
                        </a>
                    </td>

                    @if (Auth::user()->role != 'pegawai')
                        <td class="border border-gray-300 px-4 py-3 text-center">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route(Auth::user()->role . '.instrumen-pengawasan.detail', $instrumen->id) }}" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm transition">
                                    Lihat
                                </a>
                                <a href="{{ route(Auth::user()->role . '.instrumen-pengawasan.edit', $instrumen->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition">
                                    Edit
                                </a>
                                @if (Auth::user()->role == 'perencana')
                                <form action="{{ route('instrumen-pengawasan.delete', $instrumen->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus instrumen ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition">
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
                            <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-lg font-medium">Belum ada data instrumen</p>
                            <p class="text-sm">Klik "Tambah Instrumen" untuk membuat instrumen baru</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
// JavaScript functionality can be added here
</script>
