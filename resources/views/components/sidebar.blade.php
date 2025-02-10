<aside id="sidebar" class="w-16 bg-blue-500 text-white transition-all duration-300">
    <div class="p-4">
        <h2 class="text-2xl font-bold">AP</h2>
    </div>
    <nav class="mt-4">
        <ul>
            <li class="px-4 py-2 hover:bg-gray-700">
                <a href="#" class="block">Menu 1</a>
            </li>
            <li class="px-4 py-2 hover:bg-gray-700">
                <a href="#" class="block">Menu 2</a>
            </li>
            <li class="px-4 py-2 hover:bg-gray-700">
                <a href="#" class="block">Menu 3</a>
            </li>
        </ul>
    </nav>
    @if (Auth::check())
        @if (Auth::user()->role == 'admin')
            <p>Hai Admin</p>
        @elseif (Auth::user()->role == 'pjk')
            <p>Hai Penanggungjawab Kegiatan</p>
        @elseif (Auth::user()->role == 'perencana')
            <p>Hai Perencana</p>
        @elseif (Auth::user()->role == 'pegawai')
            <p>Hai Pegawai</p>
        @endif
    @endif
</aside>
