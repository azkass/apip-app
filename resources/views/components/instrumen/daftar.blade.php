<div class="container p-4">
    <div>
        <p class="font-semibold mb-1">Cari</p>
    </div>

    <div class="flex items-center mb-4">
        <!-- Search Bar -->
        <div class="flex border-2 border-gray-300 overflow-hidden rounded-md h-10 mr-2">
            <input type="text" placeholder="Cari Instrumen Pengawasan" class="w-96 outline-none bg-white text-gray-600 text-sm px-4 py-3" />
        </div>

        <!-- Filter Tahun -->
        <div class="relative mr-2 w-28">
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
                <svg class="w-4 h-4 text-gray-700 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </div>
        </div>

        <!-- Filter Status -->
        @if (Auth::user()->role != 'pegawai')
            <div class="relative mr-2 w-28">
                <select
                    id="statusDropdown"
                    class="block w-28 px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm appearance-none"
                    onchange="window.location.href=this.value"
                >
                    @if (Auth::user()->role == 'perencana')
                        <option value="{{ route('perencana.instrumen-pengawasan.index', ['status' => 'semua']) }}" {{ $activeTab == 'semua' ? 'selected' : '' }} class="flex items-center">
                    @elseif (Auth::user()->role == 'pjk')
                        <option value="{{ route('pjk.instrumen-pengawasan.index', ['status' => 'semua']) }}" {{ $activeTab == 'semua' ? 'selected' : '' }} class="flex items-center">
                    @endif
                        Semua
                    </option>

                    @if (Auth::user()->role == 'perencana')
                        <option value="{{ route('perencana.instrumen-pengawasan.index', ['status' => 'draft']) }}" {{ $activeTab == 'draft' ? 'selected' : '' }} class="flex items-center">
                    @elseif (Auth::user()->role == 'pjk')
                        <option value="{{ route('pjk.instrumen-pengawasan.index', ['status' => 'draft']) }}" {{ $activeTab == 'draft' ? 'selected' : '' }} class="flex items-center">
                    @endif
                        Draft
                    </option>

                    @if (Auth::user()->role == 'perencana')
                        <option value="{{ route('perencana.instrumen-pengawasan.index', ['status' => 'diajukan']) }}" {{ $activeTab == 'diajukan' ? 'selected' : '' }} class="flex items-center">
                    @elseif (Auth::user()->role == 'pjk')
                        <option value="{{ route('pjk.instrumen-pengawasan.index', ['status' => 'diajukan']) }}" {{ $activeTab == 'diajukan' ? 'selected' : '' }} class="flex items-center">
                    @endif
                        Diajukan
                    </option>

                    @if (Auth::user()->role == 'perencana')
                        <option value="{{ route('perencana.instrumen-pengawasan.index', ['status' => 'disetujui']) }}" {{ $activeTab == 'disetujui' ? 'selected' : '' }} class="flex items-center">
                    @elseif (Auth::user()->role == 'pjk')
                        <option value="{{ route('pjk.instrumen-pengawasan.index', ['status' => 'disetujui']) }}" {{ $activeTab == 'disetujui' ? 'selected' : '' }} class="flex items-center">
                    @endif
                        Disetujui
                    </option>
                </select>

                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-700 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>
        @endif


        <!-- Tombol Tambah -->
        @if (Auth::user()->role == 'perencana')
        <a href="{{ route('instrumen-pengawasan.create') }}" class="bg-blue-500 h-[38px] px-2 rounded-md text-white flex items-center justify-center font-medium">Tambah</a>
        @endif
    </div>


    <!-- Isi tabel -->
    <table class="w-full bg-gray-50">
        <thead>
            <tr>
                <th class="border border-gray-300 px-4 py-2 w-[20px] text-center">No</th>
                <th class="border border-gray-300 px-4 py-2 text-center">Judul</th>
                <th class="border border-gray-300 px-4 py-2 text-center">Status</th>
                <th class="border border-gray-300 px-4 py-2 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($instrumenPengawasan as $index => $instrumen)
            <tr class="hover:bg-gray-50 transition-colors duration-200">
                <td class="border border-gray-300 px-4 py-2">{{ $index + 1 }}</td>
                <td class="border border-gray-300 px-4 py-2 align-middle">
                    <a href="{{ route(Auth::user()->role . '.instrumen-pengawasan.detail', $instrumen->id) }}" class="inline-block w-full">
                       {{ $instrumen->judul }}
                    </a>
                </td>
                <td class="border border-gray-300 px-4 py-2">{{ ucfirst($instrumen->status) }}</td>
                <td class="border border-gray-300 px-4 py-2">
                    <a href="{{ route(Auth::user()->role . '.instrumen-pengawasan.edit', $instrumen->id) }}" class="">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>




</div>
