<!-- Header dan Main Content -->
<div class="flex-1 flex flex-col overflow-hidden">
    <!-- Header -->
    <header class="bg-blue-600 shadow">
        <div class="py-6 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <button id="toggleSidebar" class="text-white focus:outline-none cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            {{-- Logout --}}
            @if (Auth::check())
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="float-right cursor-pointer text-white">Logout</button>
                </form>
            @endif
        </div>
    </header>
    <div class="h-12 bg-white rounded-sm p-4 mx-4 z-10">
        {{-- User Role --}}
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

    <!-- Main Content Area -->
    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200 p-4">
        <!-- Konten utama akan diisi dari app.blade.php -->
        @yield('content')
    </main>
</div>
