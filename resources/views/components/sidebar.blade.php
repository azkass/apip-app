<aside id="sidebar" class="w-16 bg-white text-gray-600 transition-all duration-300 flex flex-col h-screen">
    <div class="p-4 flex justify-center">
        <a class="text-2xl font-bold" href="/">AP</a>
    </div>
    <div class="p-2 flex-grow">
        @if (Auth::user()->role == 'admin')
            <a href="/admin/dashboard" class="flex items-center py-2 justify-center sidebar-item">
                <i class="fa-solid fa-house mx-auto sidebar-icon fa-lg my-4" title="Dashboard"></i>
                <span class="ml-2 hidden sidebar-text">Dashboard</span>
            </a>
            <a href="/admin/list" class="flex items-center py-2 justify-center sidebar-item">
                <i class="fa-solid fa-user-shield mx-auto sidebar-icon fa-lg my-4" title="Manajemen Role"></i>
                <span class="ml-2 hidden sidebar-text">Manajemen Role</span>
            </a>
        @elseif (Auth::user()->role == 'pjk')
            <a href="/penanggungjawab/dashboard" class="flex items-center py-2 justify-center sidebar-item">
                <i class="fa-solid fa-house mx-auto sidebar-icon fa-lg my-4" title="Dashboard"></i>
                <span class="ml-2 hidden sidebar-text">Dashboard</span>
            </a>
            <a href="/penanggungjawab/prosedurpengawasan" class="flex items-center py-2 justify-center sidebar-item">
                <i class="fa-solid fa-clipboard-check mx-auto sidebar-icon fa-lg my-4" title=""></i>
                <span class="ml-2 hidden sidebar-text">Prosedur Pengawasan</span>
            </a>
            <a href="/penanggungjawab/instrumenpengawasan" class="flex items-center py-2 justify-center sidebar-item">
                <i class="fa-solid fa-tasks mx-auto sidebar-icon fa-lg my-4" title=""></i>
                <span class="ml-2 hidden sidebar-text">Instrumen Pengawasan</span>
            </a>
            <a href="" class="flex items-center py-2 justify-center sidebar-item">
                <i class="fa-solid fa-book mx-auto sidebar-icon fa-lg my-4" title=""></i>
                <span class="ml-2 hidden sidebar-text">Regulasi</span>
            </a>
        @elseif (Auth::user()->role == 'perencana')
            <a href="/" class="flex items-center py-2 justify-center sidebar-item">
                <i class="fa-solid fa-house sidebar-icon mx-auto fa-lg my-4" title="Dashboard"></i>
                <span class="ml-2 hidden sidebar-text">Dashboard</span>
            </a>
            <a href="/perencana/prosedurpengawasan" class="flex items-center py-2 justify-center sidebar-item">
                <i class="fa-solid fa-pen-ruler mx-auto sidebar-icon fa-lg my-4" title="Prosedur Pengawasan"></i>
                <span class="ml-2 hidden sidebar-text">Prosedur Pengawasan</span>
            </a>
            <a href="/perencana/instrumenpengawasan" class="flex items-center py-2 justify-center sidebar-item">
                <i class="fa-solid fa-file-signature mx-auto sidebar-icon fa-lg my-4" title="Instrumen Pengawasan"></i>
                <span class="ml-2 hidden sidebar-text">Instrumen Pengawasan</span>
            </a>
            <a href="/perencana/regulasi" class="flex items-center py-2 justify-center sidebar-item">
                <i class="fa-solid fa-square-poll-vertical mx-auto sidebar-icon fa-lg my-4" title="Regulasi"></i>
                <span class="ml-2 hidden sidebar-text">Regulasi</span>
            </a>
        @elseif (Auth::user()->role == 'pegawai')
            <a href="/pegawai/dashboard" class="flex items-center py-2 justify-center sidebar-item">
                <i class="fa-solid fa-house mx-auto sidebar-icon fa-lg my-4" title="Dashboard"></i>
                <span class="ml-2 hidden sidebar-text">Dashboard</span>
            </a>
            <a href="/pegawai/instrumenpengawasan" class="flex items-center py-2 justify-center sidebar-item">
                <i class="fa-solid fa-clipboard mx-auto sidebar-icon fa-lg my-4" title=""></i>
                <span class="ml-2 hidden sidebar-text">Instrumen Pengawasan</span>
            </a>
        @endif
    </div>
    <div class="pb-2 text-sm text-gray-500 sidebar-footer transition-all duration-75 mt-auto flex flex-col items-center">
        <div class="flex items-center sidebar-footer-small justify-center">
            <a href="https://github.com/azkass/sistem-sop" class="ml-1">©2025</a>
        </div>
        <div class="hidden sidebar-footer-full items-center">
            <div class="flex items-center">
                <p class="">©2025</p>
                <p class="ml-2">•</p>
                <a href="https://github.com/azkass/sistem-sop" class="ml-2">APIP</a>
                <i class="fa-solid fa-code-compare ml-2" title=""></i>
                <a href="https://github.com/azkass/sistem-sop" class="ml-1">V1.0</a>
            </div>
        </div>
    </div>
</aside>
