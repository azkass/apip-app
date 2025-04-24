<div class="container p-4">
    <div class="flex justify-between items-center mb-4">
        <!-- Search Bar -->
        <div class="flex border-2 border-blue-500 overflow-hidden rounded-md max-w-lg h-10 mr-4">
            <input type="text" placeholder="Cari Instrumen Pengawasan"
                class="w-full outline-none bg-white text-gray-600 text-sm px-4 py-3" />
            <button type='button' class="flex items-center justify-center bg-[#007bff] px-5 text-sm text-white cursor-pointer">
                Cari
            </button>
        </div>

        @if (Auth::user()->role == 'perencana')
        <a href="{{ route('instrumen-pengawasan.create') }}" class="bg-blue-500 p-2 rounded-md text-white font-medium">Tambah</a>
        @endif
        <!-- Tombol Tambah -->
    </div>

    <!-- Tab -->
    @if (Auth::user()->role != 'pegawai')
        <div class="flex overflow-x-auto overflow-y-hidden border-b border-gray-200 whitespace-nowrap dark:border-gray-700 mb-4">
            @if (Auth::user()->role == 'perencana')
                <a href="{{ route('perencana.instrumen-pengawasan.index', ['status' => 'semua']) }}"
            @elseif (Auth::user()->role == 'pjk')
                <a href="{{ route('pjk.instrumen-pengawasan.index', ['status' => 'semua']) }}"
            @endif
            class="inline-flex items-center h-10 px-2 py-2 -mb-px text-center {{ $activeTab == 'semua' ? 'text-blue-600 border-b-2 border-blue-500 dark:border-blue-400 dark:text-blue-300' : 'text-gray-700 border-transparent hover:border-gray-400 dark:text-white' }} bg-transparent border-b-2 sm:px-4 -px-1 whitespace-nowrap focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 sm:w-6 sm:h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                </svg>
                <span class="mx-1 text-sm sm:text-base">
                    Semua
                </span>
            </a>

            @if (Auth::user()->role == 'perencana')
                <a href="{{ route('perencana.instrumen-pengawasan.index', ['status' => 'draft']) }}"
            @elseif (Auth::user()->role == 'pjk')
                <a href="{{ route('pjk.instrumen-pengawasan.index', ['status' => 'draft']) }}"
            @endif
                class="inline-flex items-center h-10 px-2 py-2 -mb-px text-center {{ $activeTab == 'draft' ? 'text-blue-600 border-b-2 border-blue-500 dark:border-blue-400 dark:text-blue-300' : 'text-gray-700 border-transparent hover:border-gray-400 dark:text-white' }} bg-transparent border-b-2 sm:px-4 -px-1 whitespace-nowrap focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-1 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                </svg>
                <span class="mx-1 text-sm sm:text-base">
                    Draft
                </span>
            </a>

            @if (Auth::user()->role == 'perencana')
                <a href="{{ route('perencana.instrumen-pengawasan.index', ['status' => 'diajukan']) }}"
            @elseif (Auth::user()->role == 'pjk')
                <a href="{{ route('pjk.instrumen-pengawasan.index', ['status' => 'diajukan']) }}"
            @endif
            class="inline-flex items-center h-10 px-2 py-2 -mb-px text-center {{ $activeTab == 'diajukan' ? 'text-blue-600 border-b-2 border-blue-500 dark:border-blue-400 dark:text-blue-300' : 'text-gray-700 border-transparent hover:border-gray-400 dark:text-white' }} bg-transparent border-b-2 sm:px-4 -px-1 whitespace-nowrap focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-1 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span class="mx-1 text-sm sm:text-base">
                    Diajukan
                </span>
            </a>


            @if (Auth::user()->role == 'perencana')
                <a href="{{ route('perencana.instrumen-pengawasan.index', ['status' => 'disetujui']) }}"
            @elseif (Auth::user()->role == 'pjk')
                <a href="{{ route('pjk.instrumen-pengawasan.index', ['status' => 'disetujui']) }}"
            @endif
            class="inline-flex items-center h-10 px-2 py-2 -mb-px text-center {{ $activeTab == 'disetujui' ? 'text-blue-600 border-b-2 border-blue-500 dark:border-blue-400 dark:text-blue-300' : 'text-gray-700 border-transparent hover:border-gray-400 dark:text-white' }} bg-transparent border-b-2 sm:px-4 -px-1 whitespace-nowrap focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-1 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
                <span class="mx-1 text-sm sm:text-base">
                    Disetujui
                </span>
            </a>
        </div>
    @endif

    <!-- Isi tabel -->
    @foreach ($instrumenPengawasan as $instrumen)
    <div class="border-[1px] border-blue-500 rounded-lg w-72 h-40 mb-4 hover:bg-gray-50 transition relative px-4 py-2">
        <a href="{{ route(Auth::user()->role . '.instrumen-pengawasan.detail', $instrumen->id) }}" class="absolute inset-0"></a>
        <h1 class="font-bold text-xl">
            {{ $instrumen->judul }}
        </h1>
        <!-- <p class="">{{ $instrumen->petugas_nama }}</p> -->
        <p class="w-fit py-1 px-2 bg-blue-500 rounded-sm text-center text-white">{{ ucfirst($instrumen->status) }}</p>
        <!-- <p class="">{{ $instrumen->updated_at }}</p> -->
    </div>
    @endforeach
</div>
