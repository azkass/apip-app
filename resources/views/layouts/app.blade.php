<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Default Title' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
        @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css');
    </style>
    @stack('scripts')
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside id="sidebar" class="w-16 bg-white text-gray-600 transition-all duration-300 flex flex-col h-screen">
            <div class="p-4 flex justify-center">
                <a class="text-2xl font-bold text-white" href="/">AP</a>
            </div>

            <div class="p-2 flex-grow">
                @if (Auth::user()->role == 'admin')
                    <a href="/dashboard" class="flex items-center py-2 justify-center sidebar-item">
                        <i class="fa-solid fa-house mx-auto sidebar-icon fa-lg my-4" title="Dashboard"></i>
                        <span class="ml-2 hidden sidebar-text">Dashboard</span>
                    </a>
                    <a href="/admin/list" class="flex items-center py-2 justify-center sidebar-item">
                        <i class="fa-solid fa-user-shield mx-auto sidebar-icon fa-lg my-4" title="Manajemen Role"></i>
                        <span class="ml-2 hidden sidebar-text">Manajemen Role</span>
                    </a>
                    <a href="/prosedur-pengawasan" class="flex items-center py-2 justify-center sidebar-item">
                        <i class="fa-solid fa-pen-ruler mx-auto sidebar-icon fa-lg my-4" title="Prosedur Pengawasan"></i>
                        <span class="ml-2 hidden sidebar-text">Prosedur Pengawasan</span>
                    </a>
                    <a href="/instrumenpengawasan" class="flex items-center py-2 justify-center sidebar-item">
                        <i class="fa-solid fa-tasks mx-auto sidebar-icon fa-lg my-4" title=""></i>
                        <span class="ml-2 hidden sidebar-text">Instrumen Pengawasan</span>
                    </a>
                    <a href="/regulasi" class="flex items-center py-2 justify-center sidebar-item">
                        <i class="fa-solid fa-square-poll-vertical mx-auto sidebar-icon fa-lg my-4" title="Regulasi"></i>
                        <span class="ml-2 hidden sidebar-text">Regulasi</span>
                    </a>
                    <a href="{{ route('admin.inspektur-utama.index') }}" class="flex items-center py-2 justify-center sidebar-item">
                        <i class="fa-solid fa-user-tie mx-auto sidebar-icon fa-lg my-4" title="Inspektur Utama"></i>
                        <span class="ml-2 hidden sidebar-text">Inspektur Utama</span>
                    </a>
                    <a href="{{ route('periode.index') }}" class="flex items-center py-2 justify-center sidebar-item">
                        <i class="fa-solid fa-calendar-days mx-auto sidebar-icon fa-lg my-4" title="Periode Evaluasi"></i>
                        <span class="ml-2 hidden sidebar-text">Periode Evaluasi</span>
                    </a>
                @elseif (Auth::user()->role == 'pjk')
                    <a href="/dashboard" class="flex items-center py-2 justify-center sidebar-item">
                        <i class="fa-solid fa-house mx-auto sidebar-icon fa-lg my-4" title="Dashboard"></i>
                        <span class="ml-2 hidden sidebar-text">Dashboard</span>
                    </a>
                    <a href="/tugas" class="flex items-center py-2 justify-center sidebar-item">
                        <i class="fa-solid fa-file-signature mx-auto sidebar-icon fa-lg my-4" title="Tugas Saya"></i>
                        <span class="ml-2 hidden sidebar-text">Tugas Saya</span>
                    </a>
                    <a href="/prosedur-pengawasan" class="flex items-center py-2 justify-center sidebar-item">
                        <i class="fa-solid fa-pen-ruler mx-auto sidebar-icon fa-lg my-4" title="Prosedur Pengawasan"></i>
                        <span class="ml-2 hidden sidebar-text">Prosedur Pengawasan</span>
                    </a>
                    <a href="/instrumenpengawasan" class="flex items-center py-2 justify-center sidebar-item">
                        <i class="fa-solid fa-tasks mx-auto sidebar-icon fa-lg my-4" title=""></i>
                        <span class="ml-2 hidden sidebar-text">Instrumen Pengawasan</span>
                    </a>
                    <a href="/monitoring-evaluasi" class="flex items-center py-2 justify-center sidebar-item">
                        <i class="fa-solid fa-clipboard-list mx-auto sidebar-icon fa-lg my-4" title="Evaluasi Prosedur"></i>
                        <span class="ml-2 hidden sidebar-text">Monitoring Evaluasi</span>
                    </a>
                    <a href="{{ route('periode.index') }}" class="flex items-center py-2 justify-center sidebar-item">
                        <i class="fa-solid fa-calendar-days mx-auto sidebar-icon fa-lg my-4" title="Periode Evaluasi"></i>
                        <span class="ml-2 hidden sidebar-text">Periode Evaluasi</span>
                    </a>
                @elseif (Auth::user()->role == 'perencana')
                    <a href="/" class="flex items-center py-2 justify-center sidebar-item">
                        <i class="fa-solid fa-house sidebar-icon mx-auto fa-lg my-4" title="Dashboard"></i>
                        <span class="ml-2 hidden sidebar-text">Dashboard</span>
                    </a>
                    <a href="/tugas" class="flex items-center py-2 justify-center sidebar-item">
                        <i class="fa-solid fa-file-signature mx-auto sidebar-icon fa-lg my-4" title="Tugas Saya"></i>
                        <span class="ml-2 hidden sidebar-text">Tugas Saya</span>
                    </a>
                    <a href="/prosedur-pengawasan" class="flex items-center py-2 justify-center sidebar-item">
                        <i class="fa-solid fa-pen-ruler mx-auto sidebar-icon fa-lg my-4" title="Prosedur Pengawasan"></i>
                        <span class="ml-2 hidden sidebar-text">Prosedur Pengawasan</span>
                    </a>
                    <a href="/instrumenpengawasan" class="flex items-center py-2 justify-center sidebar-item">
                        <i class="fa-solid fa-tasks mx-auto sidebar-icon fa-lg my-4" title=""></i>
                        <span class="ml-2 hidden sidebar-text">Instrumen Pengawasan</span>
                    </a>
                    <a href="/regulasi" class="flex items-center py-2 justify-center sidebar-item">
                        <i class="fa-solid fa-square-poll-vertical mx-auto sidebar-icon fa-lg my-4" title="Regulasi"></i>
                        <span class="ml-2 hidden sidebar-text">Regulasi</span>
                    </a>
                    <a href="/monitoring-evaluasi" class="flex items-center py-2 justify-center sidebar-item">
                        <i class="fa-solid fa-clipboard-list mx-auto sidebar-icon fa-lg my-4" title="Evaluasi Prosedur"></i>
                        <span class="ml-2 hidden sidebar-text">Monitoring Evaluasi</span>
                    </a>
                    <a href="{{ route('periode.index') }}" class="flex items-center py-2 justify-center sidebar-item">
                        <i class="fa-solid fa-calendar-days mx-auto sidebar-icon fa-lg my-4" title="Periode Evaluasi"></i>
                        <span class="ml-2 hidden sidebar-text">Periode Evaluasi</span>
                    </a>
                @elseif (Auth::user()->role == 'pegawai')
                    <a href="/dashboard" class="flex items-center py-2 justify-center sidebar-item">
                        <i class="fa-solid fa-house mx-auto sidebar-icon fa-lg my-4" title="Dashboard"></i>
                        <span class="ml-2 hidden sidebar-text">Dashboard</span>
                    </a>
                    <a href="/prosedur-pengawasan" class="flex items-center py-2 justify-center sidebar-item">
                        <i class="fa-solid fa-pen-ruler mx-auto sidebar-icon fa-lg my-4" title="Prosedur Pengawasan"></i>
                        <span class="ml-2 hidden sidebar-text">Prosedur Pengawasan</span>
                    </a>
                    <a href="/instrumenpengawasan" class="flex items-center py-2 justify-center sidebar-item">
                        <i class="fa-solid fa-clipboard mx-auto sidebar-icon fa-lg my-4" title=""></i>
                        <span class="ml-2 hidden sidebar-text">Instrumen Pengawasan</span>
                    </a>
                    <a href="/regulasi" class="flex items-center py-2 justify-center sidebar-item">
                        <i class="fa-solid fa-square-poll-vertical mx-auto sidebar-icon fa-lg my-4" title="Regulasi"></i>
                        <span class="ml-2 hidden sidebar-text">Regulasi</span>
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

        <!-- Header -->
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
                                <p class="">Hai, {{ Str::title(Auth::user()->name) }}</p>
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

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mx-6 mt-2 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                    <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mx-6 mt-2 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                    <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
            @endif

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-white mx-6 mt-4 rounded-[3px] mb-4">
                <!-- Konten utama akan diisi dari app.blade.php -->
                @yield('content')
            </main>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const toggleButton = document.getElementById('toggleSidebar');
        const sidebarTexts = document.querySelectorAll('.sidebar-text');
        const sidebarIcons = document.querySelectorAll('.sidebar-icon');
        const footerSmall = document.querySelector('.sidebar-footer-small');
        const footerFull = document.querySelector('.sidebar-footer-full');

        // Get sidebar state from localStorage or default to open
        let isOpen = localStorage.getItem('sidebarOpen') !== 'false';
        let isMobile = window.innerWidth < 768;

        // Apply sidebar state more efficiently
        function applySidebarState() {
            // Common state changes
            sidebar.classList.toggle('w-64', isOpen);
            sidebar.classList.toggle('w-16', !isOpen || !isMobile);
            sidebar.classList.toggle('hidden', isMobile && !isOpen);

            // Update appearance based on state
            if (isOpen) {
                sidebarTexts.forEach(text => text.classList.remove('hidden'));
                document.querySelectorAll('.sidebar-item').forEach(item => {
                    item.classList.remove('justify-center');
                    item.classList.add('justify-start');
                });
                sidebarIcons.forEach(icon => {
                    icon.classList.add('w-6', 'ml-3');
                    icon.classList.remove('mx-auto');
                });
                footerSmall.classList.add('hidden');
                footerFull.classList.remove('hidden');
            } else {
                sidebarTexts.forEach(text => text.classList.add('hidden'));
                document.querySelectorAll('.sidebar-item').forEach(item => {
                    item.classList.remove('justify-start');
                    item.classList.add('justify-center');
                });
                sidebarIcons.forEach(icon => {
                    icon.classList.remove('w-6', 'ml-3');
                    icon.classList.add('mx-auto');
                });
                footerSmall.classList.remove('hidden');
                footerFull.classList.add('hidden');
            }

            // Save state to localStorage
            localStorage.setItem('sidebarOpen', isOpen);
        }

        // Toggle sidebar state with button
        toggleButton.addEventListener('click', function() {
            isOpen = !isOpen;
            applySidebarState();
        });

        // We want to close the sidebar on menu click for both mobile and desktop
        document.querySelector('.p-2.flex-grow').addEventListener('click', function(e) {
            const sidebarItem = e.target.closest('.sidebar-item');
            if (sidebarItem) {
                // Close the sidebar in localStorage for both mobile and desktop
                localStorage.setItem('sidebarOpen', 'false');
                // No preventDefault, let the link navigate normally
                return true;
            }
        });

        // Efficient window resize handling
        const handleResize = () => {
            const wasMobile = isMobile;
            isMobile = window.innerWidth < 768;

            if (wasMobile !== isMobile) {
                applySidebarState();
            }
        };

        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(handleResize, 100);
        });

        // Initialize sidebar
        applySidebarState();
    });
    </script>

</body>
</html>
