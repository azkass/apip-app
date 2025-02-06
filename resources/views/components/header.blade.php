<div class="font-bold text-xl bg-blue-500 text-white p-4">
    <a href="/">Aplikasi Prosedur dan Instrumen Pengawasan</a>
    @if (Auth::check())
        {{-- <a href="{{ route('logout') }}" class="float-right" method="POST">Logout</a> --}}
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="float-right cursor-pointer">Logout</button>
        </form>
    @endif

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
</div>
