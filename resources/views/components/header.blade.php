<div class="flex-1 flex flex-col overflow-hidden">
    <header class="bg-gray-100 h-36 relative">
        <div class="bg-[#0069d9;] h-28 pb-10 px-4 sm:px-6 flex items-center justify-between">
            <button id="toggleSidebar" class="text-white focus:outline-none cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            {{-- Logout --}}
            @if (Auth::check())
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="float-right cursor-pointer text-white"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
                </form>
            @endif
        </div>

        <div class="h-18 bg-white rounded-[3px] px-4 mx-6 -mt-10 flex items-center font-bold text-xl text-gray-600">
                @if (Auth::user()->role == 'admin')
                    <p>Hai, Admin</p>
                @elseif (Auth::user()->role == 'pjk')
                    <p>Hai, Penanggung Jawab Kegiatan</p>
                @elseif (Auth::user()->role == 'perencana')
                    <p>Hai, Perencana</p>
                @elseif (Auth::user()->role == 'pegawai')
                    <p>Hai, Pegawai</p>
                @endif
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-4">
        <!-- Konten utama akan diisi dari app.blade.php -->
        @yield('content')
    </main>
</div>
