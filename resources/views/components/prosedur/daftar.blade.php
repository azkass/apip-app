<div class="container p-8">
    @if (Auth::user()->role == 'perencana')
        <a href="/perencana/prosedurpengawasan/create-cover" class="bg-blue-500 hover:bg-blue-400 text-white px-4 py-2 rounded-sm mb-4">Create Cover Fix Code</a> <br><br>
        <a href="/perencana/prosedurpengawasan/create-fix" class="bg-blue-500 hover:bg-blue-400 text-white px-4 py-2 rounded-sm">Create Body Fix Code</a>
    @endif

    <div class="flex items-center my-6">
    <!-- Search Bar -->
    <div>
        <label class="font-semibold">Cari</label>
        <div class="flex border-2 border-gray-300 overflow-hidden rounded-md h-10 mr-2 mt-1">
            <input type="text" id="search" placeholder="Cari prosedur Pengawasan" class="form-control w-96 outline-none bg-white text-gray-600 text-sm px-4 py-3" />
        </div>
    </div>

    <!-- Filter Tahun -->
    <div>
        <label class="font-semibold">Tahun</label>
        <div class="relative mr-2 w-28 mt-1">
            <select
                id="tahunDropdown"
                class="block w-28 px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm appearance-none"
                onchange="window.location.href=this.value"
            >
                @if (Auth::user()->role == 'perencana')
                    @foreach (range(date('Y'), 2025) as $year)
                        <option value="{{ route('perencana.prosedur-pengawasan.index', ['status' => $activeTab, 'tahun' => $year]) }}"
                                {{ request('tahun', date('Y')) == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                @elseif (Auth::user()->role == 'pjk')
                    @foreach (range(date('Y'), 2025) as $year)
                        <option value="{{ route('pjk.prosedur-pengawasan.index', ['status' => $activeTab, 'tahun' => $year]) }}"
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
    </div>

    <!-- Filter Status -->
    @if (Auth::user()->role != 'pegawai')
    <div>
        <label class="font-semibold">Status</label>
        <div class="relative mr-2 w-28 mt-1">
            <select
                id="statusDropdown"
                class="block w-28 px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm appearance-none"
                onchange="window.location.href=this.value"
            >
                @if (Auth::user()->role == 'perencana')
                    <option value="{{ route('perencana.prosedur-pengawasan.index', ['status' => 'semua']) }}" {{ $activeTab == 'semua' ? 'selected' : '' }} class="flex items-center">
                @elseif (Auth::user()->role == 'pjk')
                    <option value="{{ route('pjk.prosedur-pengawasan.index', ['status' => 'semua']) }}" {{ $activeTab == 'semua' ? 'selected' : '' }} class="flex items-center">
                @endif
                    Semua
                </option>

                @if (Auth::user()->role == 'perencana')
                    <option value="{{ route('perencana.prosedur-pengawasan.index', ['status' => 'draft']) }}" {{ $activeTab == 'draft' ? 'selected' : '' }} class="flex items-center">
                @elseif (Auth::user()->role == 'pjk')
                    <option value="{{ route('pjk.prosedur-pengawasan.index', ['status' => 'draft']) }}" {{ $activeTab == 'draft' ? 'selected' : '' }} class="flex items-center">
                @endif
                    Draft
                </option>

                @if (Auth::user()->role == 'perencana')
                    <option value="{{ route('perencana.prosedur-pengawasan.index', ['status' => 'diajukan']) }}" {{ $activeTab == 'diajukan' ? 'selected' : '' }} class="flex items-center">
                @elseif (Auth::user()->role == 'pjk')
                    <option value="{{ route('pjk.prosedur-pengawasan.index', ['status' => 'diajukan']) }}" {{ $activeTab == 'diajukan' ? 'selected' : '' }} class="flex items-center">
                @endif
                    Diajukan
                </option>

                @if (Auth::user()->role == 'perencana')
                    <option value="{{ route('perencana.prosedur-pengawasan.index', ['status' => 'disetujui']) }}" {{ $activeTab == 'disetujui' ? 'selected' : '' }} class="flex items-center">
                @elseif (Auth::user()->role == 'pjk')
                    <option value="{{ route('pjk.prosedur-pengawasan.index', ['status' => 'disetujui']) }}" {{ $activeTab == 'disetujui' ? 'selected' : '' }} class="flex items-center">
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
    </div>
    @endif


    <!-- Button Tambah -->
    @if (Auth::user()->role == 'perencana')
    <div>
        <a href="{{ route('prosedur-pengawasan.create') }}" class="bg-blue-500 hover:bg-blue-600 h-[38px] mt-7 px-3 rounded-md text-white flex items-center justify-center font-medium">Tambah</a>
    </div>
    @endif
</div>


<!-- Isi tabel -->
<table class="w-full bg-gray-50">
    <thead class="bg-gray-200">
        <tr>
            <th class="border border-gray-300 px-4 py-2 w-[20px] text-center">No</th>
            <th class="border border-gray-300 px-4 py-2 w-[150px] text-center">Nomor SOP</th>
            <th class="border border-gray-300 px-4 py-2 text-center">Judul</th>
            <th class="border border-gray-300 px-4 py-2 text-center w-40">Status</th>
            @if (Auth::user()->role != 'pegawai')
            <th class="border border-gray-300 px-4 py-2 text-center w-36">Aksi</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($prosedurPengawasan as $index => $prosedur)
        <tr class="hover:bg-white transition-colors duration-200">
            <td class="border border-gray-300 px-4 py-2 text-center">{{ $index + 1 }}</td>
            <td class="border border-gray-300 px-4 py-2 text-left">
                <a href="{{ route(Auth::user()->role . '.prosedur-pengawasan.detail', $prosedur->id) }}" class="inline-block w-full">
                    {{ $prosedur->nomor }}
                </a>
            </td>
            <td class="border border-gray-300 px-4 py-2 align-middle">
                <a href="{{ route(Auth::user()->role . '.prosedur-pengawasan.detail', $prosedur->id) }}" class="inline-block w-full">
                   {{ $prosedur->judul }}
                </a>
            </td>
            <td class="border border-gray-300 text-center text-white font-semibold">
                <span class="py-2 px-3 rounded-md
                    @if($prosedur->status == 'draft')
                        bg-yellow-500
                    @elseif($prosedur->status == 'diajukan')
                        bg-blue-600
                    @elseif($prosedur->status == 'disetujui')
                        bg-green-500
                    @endif">
                    {{ ucfirst($prosedur->status) }}
                </span>
            </td>

            @if (Auth::user()->role != 'pegawai')
                <td class="border border-gray-300 px-4 py-3 text-center font-semibold text-white">
                    <a href="{{ route(Auth::user()->role . '.prosedur-pengawasan.edit', $prosedur->id) }}" class="py-2 px-4 bg-sky-500 hover:bg-sky-600 rounded-md">Edit</a>
                </td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>
</div>
