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
    </style>
</head>
<body class="font-inter">
    <!-- Navbar -->
    @include('components.navbar')

    <!-- Main Content -->
    <div class="container m-4">
        @yield('content')
    </div>

    <!-- Footer -->
    @include('components.footer')
</body>
</html>
