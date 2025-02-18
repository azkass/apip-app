<aside id="sidebar" class="w-16 bg-white text-gray-600 transition-all duration-300">
    <div class="p-4">
        <a class="text-2xl font-bold" href="/">AP</a>
    </div>
    <div class="p-2">
        @if (Auth::user()->role == 'admin')
            <a href="/admin/list">Manajemen Role</a>
        @elseif (Auth::user()->role == 'pjk')
            <a href="/penanggungjawab/daftarinstrumenpengawasan">Instrumen Pengawasan</a>
        @elseif (Auth::user()->role == 'perencana')
            <a href="/perencana/daftarinstrumenpengawasan">Instrumen Pengawasan</a>
        @elseif (Auth::user()->role == 'pegawai')
            <p>Hai Pegawai</p>
        @endif
    </div>
</aside>
