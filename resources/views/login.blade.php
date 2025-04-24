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
<body>
    <div class="relative mx-auto w-full max-w-md bg-gray-50 px-6 py-10 mt-40 shadow-xl ring-1 ring-gray-900/5 sm:rounded-xl sm:px-10">
        <div class="text-center mb-4">
            <h1 class="text-3xl font-semibold text-black">Sign in</h1>
        </div>
        <div class="bg-gray-200 hover:bg-gray-300 text-black text-center w-56 p-2 rounded-lg cursor-pointer mx-auto">
            <a href="/auth/redirect" class="inline-block w-full">
                <i class="fa-brands fa-google mr-2"></i>
                Sign In Google
            </a>
        </div>
    </div>
</body>
</html>
