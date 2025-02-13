<!-- Header dan Main Content -->
<div class="flex-1 flex flex-col overflow-hidden">
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <button id="toggleSidebar" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            {{-- User Role --}}
            @if (Auth::check())
                @if (Auth::user()->role == 'admin')
                    <p>Hai, Admin</p>
                @elseif (Auth::user()->role == 'pjk')
                    <p>Hai, Penanggung Jawab Kegiatan</p>
                @elseif (Auth::user()->role == 'perencana')
                    <p>Hai, Perencana</p>
                @elseif (Auth::user()->role == 'pegawai')
                    <p>Hai, Pegawai</p>
                @endif
            @endif

            {{-- Logout --}}
            @if (Auth::check())
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="float-right cursor-pointer">Logout</button>
                </form>
            @endif
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200 p-4">
        <!-- Konten utama akan diisi dari app.blade.php -->
        @yield('content')
    </main>
</div>
