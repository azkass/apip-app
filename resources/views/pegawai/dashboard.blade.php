<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite('resources/css/app.css')
</head>
<body>
    @include('components.header')
    @include('components.sidebar')
    <div class="container m-4">
        @yield('content')
    </div>
    @include('components.footer')
</body>
</html>
