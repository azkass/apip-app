<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'Default Title' }}</title>
    @vite('resources/css/app.css')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');
        @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css');
    </style>
</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
        @include('components.sidebar')
        @include('components.header')
    </div>

    <script>
    document.getElementById('toggleSidebar').addEventListener('click', function() {
        const sidebar = document.getElementById('sidebar');
        const sidebarTexts = document.querySelectorAll('.sidebar-text');
        const sidebarItems = document.querySelectorAll('.sidebar-item');
        const sidebarIcons = document.querySelectorAll('.sidebar-icon');
        const sidebarFooterSmall = document.querySelector('.sidebar-footer-small');
        const sidebarFooterFull = document.querySelector('.sidebar-footer-full');

        if (sidebar.classList.contains('w-64')) {
            // Ubah ke mode kecil
            sidebar.classList.remove('w-64');
            sidebar.classList.add('w-16');

            // Sembunyikan teks
            sidebarTexts.forEach(text => {
                text.classList.add('hidden');
            });

            // Pusatkan ikon secara horizontal
            sidebarItems.forEach(item => {
                item.classList.remove('justify-start');
                item.classList.add('justify-center');
            });

            // Set ikon ke tengah dan hapus margin
            sidebarIcons.forEach(icon => {
                icon.classList.remove('w-6');
                icon.classList.remove('ml-2');
                icon.classList.add('mx-auto');
            });

            // Tampilkan footer versi kecil, sembunyikan versi besar
            sidebarFooterSmall.classList.remove('hidden');
            sidebarFooterFull.classList.add('hidden');

        } else {
            // Ubah ke mode besar
            sidebar.classList.remove('w-16');
            sidebar.classList.add('w-64');

            // Tampilkan teks
            sidebarTexts.forEach(text => {
                text.classList.remove('hidden');
            });

            // Ubah alignment ke kiri
            sidebarItems.forEach(item => {
                item.classList.remove('justify-center');
                item.classList.add('justify-start');
            });

            // Atur ikon agar sesuai mode besar dengan margin
            sidebarIcons.forEach(icon => {
                icon.classList.add('w-6');
                icon.classList.add('ml-4');
                icon.classList.remove('mx-auto');
            });

            // Sembunyikan footer versi kecil, tampilkan versi besar
            sidebarFooterSmall.classList.add('hidden');
            sidebarFooterFull.classList.remove('hidden');
        }
    });
    </script>
</body>
</html>
