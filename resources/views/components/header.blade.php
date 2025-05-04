<div class="flex-1 flex flex-col overflow-hidden">
    <header class="bg-gray-100 h-36 relative">
        <div class="bg-[#0069d9] h-28 pb-10 px-4 sm:px-6 flex items-center justify-between">
            <button id="toggleSidebar" class="text-white focus:outline-none cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            {{-- Logout --}}
            @if (Auth::check())
                <div class="float-right flex items-center space-x-6 text-white">
                    <i class="fa-solid fa-circle-user fa-2xl" style="color: #ffffff;"></i>
                    <div class="text-lg">
                        @if (Auth::user()->role == 'admin')
                            <p class="font-medium">Hai, Admin</p>
                        @elseif (Auth::user()->role == 'pjk')
                            <p class="font-medium">Hai, Penanggung Jawab Kegiatan</p>
                        @elseif (Auth::user()->role == 'perencana')
                            <p class="font-medium">Hai, Perencana</p>
                        @elseif (Auth::user()->role == 'pegawai')
                            <p class="font-medium">Hai, Pegawai</p>
                        @endif
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="cursor-pointer font-medium"><i class="fa-solid fa-right-from-bracket cursor-pointer"></i> Logout</button>
                    </form>
                </div>
            @endif
        </div>

        <div class="h-18 bg-white rounded-[3px] px-4 mx-6 -mt-10 flex items-center font-bold text-xl text-gray-800">
            <p>{{ $title ?? 'Default Title' }}</p>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-white mx-6 mt-4 rounded-[3px]">
        <!-- Konten utama akan diisi dari app.blade.php -->
        @yield('content')
    </main>
</div>
